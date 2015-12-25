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

// List of receivers
$receivers = array();
$receivers[] = "your-email@example.com";

// Feed Configuration
$feed_url = 'http://feeds.joomla.org/JoomlaSecurityNews';

// Database Configurations
$config['host'] = 'localhost';
$config['dbname'] = '';
$config['username'] = '';
$config['password'] = '';
$config['table'] = 'wsy_rss_news'; // You can name the database whatever you want. Make sure it is named uniquely

