<?php
/**
 * Файл был сгенерирован с помощю Gen Qm Framework
 *
 * Необходимо провести реорганизацию кода даного контролера
 */

class BaseController extends Controller{

    /**
     * Метод запускаеться перед инициализацией контролера и внутриних методов системы с вязвных и ним
     * Контролеры что наследуют BaseController будут иметь установленые параметы, что-бы переопределить
     * например чанк на других страницах, в контролерах можно использывать метода after() в котором нужно
     * переопределить данные для чанка.
     */
    public function init()
    {
        /**
         * Назначение имени страницы по умолчанию
         *
         * Теперь в виде можно выводить заголовок $this->data("pageTitle");
         * и переписать его в любом методе других контролеров для изминения названия
         */
        $this->setData("pageTitle", "QM Framework");


        /**
         * Назначение Чанков. Чанк использует чати вида для вывода в общий вид
         * методом
         * $this->chunk('copyright');
         */
        $this->setChunk('topmenu','chunks/topmenu');
        $this->setChunk('copyright','chunks/copyright');
        $this->setChunk('leftmenu','sidebars/leftmenu');
        $this->setChunk('about','sidebars/about');


        /**
         * Забинденая переменная в методе дает возможность также выводить
         * некоторые данные в вид. Доступ $this->var['myVar'];
         */
        $this->setVar('myVar',array(
            '<h2>Вывод из setVar</h2>
             <p>Привет друг как дела?</em></p>
             <p><a href="#">Скачай меня!</a></p>',
        ));


        /** Регистрация скриптов */
        $this->registerScript(array(
            'url'   => App::$urlTheme.'/js/jquery-2.0.3.min.js',
            'showIn'=> 'header',
            'name'  => 'jquery'
        ));


        /** Регистрация стилей */
        $this->registerStyle(array(
            'url'  => App::$urlTheme.'/css/mycss.css',
            'name'  => 'myCss'
        ));


        /**
         * Добавление скрипта и стилей к всем возможным страницам при услови наследования данного контролера.
         * Используйте $this->addScript('jquery','disabled'); для отмены вывода скрипта
         * Скрипты и стили будут выводится в виде в местеах
         * $this->showScripts('header');
         * $this->showStyles();
         * $this->showScripts('footer');
         */
        $this->addScript('jquery');
        $this->addStyle('myCss');



    }

}
