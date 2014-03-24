<?php

if(DEBUG){
    error_reporting(E_ALL | E_STRICT);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}


// Check PHP version
if (version_compare(phpversion(), '5.4.3', '<')) {
    //printf("PHP 5.4.3 is required, you have %s\n", phpversion());
    //exit();
}

// The page cannot be displayed in a frame
header('X-Frame-Options: SAMEORIGIN');

// Hide php version
header('X-Powered-By: backend');

/** 
 * Include helper base function file.
 */
if(file_exists(PATH_SYS.'functions.php'))
	include( PATH_SYS.'functions.php' );



timerStart();


/** **************************************************************************************************
    Autoload aplication classes
************************************************************************************************** **/
function __autoload($className)
{

//var_dump('add:'.$className);echo '<br>';

// System  autolads classes files
    if( strpos($className,'Core\\') >    -1 OR
        strpos($className,'Library\\') > -1 ){
        $className = str_replace('\\','/',$className);
        //var_dump( 'intoSYS'.$className );echo '<br>';
        //var_dump( PATH_SYS . $className . '.php' );echo '<br>';

        include(PATH_SYS . $className . '.php');
    }

// App autolads classes files
    if( strpos($className,'Classes\\')     > -1 OR 
        strpos($className,'Models\\')      > -1 OR
        strpos($className,'Helpers\\')     > -1 OR
        strpos($className,'Components\\')  > -1 OR
        strpos($className,'Controllers\\') > -1 OR
        strpos($className,'Extensions\\')  > -1 ){
        $className = str_replace('\\','/',$className);
        //var_dump( 'intoAPP'.$className );echo '<br>';
        //var_dump( PATH_APP . $className . '.php' );echo '<br>';
        
        include(PATH_APP . $className . '.php');
    }
    
}
