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
 * @todo Remove the /other directory
 */

// Register the autoloader
require_once dirname(__DIR__) . '/library/smCore/Autoloader.php';
new smCore\Autoloader(null, dirname(__DIR__) . '/library');

echo '<!doctype html>
<head>
<title>smCore installation</title>
</head>
<body>
<h1>This is pre-alpha software and is for development purposes only!</h1>';

do {
    // Does the settings file exist?
	if (!file_exists('../settings.php'))
	{
		// Try building a settings file
		$protocol = 'http'.(!empty($_SERVER['HTTPS']) ? 's' : '');
		// @todo URL generating is failing now.
		$url = $protocol.'://'.$_SERVER['HTTP_HOST'] . rtrim(str_replace(str_replace(array(dirname(__DIR__), '\\', '/'), '', __FILE__), '', $_SERVER['SCRIPT_NAME']), '/');
		$settings = file_get_contents(__DIR__ . '/settings.php');
		$settings = str_replace(array(
			'/home/my_site/public_html',
			'http://www.youdidntchangeyoursettingsfile.lol',
			'smcore.mysite.com',
			'noreply@mysite.com',
		),
		array(
			rtrim(dirname(__DIR__), '\\/'),
			$url,
			$_SERVER['HTTP_HOST'],
			'noreply@' . $_SERVER['HTTP_HOST'],
		), $settings);
		
		file_put_contents(dirname(__DIR__) . '/settings.php', $settings);
		echo 'Settings file created.';
	}

	// Load up the settings
	require dirname(__DIR__) . '/settings.php';
	$settings = new Settings;

	// Has the URL been changed?
	if ($settings['url'] == 'http://www.youdidntchangeyoursettingsfile.lol')
	{
		echo 'You have not correctly set your URL in ' . dirname(__DIR__) . '/settings.php';
		break;
	}

	if (empty($settings['database']['user']))
	{
		echo 'You have not correctly set your database login details.';
		break;
	}

	// Connect to the database
	$con = mysql_connect($settings['database']['host'], $settings['database']['user'], $settings['database']['password']);
	if (!$con)
	{
		echo '<p>Please add your database settings to the settings file.</p>';
		break;
	}
	else
	{
		$qry = file_get_contents('database.sql');
		$fixedqry = str_replace('{db_prefix}', $settings['database']['dbname'].'`.`'.$settings['database']['prefix'], $qry);
		$queries = explode(';', $fixedqry);
		do {
			foreach ($queries as $query)
			{
				!$query ?: mysql_query($query);
				if ($error = mysql_error())
				{
					echo "<p>", $error, "</p>
					<p>MySQL error. To complete installation please run the following query in PHPMyAdmin:</p>\n<textarea>" , $fixedqry, '</textarea>';
					break 2;
				}
			}
			echo '<p>Database should have been installed.</p>';
			echo '<a href="../">Visit your newly installed smCore site.</a>';
		} while (false);
	}
} while (false);

echo '
</body>
</html>';