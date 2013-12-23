<?php

/**
 * Файл был сгенерирован с помощю Gen Qm Framework
 * Класс контролера определяет всю логику преложения, все запросы перенаправляються
 * в класс по его имени "class ControllerMy" (http://site.com/my) отрабатывает метод actionIndex
 * или http://site.com/my/contact указывает на actionContact
 * вам необходимо провести реорганизацию кода даного контролера
 */

class Controller[[CONTROLLERNAME]] extends [[CONTROLLEREXTENDS]]
{

    /** Розпаковывать массивы в виде */
    protected $extracted = false;


    /** Конструктор задает последовательность загрузки методов */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Метод отрабатываеться до загрузки системы и всех классов.
     * Предназначин для описания регистрации скриптов, стилей, частей шаблона (сайтбары прочее).
     * В методе не нужно описывать функционал не входящий в загрузки по умолчанию.
     */
    public function before()
    {
        #Code...
    }


    /**
     * Метод отрабатываеться после загрузки системы и всех классов
     * Предназначин для описания перезаписи автозагрузок или рание обявленных значений
     * таких как перехват загруженных классов, созданых сайдбаров итд
     */
    public function after()
    {
        /** Определяет основной файл в шаблоне, по умолчанию (theme/__active_theme__/views/main.php) */
        #$this->layout = "otherMainLayout";

        /** Определяет основной файл вида, по умолчанию (__active_app__/views/main.php) */
        #$this->view   = "otherMainView";
        #Code...
    }


    /** Внешний метод. Отрабатываеться по умолчанию */
    public function actionIndex()
    {
        #Code...
        #$this->show();
    }


    /** Внутрений метод */
    public function myMethod()
    {
        #Code...
    }

}