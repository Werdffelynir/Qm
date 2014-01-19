<?php
/**
 * Файл был сгенерирован с помощю Gen Qm Framework
 *
 * MVC PHP Framework Quick Minimalism
 * File:    configAutoload.php
 * Version: 0.2.2
 * Author:  OL Werdffelynir
 * Date:    07.12.13
 *
 * Конфигурация автозагрузи
 *
 * ЕСЛИ ВЫ НЕ УВЕРЕНЫ В ТОМ ЧТО ИЗМЕНЯЕТЕ, ЛУЧШЕ ЭТОГО НЕ ДЕЛАТЬ.
 */
return array(


    /** ----------------------- Загрузка дополнительных классов
     *--------------------------------------------------------------------------- */

    /** Подключаемые классы с директории app/Classes/* Рекомендую автозогрузку держать
    отключенной "autoload"  => false, при необходимосе делать исключения для автозагрузки
    параметром "classes". Для разовой загрузки использывать метод: App::loadClasses($fileName, $newObj=true)  */
    "classesAutoload" => array(
        "autoload"  => false,
        "classes" => array(
            "QmClasses",
        ),
    ),

    /** Подключаемые хелперы с директории app/Helpers/* Рекомендую автозогрузку держать
    отключенной "autoload"  => false, при необходимосе делать исключения для автозагрузки
    параметром "classes". Для разовой загрузки использывать метод: App::loadHelper($fileName, $newObj=true) */
    "helpersAutoload" => array(
        "autoload"  => false,
        "classes" => array(
            "QmHelpers",
        ),
    ),

    /** Подключаемые расширения с директории app/Extension/*
     * Для автоматической одгрузки используйте синтаксис:
     * "_Директория_"=>"_подключаемый_файл_.php" например "phpMailer"=>"class.phpmailer.php",
     * Для разовой загрузки использывать метод: App::loadExtension($extPath)  */
    "extensionAutoload" => array(
        "classes" => array(
            //"phpMailer"=>"class.phpmailer.php",
        ),
    ),

    /** Первичный Контролер для наследования созданых контролеров, запускаеться по умолчанию.
     * Назначение: инициализация общих параметров, видов, других настроек */
    "startControllerAutoload" => array(
        "BaseController",
    ),

    /** Включенные структурных частей приложения, подробно см. в док.*/
    "structureAutoload" => array(
        "structure" => array(
            "administrator",
        ),
    ),

);