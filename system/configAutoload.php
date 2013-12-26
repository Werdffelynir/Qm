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

    /** ---------------------------------------------------------------------------
     *                            Системные настройки
     *--------------------------------------------------------------------------- */

    /* Подключаемые классы с директории app/classes/* Рекомендую автозогрузку держать
    отключенной autoload, при необходимосе делать исключения для автозагрузки
    параметром "exclusion" */
    "classesAutoload" => array(
        "autoload"  => false,
        "exclusion" => array(
            "QmCookie",
            "QmSession",
        ),
    ),

    /* Первичный Контролер для наследования созданых контролеров, запускаеться по умолчанию.
     * Назначение: инициализация общих параметров, видов, других настроек */
    "runStartController" => array(
        //"BaseSiteController",
        //"BaseAdminController",
    ),

    /* Включенные структурных частей приложения, подробно см. в док. */
    "structures"   => array(
        //"administrator",
        //"login",
    ),


);