<?php
/**
 * MVC PHP Framework Quick Minimalism
 * File:    bootstrap.php
 * Version: 0.2.2.022
 * Author:  OL Werdffelynir
 * Date:    07.12.13
 */


/** Bases system functions */
include "functions.php";


/**
 * Автозагрузка классов преложения и системы
 * Авто загрузка происходит по установленым настройкам конфигурации
 *
 * SysHelpers
 * SysClasses
 * SysController
 * SysModel
 * AppHelpers
 * AppClasses
 * AppControllers (start controllers)
 *
 * И расширений:
 * SysExtensions
 * AppExtensions
 *
 * @param $className
 */
function __autoload($className){

    $configAutoload = QmConf('all', array('load','sys'));

// SysHelpers
    if($configAutoload["helpersSysAutoload"]["autoload"] == true){
        include(PATH_SYS_HELPERS.$className.'.php');
    }elseif(!empty($configAutoload["helpersSysAutoload"]["classes"])){
        foreach($configAutoload["helpersSysAutoload"]["classes"] as $loadClasses){
            if($className == $loadClasses){
                include(PATH_SYS_HELPERS.$loadClasses.'.php');
            }
        }
    }

// SysClasses
    if($configAutoload["classesSysAutoload"]["autoload"] == true){
        include(PATH_SYS_CLASSES.$className.'.php');
    }elseif(!empty($configAutoload["classesSysAutoload"]["classes"])){
        foreach($configAutoload["classesSysAutoload"]["classes"] as $loadClasses){
            if($className == $loadClasses){
                include(PATH_SYS_CLASSES.$loadClasses.'.php');
            }
        }
    }

// SysController
    if (file_exists(PATH_SYS_CONTROLLER.$className.'.php')){
        include (PATH_SYS_CONTROLLER.$className.'.php');
    }

// SysModel
    if (file_exists(PATH_SYS_MODEL.$className.'.php')){
        include (PATH_SYS_MODEL.$className.'.php');
    }

// AppHelpers
    if($configAutoload["helpersAutoload"]["autoload"] == true){
        include(PATH_APP_HELPERS.$className.'.php');
    }elseif(!empty($configAutoload["helpersAutoload"]["classes"])){
        foreach($configAutoload["helpersAutoload"]["classes"] as $loadClasses){
            if($className == $loadClasses){
                include(PATH_APP_HELPERS.$loadClasses.'.php');
            }
        }
    }

// AppClasses
    if($configAutoload["classesAutoload"]["autoload"] == true){
        include(PATH_APP_CLASSES.$className.'.php');
    }elseif(!empty($configAutoload["classesAutoload"]["classes"])){
        foreach($configAutoload["classesAutoload"]["classes"] as $loadClasses){
            if($className == $loadClasses){
                include(PATH_APP_CLASSES.$loadClasses.'.php');
            }
        }
    }

// AppControllers (start controllers)
    if( !empty($configAutoload["startControllerAutoload"]) ) {
        foreach($configAutoload["startControllerAutoload"] as $loadClasses){
            if($className == $loadClasses){
                include(PATH_APP_CONTROLLERS.$loadClasses.'.php');
            }
        }
    }

// SysExtensions
    if( !empty($configAutoload["extensionSysAutoload"]["classes"]) ){
        foreach($configAutoload["extensionSysAutoload"]["classes"] as $keyExt => $valExt ){
            if(file_exists(PATH_SYS_EXTENSION.$keyExt.DS.$valExt)){
                include PATH_SYS_EXTENSION.$keyExt.DS.$valExt;
            }
        }
    }

// AppExtensions
    if( !empty($configAutoload["extensionAutoload"]["classes"]) ){
        foreach($configAutoload["extensionAutoload"]["classes"] as $keyExt => $valExt ){
            if(file_exists(PATH_APP_EXTENSION.$keyExt.DS.$valExt)){
                include PATH_APP_EXTENSION.$keyExt.DS.$valExt;
            }
        }
    }

}


/** Hard-Core system */
include "Core/App.php";

/** Run сore */
$app = new App();

/** Подключение внутрених файлов Преложения */
if(file_exists(PATH_APP."functions.php"))
    include PATH_APP."functions.php";
if(file_exists(PATH_APP."bootstrap.php"))
    include PATH_APP."bootstrap.php";

$app->run();