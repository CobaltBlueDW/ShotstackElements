<?php

// setup program constants
define('BASE_PATH', str_replace("/src/lib/framework/../../../", "/", rtrim(preg_replace('#[/\\\\]{1,}#', '/', __DIR__.'/../../../'), '/') . '/'));


// setup autoloaders
require __DIR__ . '/autoload.php';


// setup Global Config
require  __DIR__ . '/settings/setup.php';


// setup Dependency Injection
require  __DIR__ . '/di/setup.php';
