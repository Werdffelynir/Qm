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

/** Loader structure main classes */
include "system/bootstrap.php";
