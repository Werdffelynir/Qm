<?php

/**  *************************************************************************************
	Global system runer
*************************************************************************************  */


/**
 * Paths
 */
define('DS', DIRECTORY_SEPARATOR);
define('PATH_ROOT', dirname(__DIR__).DS);
define('PATH_SITE', __DIR__.DS);
define('PATH_APP', PATH_SITE . 'app'.DS);
define('PATH_SYS', PATH_ROOT.'system'.DS);
define('PATH_DATA', PATH_SITE . 'data'.DS);
define('PATH_PUBLIC', PATH_SITE . 'public'.DS);


/**
 * Configurations
 */
if(file_exists(PATH_APP.'config.php')){
	$config = include( PATH_APP.'config.php' );
    $config['path'] = __DIR__;
} else {
	die('<h1 style="color:#CC0000; text-align:center; margin-top: 100px">STOP! File "config.php" not found!<h1>');
}

/**
 * init bootstrap
 */
require_once( PATH_SYS.'bootstrap.php' );


/**
 * Environment
 */
define('DEBUG', $config['debug']);


// init application
$app = Core\App::getInstance();
$app->setConfig($config);

if(file_exists(PATH_APP.'bootstrap.php'))
    require_once( PATH_APP.'bootstrap.php' );

$app->init();