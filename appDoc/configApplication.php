<?php
/**
 * MVC PHP Framework Quick Minimalism
 * File:    configuration.php
 * Version: 0.2.0
 * Author:  OL Werdffelynir
 * Date:    07.12.13
 *
 * Конфигурация фреймворка.
 * Массив загружаеться вторым после одноименного файла в директории прелоджения полностю повторяя эту
 * структуру, но конфигурация преложения вмещает только основные параметры приложения,
 * Массивы накладуються друг на друга таким образом что конфигурация в преложении имеет выший приоритет.
 *
 * НЕ РЕКОМЕНДОВАНО изминять конфигурацию в этом файле, повторите настройку в файле преложения, условие
 * одно соответствия ключа.
 */
return array(


    /** ----------------------- Базовые настройки
     *--------------------------------------------------------------------------- */

    /* Имя приложения */
    "appTitle" =>"Qm Application",

    /* Имя приложения */
    "appCopy" =>"&copy; OL Werdffelynir 2013/ Powered by Quick Minimalism MVC PHP Framework",

    /* Базовый URL адрес приложения, основная настройка для нахождения путей всей системы
     *
     * Но же корневая директрория преложения (от входного файла index.php), обычно это
     * ".../www/my-site.com/index.php" => ("baseUrl" =>"my-site.com",)
     * или если в под каталогах
     * ".../www/my-site.com/test/qm_framework/index.php" => ("baseUrl" =>"my-site.com/test/qm_framework",). */
    "baseUrl" =>"qm.v0.2.2.021.loc",

    /* Каталог активного Шаблона */
    "nameTheme" => "default",


    /** ----------------------- Производительность
     *--------------------------------------------------------------------------- */
    /* Настройка роутингов методов контролеров преложения */
    "routeReName" => array(
        "home" => "index/index",
        "doc" => "index/doc",
        "controllers" => "index/controllers",
        "models" => "index/models",
        "views" => "index/views",
        "download" => "index/download",
        "admin" => "index/edit",
    ),
    /*
    "routeReName" => array(
        array("actionRouteTestOne", "reTestOne"),
        array("actionRouteTestTwo", "reTestTwo"),
    ),
     */
    /*
    "routeRegExr" => array(
        "home" => "index/index",
        "doc" => "index/doc",
        "controllers" => "index/controllers",
        "models" => "index/models",
        "views" => "index/views",
        "download" => "index/downloads",
        "edit" => "index/edit",
    ),
     */

    /* Работа приложение в режиме debug или production*/
    "appMode" => "debug",

    /* Безопасные URL строки запроса */
    "safeUrl"   => true,


    /** ----------------------- Соединение с базой данных
     *--------------------------------------------------------------------------- */

    /* Настройка обявляет автозагрузку класса, только первого в списке если их несколько,
    свойство с екземпляром PDO создаеться при обращении к модели.
    Если настройка установленная в FALSE, то все екземпляы PDO необходимо создавать вручную. */
    "dbAutoConnect" => true,

    /* Настройки подключений к БД*/
	"dbConnects" => array(

	    /* Настройки подключения к базе данных. через PDO SQLite */
	    "db" => array(
	        "driver"  => "sqlite",
	        "class"   => "SimplePDO",
	        "path"    => dirname(__DIR__)."/appDoc/DataBase/QmDataBase.sqlite",
	    ),
	    /*
	    "dbTwo" => array(
	        "driver"  => "sqlite",
	        "class"   => "SimplePDO",
	        "path"    => dirname(__DIR__)."/app/DataBase/QmDataBaseTwo.sqlite",
	    ),*/
        /* Настройки подключения к базе данных. через PDO MySQL
        "dbMySql" => array(
            "driver"    => "mysql",
            "class"     => "SimplePDO",
            "host"      => "localhost",
            "dbname"    => "yiidb",
            "user"      => "root",
            "password"  => "",
        ),*/
	),

	
);