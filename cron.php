<?php
/**
 * @Copyright
 * @package     dk.wsy.simpleRssFeedNotifier
 * @author      Wel Rachid https://twitter.com/welrachid
 * @version     1.0 2015-12-24
 * @link        https://github.com/welrachid/dk.wsy.simpleRssFeedNotfier
 *
 * @license     GNU/GPL
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

require_once('config.php');
$db = new PDO('mysql:host='.$config['host'].';dbname='.$config['dbname'].';charset=utf8', $config['username'], $config['password']);
installDB($db,$config);
$array = simplexml_load_file($feed_url);
$new_entries = array();
foreach($array->channel->item as $key => $item){
	if(addNewEntry($db, $config, $feed_url, $item->link, $item->title, $item->guid)){
		$new_entries[] = $item;
	}
}
if(count($new_entries)>0 && count($receivers)>0){
	sendMail($receivers, $new_entries);
}

function addNewEntry($db,$config, $feed_url, $link, $title, $guid){
	try{
		$stmt = $db->prepare("INSERT INTO `".$config['table']."` (feed_url, link, title, guid, created)
				VALUES(:feed_url, :link, :title, :guid, now())");
		if (!$stmt->execute(array(':feed_url' => $feed_url, ':link' => $link, ':title' => $title, ':guid' => $guid))){
			$err = $stmt->errorInfo();
			if($err[1] == 1062){
				/* Dublicate entry - return false;*/
			}
			return false;
		}else {
			return true;
		}
	}catch(PDOException $ex){
		echo $ex->getMessage();
	}
	return false;
}
function installDB($db,$config){
	// This sql will create table with indexes and primary key
	$sql_install = "CREATE TABLE IF NOT EXISTS `".$config['table']."` (
	`id` int(99) NOT NULL,
	  `feed_url` varchar(255) NOT NULL,
	  `link` varchar(255) NOT NULL,
	  `title` varchar(255) NOT NULL,
	  `guid` varchar(255) NOT NULL,
	  `created` datetime NOT NULL
	) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

	ALTER TABLE `".$config['table']."`
	 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `feed_url_2` (`feed_url`,`guid`), ADD KEY `title` (`title`), ADD KEY `guid` (`guid`), ADD KEY `created` (`created`), ADD KEY `feed_url` (`feed_url`), ADD KEY `link` (`link`);

	ALTER TABLE `".$config['table']."`
	MODIFY `id` int(99) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;";
	$db->exec($sql_install);
}
function sendMail($receivers, $new_entries){
	// Contents of email
	foreach($receivers as $key => $receiver){
		$msg = "Hi

Here are the new entries:
-------------------------
";
		foreach($new_entries as $entry){
			$msg .= $entry->title." ".$entry->link."\n";
		}
$msg .= "-------------------------

Best regards

";
		// Standard sendmail - Customize as needed
		mail($receiver, "New RSS entry",$msg);
	}
}
echo "Done";