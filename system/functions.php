<?php
/*
 * MVC PHP Framework Quick Minimalism
 * File:    functions.php
 * Version: 0.2.0
 * Author:  OL Werdffelynir
 * Date:    07.12.13

   *****************************************************************************************

                                         SYSTEM CONST

   ************************************************************************************** */

/** Сепаратор, кратко */
define('DS', DIRECTORY_SEPARATOR);

/** Системная константа, для проверки пренадлежности */
define('QM', 'QmSYSTEM');

/** Системные пути */
define('ROOT', dirname(__DIR__));
define('PATH', ROOT.DS);

define('PATH_SYS', PATH.'system'.DS);
define('PATH_SYS_CLASSES', PATH_SYS.'Classes'.DS);
define('PATH_SYS_CONTROLLER', PATH_SYS.'Controller'.DS);
define('PATH_SYS_CORE', PATH_SYS.'Core'.DS);
define('PATH_SYS_EXTENSION',  PATH_SYS.'Extension'.DS);
define('PATH_SYS_HELPERS', PATH_SYS.'Helpers'.DS);
define('PATH_SYS_MODEL', PATH_SYS.'Model'.DS);

define('PATH_APP', PATH. QmConfSys('nameApp').DS);
define('PATH_APP_CLASSES', PATH_APP.'Classes'.DS);
define('PATH_APP_CONTROLLERS', PATH_APP.'Controllers'.DS);
define('PATH_APP_EXTENSION', PATH_APP.'Extension'.DS);
define('PATH_APP_HELPERS', PATH_APP.'Helpers'.DS);
define('PATH_APP_MODELS', PATH_APP.'Models'.DS);
define('PATH_APP_STRUCTURE', PATH_APP.'Structure'.DS);
define('PATH_APP_VIEWSTHEME', PATH_APP.'ViewsTheme'.DS);
define('PATH_APP_VIEWSPARTIALS', PATH_APP.'ViewsPartials'.DS);
define('PATH_APP_THEME', PATH_APP_VIEWSTHEME.QmConfApp('nameTheme').DS);


/** Загрузка базовых свойст конфигурации, если разрабатываеться сложное преложение то для улучшения производимости
 * можно установить необходимые свойста как в массив, получим глобальный доступ
$QmBaseConf = QmConf(
    array(
        'nameApp',
        'nameTheme',
        'baseUrl',
    ),
    array(
        'app',
        'sys',
    ));
*/


/* *****************************************************************************************

                            INCLUDES FILES & DIRECTORIES FUNCTIONS 

   ************************************************************************************** */
/**
 *
 *
 * @param $file
 * @param array $data
 * @param bool $e
 * @return bool
 */
function QmIncludeTheme($file, array $data=null, $e=false){

    if(file_exists($fileName = PATH_APP_THEME.$file.'.php')){

        if(!is_null($data))
            extract($data);

        if($e)
            echo $fileName;
        else
            include $fileName;
        
    }else{
        return false;
    }
}



/**
 * @param $className
 * @param bool $newObj
 * @return bool|string
 */
function QmIncludeClass($className, $newObj=false){
    if(file_exists(PATH_APP_CLASSES.$className.'.php')){
        include PATH_APP_CLASSES.$className.'.php';
        if( $newObj )
        return new $className.'()';
    }else{
        return false;
    }
}


/**
 * @param $helperName
 * @param bool $newObj
 * @return bool|string
 */
function QmIncludeHelper($helperName, $newObj=false){
    if(file_exists(PATH_APP_HELPERS.$helperName.'.php')){
        include PATH_APP_HELPERS.$helperName.'.php';
        if( $newObj )
        return new $helperName.'()';
    }else{
        return false;
    }
}



/**
 * @param $extensionName
 * @param bool $newObj
 * @return bool|string
 */
function QmIncludeExt($extensionName, $newObj=false){
    if(file_exists(PATH_APP_EXTENSION.$extensionName.'.php')){
        include PATH_APP_EXTENSION.$extensionName.'.php';
        if( $newObj )
        return new $extensionName.'()';
    }else{
        return false;
    }
}



/**
 * @param $fileName
 * @return string
 */
function QmIncludeOb($fileName){
    if(file_exists($fileName)){
        ob_start();
        include $fileName;
        $fileCont = ob_get_contents();
        ob_clean();
        return $fileCont;
    }else{
        return false;
    }
}




/* *****************************************************************************************

                                    GET CONFIGURATION FUNCTIONS 

   ************************************************************************************** */


/*
 * Функция доступа к данным конфигурационного файла.
 * Функция сначала подгружает конфигурационные файлы и сливает, их приоритет у
 * файлов преложения выше чем у файлов системных.
 *
 * Функция употребляет некоторое количество ресурсов, в примерах показано по
 * возрастающей, последний прием QmConf('__имя_сайства__') самый ресурср вотребляем.
 *
 * //__имя_сайства__     указано в конфиг файле типа "__тип__"
 * //__тип__             может быть "APP" или "SYS" или "LOAD" по умолчанию "all"

 * Пример использования, атребуты string - string:
    QmConf('__имя_сайства__',   '__тип__');

 * Пример использования, атребуты array - string:
    QmConf(
        array(
            '__имя_сайства__',
            '__имя_сайства__'
            ),
        '__тип__'
    );

 * Пример использования, атребуты array - array:
    QmConf(
        array(
            '__имя_сайства__',
            '__имя_сайства__'
        ),
        array(
            '__тип__',
            '__тип__'
        )
    );

 * Пример использования, атребут один string:
    QmConf('__имя_сайства__');

 *
 *
 * @param null $param
 * @param string $part
 * @return array|bool|mixed
 */

function QmConf($param = 'all', $part = 'all')
{
    static $confApp;
    static $confLoad;
    static $confSys;

    $file_app_app  = PATH_APP.'configApplication.php';
    $file_app_load = PATH_APP.'configAutoload.php';
    $file_sys_sys  = PATH_SYS.'configSystem.php';

    if(empty($confApp) /*AND file_exists($file_app_app)*/){
        //$confApp = include PATH_APP.'configApplication.php';
        $confApp = include $file_app_app;
    }
    if(empty($confLoad) /*AND file_exists($file_app_load)*/){
        //$confLoad = include PATH_APP.'configAutoload.php';
        $confLoad = include $file_app_load;
    }
    if(empty($confSys) /*AND file_exists($file_sys_sys)*/){
        //$confSys = include PATH_SYS.'configSystem.php';
        $confSys = include $file_sys_sys;
    }

	if( is_array($part) )
	{
		$config = array();
		$_config = array();
		foreach( $part as $keyPart )
		{
            $keyPart = strtolower($keyPart);
			if($keyPart=='sys' AND file_exists($file_sys_sys)){
				$_config = $confSys;
			}elseif($keyPart=='app' AND file_exists($file_app_app)){
                $_config = $confApp;
			}elseif($keyPart=='load' AND file_exists($file_app_load)){
                $_config = $confLoad;
			}
            $config = array_merge($config, $_config);
		}

		if( $param == 'all' ){
        	return $config;
        }elseif( is_array($param) ){
            $_config = array();
            foreach( $param as $keyParam ){
            	if(array_key_exists($keyParam, $config)){
	            	$_config[$keyParam] = $config[$keyParam];
	            }
            }
            return $_config;
        }elseif( is_string($param) AND array_key_exists($param, $config) ){ 
        	return $config[$param];
        }else{
        	return false; 
        }
		
	}
	elseif( is_string($part) )
	{
		$part = strtolower($part);
		if($part=='sys' AND file_exists($file_sys_sys)){
			$config = $confSys;
		}elseif($part=='app' AND file_exists($file_app_app)){
            $config = $confApp;
		}elseif($part=='load' AND file_exists($file_app_load)){
            $config = $confLoad;
		}elseif($part=='all'){
            $confAA = $confApp;
            $confAL = $confLoad;
            $confSS = $confSys;
            $config = array_merge($confAA, $confAL, $confSS);
		}
		
		if( $param == "all" ){
        	return $config;
        }elseif( is_array($param) ){
            $_config = array();
            foreach( $param as $keyParam ){
            	if(array_key_exists($keyParam, $config)){
	            	$_config[$keyParam] = $config[$keyParam];
	            }
            }
            return $_config;
        }elseif( is_string($param) AND array_key_exists($param, $config) ){ 
        	return $config[$param];
        }else{
        	return false; 
        }
	}
}


/*
 * Облегченная функция доступу к данным конфигурационного файла 'configApplication.php',
 * немного быстрее чем QmConf()

 * Пример использования, атребут один string:
    QmConfApp('__имя_сайства__');
 *
 * @param $param
 * @return bool
 */
function QmConfApp($param)
{
    static $confApp;
    
    if( file_exists(PATH_APP.'configApplication.php') )
    {
        if(empty($confApp)){
            $confApp = include PATH_APP.'configApplication.php';
        }
        $config = $confApp;
	    
	    if( array_key_exists($param, $config) ){ 
	    	return $config[$param];
	    }else{
	    	return false; 
	    }
    }
}

/*
 * Облегченная функция доступу к данным конфигурационного файла 'configAutoload.php',
 * немного быстрее чем QmConf()

 * Пример использования, атребут один string:
    QmConfLoad('__имя_сайства__');

 * @param $param
 * @return bool
 */
function QmConfLoad($param)
{
    static $confLoad;
    
    if(file_exists(PATH_APP.'configAutoload.php') )
    {
        if(empty($confLoad)){
            $confLoad = include PATH_APP.'configAutoload.php';
        }
        $config = $confLoad;
	    
	    if( array_key_exists($param, $config) ){ 
	    	return $config[$param];
	    }else{
	    	return false; 
	    }
    }
    
}

/**
 * Облегченная функция доступу к данным конфигурационного файла 'configSystem.php',
 * немного быстрее чем QmConf()

 * Пример использования, атребут один string:
    QmConfSys('__имя_сайства__');

 * @param $param
 * @return bool
 */
function QmConfSys($param)
{
    static $confSys;
	
	if(file_exists(PATH_SYS.'configSystem.php') )
    {
        if(empty($confSys)){
            $confSys = include PATH_SYS.'configSystem.php';
        }
        $config = $confSys;

		if( array_key_exists($param, $config) ){ 
	    	return $config[$param];
	    }else{
	    	return false; 
	    }
	}
}



/* *****************************************************************************************

                                    SHOW ERROR FUNCTIONS 

   ************************************************************************************** */
/** Функции вывода ошибок при включеном моде дебага */
function errorFileExists($file = null)
{
    //$titleError = (!is_null($titleError)) ? $titleError : '';
    $file = (!is_null($file)) ? $file : '';
    $appMode = QmConf('appMode');
    if($appMode == 'debug')
    {
        print(errorStyle('Файл не найден ',' [ <b>'.$file.'</b> ] не существует!'));
        exit;
    }
}
/** Вызывать сообщение о ошибке */
function error($titleError, $text = null)
{
    if(QmConf('appMode') == 'debug')
    {
        print(errorStyle('Вызвана ошибка '.$titleError, $text));
        exit;
    }
}
/** Вызывать сообщение о ошибке */
function QmError($titleError, $text = null)
{
    if(QmConf('appMode') == 'debug')
    {
        print(errorStyle('Вызвана ошибка '.$titleError, $text));
        exit;
    }
}
/** Общий стиль для вывода ошибок */
function errorStyle($titleError = null, $text)
{
    return '
    <html>
    <head>
    <meta charset="utf-8">
    <style type="text/css">
    body{
        background: #3C3F41;
    }
    </style>
    </head>
        <body>


    <div style="background: #2B2B2B; color: #9C9C9C; font-family: consolas, courier new; padding: 10px 20px; ">
        <h2>РЕЖИМ ОТЛАДКИ: '.$titleError.'</h2>


        <code style="background: #3C3F41; color: #CC7832; padding: 5px 3px; margin: 0 auto 25px; display: block;">
            '.$text.'
        </code>

        <hr style=" height: 1px; border:none; background: #f7002b;">
        <small>Qm Framework version 0.1. DeBug Mode ON. </small>
    </div>

        </body>
    </html>
    ';
}


if (!function_exists('isExists')) {
    /**
     * verification => ifExists , $function='isset-empty'
     * Метод проверки данных на существование или дугой тип
     *
     * @param string $value Данные что проверяем
     * @return null
     * @throws Exception
     */
    function isExists($value)
    {
        return (isset($value) AND !empty($value)) ? $value : null;
    }
}

if (!function_exists('isEcho')) {
    /**
     * Метод проверки данных на существование или дугой тип
     *
     * @param string    $value  Данные для вывода
     * @param bool      $e
     * @return null
     */
    function isEcho($value, $e=true)
    {
        if($e)
            echo (isset($value)) ? $value : null;
        else
            return (isset($value)) ? $value : null;
    }
}