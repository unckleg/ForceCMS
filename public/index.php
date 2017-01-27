<?php

define('APPLICATION_ENV', 'development');

define('DS', DIRECTORY_SEPARATOR);

define('ZEND_LIBRARY_PATH', realpath('../lib'));

define('APPLICATION_PATH', realpath('../app' ));

define('APP_LIBRARY_PATH', APPLICATION_PATH . '../lib/ForceCMS');

define('APP_PUBLIC', realpath(''));

$paths = [
    ZEND_LIBRARY_PATH,
    APP_LIBRARY_PATH,
    realpath( __DIR__ . '/../app'),
    get_include_path()
];

set_include_path(implode(PATH_SEPARATOR, $paths));

require('ForceX/Application.php');

$application = new \ForceX\Application(APPLICATION_ENV, APPLICATION_PATH . '/Config/application.ini');
$application->bootstrap()->run();