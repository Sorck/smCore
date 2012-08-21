<?php
/*
 * installs the database if you have setup your settings.php file correctly
 */

namespace smCore;

// do the settings exist?
if(!file_exists('settings.php'))
    die('Please create your settings.php file in '.dirname(dirname(__FILE__)).' and configure it based upon '.dirname(dirname(__FILE__)).'/other/settings.php');
// are the settings changed from defaults?
require('settings.php');
if(Settings::URL == 'http://www.youdidntchangeyoursettingsfile.lol')
    die('You have not correctly set your URL in '.dirname(dirname(__FILE__)).'/settings.php');
if(Settings::$database['user'] === '')
    die('You have not correctly set your MySQL username in '.dirname(dirname(__FILE__)).'/settings.php');
// now try and connect
$con = mysql_connect(Settings::$database['host'], Settings::$database['user'], Settings::$database['password']);
if(!$con)
    die('MySQL connection failed. Please check your settings in '.dirname(dirname(__FILE__)).'/settings.php');
if(isset($_GET['install'])) {
    $qry = file_get_contents('database.sql');
    $fixedqry = str_replace('{db_prefix}', Settings::$database['dbname'].'`.`'.Settings::$database['prefix'], $qry);
    echo "# below is your query ready to run in PHPMyAdmin :-)\n" , $fixedqry;
    //echo mysql_error() or 'Should be installed now...';
    exit;
}

echo 'Are you sure you wish to install to your database?<br /><a href="install.php?install">YES, INSTALL THE DB TABLES</a>';