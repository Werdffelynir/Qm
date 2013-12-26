<?php
/**
 * MVC PHP Framework Quick Minimalism
 * File:    functions.php
 * Version: 0.2.0
 * Author:  OL Werdffelynir
 * Date:    07.12.13
 */
/* *****************************************************************************************
                                         SYSTEM CONST
   ************************************************************************************** */
/*
Classes
Controllers
Core
Extension
Helpers
Models
Structures
*/
/** Сепаратор, кратко */
define("DS", DIRECTORY_SEPARATOR);

/** Системные пути */
define("ROOT", dirname(__DIR__));
define("PATH", dirname(__DIR__).DS);
define("PATH_SYS", PATH.'system'.DS);
define("PATH_SYS_CLASSES", PATH_SYS.'Classes'.DS);
define("PATH_SYS_CONTROLLERS", PATH_SYS.'Controllers'.DS);
define("PATH_SYS_CORE", PATH_SYS.'Core'.DS);
define("PATH_SYS_EXTENSION", PATH_SYS.'Extension'.DS);
define("PATH_SYS_HELPERS", PATH_SYS.'Helpers'.DS);
define("PATH_SYS_MODELS", PATH_SYS.'Models'.DS);
define("PATH_SYS_STRUCTURES", PATH_SYS.'Structures'.DS);


/** Пути преложения */
$nameApp   = config("nameApp", "sys");
$nameTheme = config("nameTheme", "sys");
$baseUrl   = config("baseUrl", "app");
define("PATH_APP", PATH.$nameApp.DS);
define("PATH_APP_CLASSES", PATH_APP.'Classes'.DS);
define("PATH_APP_CONTROLLERS", PATH_APP.'Controllers'.DS);
define("PATH_APP_EXTENSION", PATH_APP.'Extension'.DS);
define("PATH_APP_HELPERS", PATH_APP.'Helpers'.DS);
define("PATH_APP_MODELS", PATH_APP.'Models'.DS);
define("PATH_APP_STRUCTURES", PATH_APP.'Structures'.DS);
define("PATH_APP_VIEWSTHEME", PATH_APP.'ViewsTheme'.DS);
define("PATH_APP_VIEWSPARTIALS", PATH_APP.'ViewsPartials'.DS);

//ViewsPartials
define("PATH_APP_THEME_DEFAULT", PATH_APP.$nameTheme.DS);

/** URL пути, */
define("URL", 'http://'.$baseUrl);
define("URL_THEME", 'http://'.$baseUrl.'/ViewsTheme/'.$nameTheme.'');

/** Системная константа, для проверки пренадлежности */
define("QM", 'QmSYSTEM');



var_dump(URL);


/* *****************************************************************************************
                                        SYSTEM FUNCTION
   ************************************************************************************** */

/**
 * Метод реализации доступа к конфигурации преложения.
 * @param $param ключ массива конфигурации
 * @param $part - APP | SYS | LOAD
 * @return array|bool|mixed
 */
function config($param=false, $part='APP'){

    $part = strtolower($part);

    if($part == 'app')
    {

        $fileApp = PATH_APP."configApplication.php";
        $fileSys = PATH_SYS."configApplication.php";

        if( file_exists($fileApp) AND file_exists($fileSys) )
        {
            $confApp = include $fileApp;
            $confSys = include $fileSys;

            $config = array_merge( $confApp, $confSys );

            if($param==false)
                return $config;
            elseif(array_key_exists($param, $config))
                return $config[$param];
            else return false;

        }else return false;

    }elseif($part == 'sys'){

        $file = PATH_SYS."configSystem.php";

        if( file_exists($file) )
        {
            $config = include $file;
            if($param==false)
                return $config;
            elseif(array_key_exists($param, $config))
                return $config[$param];
            else return false;

        }else{
            return false;
        }

    }elseif($part == 'load'){

        $fileApp = PATH_APP."configAutoload.php";
        $fileSys = PATH_SYS."configAutoload.php";

        if( file_exists($fileApp) AND file_exists($fileSys) )
        {
            $confApp = include $fileApp;
            $confSys = include $fileSys;

            $config = array_merge( $confApp, $confSys );

            if($param==false)
                return $config;
            elseif(array_key_exists($param, $config))
                return $config[$param];
            else return false;

        }else return false;


    }else{
        return false;
    }

}















