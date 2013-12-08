<?php


/** Сепаратор, кратко */
define("DS", DIRECTORY_SEPARATOR);

/** Корень, todo: Убрать PATH_ROOT, переписать ядро на PATH */
define("PATH", dirname(__DIR__).DS);
define("PATH_ROOT", dirname(__DIR__));

/** Пути в Системных каталогах */
define("PATH_LIB", PATH.'lib'.DS);
define("PATH_LIB_ABSTRACTS", PATH.'lib'.DS.'Abstracts'.DS);
define("PATH_LIB_CLASSES", PATH.'lib'.DS.'Classes'.DS);
define("PATH_LIB_CORE", PATH.'lib'.DS.'Core'.DS);

/** Пути в преложении */
define("APP", QmConf('pathApp'));
define("PATH_APP", PATH.APP.DS);
define("PATH_APP_CLASS", PATH.APP.DS.'Classes'.DS);
define("PATH_APP_CONTROLLERS", PATH.APP.DS.'Controllers'.DS);
define("PATH_APP_VIEWS", PATH.APP.DS.'Views'.DS);

/** Пути в шаблоне */
define("PATH_THEME", PATH.'theme'.DS.QmConf('defaultTheme').DS);

/** URL пути, */
define("URL", 'http://'.QmConf('baseUrl'));
define("URL_THEME", 'http://'.QmConf('baseUrl').'/theme/'.QmConf('defaultTheme').'');

/** Системная константа, для проверки пренадлежности */
define("QM", 'QmSYSTEM');



/**
 *
 *
 * @param $file
 * @param array $data
 * @param bool $e
 * @return bool
 */
function QmIncludeTheme($file, array $data=null, $e=false){

    if(file_exists($fileName = PATH_THEME.$file.'.php')){

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
 * @param bool $exp
 * @return bool
 */
function QmIncludeClass($className, $exp=true){
    $exp = ($exp)? '.php' : '';
    if(file_exists(PATH_APP_CLASS.$className.$exp)){
        include PATH_APP_CLASS.$className.$exp;
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


/**
 * Метод реализации доступа к конфигурации преложения.
 *
 * @param $param string ключ массива конфигурации
 * @param $sys bool
 * @return bool
 */
function QmConf($param=null, $sys=false){

    if(file_exists(PATH_LIB."configuration.php")){

        if($sys) {
            $config = include PATH_LIB."configuration.php";
        } else {
            $config = include PATH_LIB."configuration.php";

            if(file_exists(PATH_APP."configuration.php")) {
                $configApp = include PATH_APP."configuration.php";
                $config = array_merge($config, $configApp);
            }
        }

        if($param==null)
            return $config;

        if(array_key_exists($param, $config)){
            return $config[$param];
        }else{
                return false;
        }
    }else{
        return false;
    }
}


/** Использование класса через фу.
function QmRout($param){
    $Rout = new Router(PATH_ROOT);
    $Rout->run();
    return $Rout->$param;
}
*/



/** Функции вывода ошибок при включеном моде дебага */
function errorFileExists($file = null)
{
    $titleError = (!is_null($titleError)) ? $titleError : '';
    $file = (!is_null($file)) ? $file : '';
    $appMode = QmConf('appMode');
    if($appMode == "debug")
    {
        print(errorStyle('Файл не найден ',' [ <b>'.$file.'</b> ] не существует!'));
        exit;
    }
}
/** Вызывать сообщение о ошибке */
function error($titleError, $text = null)
{
    if(QmConf('appMode') == "debug")
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


