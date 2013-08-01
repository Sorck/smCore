<?php

ini_set('display_errors', 1);

// Load the composer autoloader
require_once 'library/autoload.php';

// Register the autoloader
include_once __DIR__ . '/library/smCore/Autoloader.php';
new smCore\Autoloader(null, __DIR__ . '/library');

// Load the settings file and create the settings object
include_once __DIR__ . '/settings.php';
$settings = new Settings();

$application = new smCore\Application($settings);
$application->run();