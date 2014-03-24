<?php

/**
 * файл загружаеться посли обработок всех запросов
 */

// wpring constants
define('URL',\Core\App::$getURL['base']);
define('URL_PUBLIC',\Core\App::$getURL['public']);

/*
if(file_exists(PATH_APP.'Helpers/aliases.php'))
    include( PATH_APP.'Helpers/aliases.php' );
*/

/**
 * Функция добавляет CSS класс для активных меню по url адресу
 * @param $request
 */
function activeMenu($request)
{
    if ($request==\Core\App::$router['slug'])
        echo ' active ';
}
