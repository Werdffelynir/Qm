<?php

/**
 * Точка вхождения
 *
 * Преложение: Qm PHP MVC framework
 * Версия: 0.2.2
 */

list($microtime, $sec) = explode(chr(32), microtime());
$timeLoader = $sec + $microtime;

ini_set("display_errors",1);
error_reporting(E_ALL);

/** Bases system functions */
include "system/functions.php";

/** Loader structure main classes */
include "system/bootstrap.php";

/** Run сore */
$app = new App();
$app->run();

