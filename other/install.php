<?php

/**
 * smCore Installation script.
 *
 * Sets up the database if settings.php is correctly configured.
 *
 * @package smCore
 * @author smCore Dev Team
 * @license MPL 1.1
 * @version 1.0 Alpha
 *
 * The contents of this file are subject to the Mozilla Public License Version 1.1
 * (the "License"); you may not use this package except in compliance with the
 * License. You may obtain a copy of the License at http://www.mozilla.org/MPL/
 *
 * The Original Code is smCore.
 *
 * The Initial Developer of the Original Code is the smCore project.
 *
 * Portions created by the Initial Developer are Copyright (C) 2013
 * the Initial Developer. All Rights Reserved.
 *
 * @todo Move up to the main directory
 * @todo Use smCore's database libraries
 * @todo Actually perform the database query
 * @todo Create the settings file
 */

// Register the autoloader
require_once(dirname(dirname(__FILE__)) . '/library/smCore/Autoloader.php');
new smCore\Autoloader(null, dirname(dirname(__FILE__)) . '/library');

// Does the settings file exist?
if(!file_exists('../settings.php'))
    die('Please create your settings.php file in '.dirname(dirname(__FILE__)).' and configure it based upon '.dirname(dirname(__FILE__)).'/other/settings.php');

// Liad up the settings
require('../settings.php');
$settings = new Settings;

// Has the URL been changed?
if($settings['url'] == 'http://www.youdidntchangeyoursettingsfile.lol')
{
    die('You have not correctly set your URL in '.dirname(dirname(__FILE__)).'/settings.php');
}
// They need to set a database user
if($settings['database']['user'] === '')
{
    die('You have not correctly set your MySQL username in '.dirname(dirname(__FILE__)).'/settings.php');
}

// Connect to the database
$con = mysql_connect($settings['database']['host'], $settings['database']['user'], $settings['database']['password']);
if(!$con)
{
    die('MySQL connection failed. Please check your settings in '.dirname(dirname(__FILE__)).'/settings.php');
}

$qry = file_get_contents('database.sql');
$fixedqry = str_replace('{db_prefix}', $settings['database']['dbname'].'`.`'.$settings['database']['prefix'], $qry);
echo "<!doctype html><html><head><title>smCore installation script</title></head><body><p>Below is your query ready to run in PHPMyAdmin</p>\n<textarea>" , $fixedqry, '<textarea>';