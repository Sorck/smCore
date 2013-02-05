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
require_once(__DIR__ . '/library/smCore/Autoloader.php');
new smCore\Autoloader(null, __DIR__ . '/library');

echo '<!doctype html>
<head>
<title>smCore installation</title>
</head>
<body>
<h1>This is pre-alpha software and is for development purposes only!</h1>';

// Does the settings file exist?
if(!file_exists('settings.php'))
{
    // Try building a settings file
	$protocol = 'http'.(!empty($_SERVER['HTTPS']) ? 's' : '');
	$url = $protocol.'://'.$_SERVER['HTTP_HOST'] . rtrim(str_replace(str_replace(array(__DIR__, '\\', '/'), '', __FILE__), '', $_SERVER['SCRIPT_NAME']), '/');
	$settings = file_get_contents(__DIR__ . '/other/settings.php');
	$settings = str_replace(array(
		'/home/my_site/public_html',
		'http://www.youdidntchangeyoursettingsfile.lol',
		'smcore.mysite.com',
		'noreply@mysite.com',
	),
	array(
		rtrim(__DIR__, '\\/'),
		$url,
		$_SERVER['HTTP_HOST'],
		'noreply@' . $_SERVER['HTTP_HOST'],
	), $settings);
	file_put_contents('settings.php', $settings);
	echo 'Settings file created.';
}

// Load up the settings
require('settings.php');
$settings = new Settings;

// Has the URL been changed?
if($settings['url'] == 'http://www.youdidntchangeyoursettingsfile.lol')
{
    die('You have not correctly set your URL in ' . __DIR__ . '/settings.php');
}

// Connect to the database
$con = mysql_connect($settings['database']['host'], $settings['database']['user'], $settings['database']['password']);
if(!$con)
{
	echo '<p>Please add your database settings to the settings file.</p>';
}
else
{
	$qry = file_get_contents('other/database.sql');
	$fixedqry = str_replace('{db_prefix}', $settings['database']['dbname'].'`.`'.$settings['database']['prefix'], $qry);
	mysql_query($fixedqry);
	echo '<p>Database should have been installed.</p>';
	echo "<p>If it hasn't been installed, run the following query in PHPMyAdmin:</p>\n<textarea>" , $fixedqry, '</textarea>';
}

echo '
</body>
</html>';