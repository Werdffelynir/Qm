<?php

class BaseAdminController extends Controller{

   // public function __construct(){
        // загрузка метода отображение елементов сайтбара по умолчаию
        //$this->start();
   // }

    public function before(){
        /** загрузка метода отображение елементов сайтбара и других повторяющехся
         * елементов по умолчаию */

    }


    public function init()
    {
        /** Назначение имени страницы по умолчанию */
        $this->data["pageTitle"] = "QM Framework";


        /** Резервирование Чанков*/
        $this->setChunk('topMenu','chunks/topMenu');
        $this->setChunk('sidebarFirst','chunks/sidebarAbout');
        $this->setChunk('sidebarSecond','chunks/sidebarQuickStart');


        /** Регистрация скриптов ************ */
        /** jquery-2.0.3
        $this->registerScript(array(
            'path'  => PATH_APP.'assets'.DS.'jquery'.DS.'jquery-2.0.3.min.js',
            'name'  =>'jquery'
        ));*/
        /** nicEdit.js
        $this->registerScript(array(
            'path'  => PATH_APP.'assets'.DS.'nicEdit'.DS.'nicEdit.js',
            'name'  =>'nicEdit'
        ));*/

        /** Тестовый CSS
        $this->registerStyle(array(
            'path'  => PATH_APP.'assets'.DS.'css'.DS.'test.css',
            'name'  =>'test'
        ));*/


        /** jquery-2.0.3*/
        $this->registerScript(array(
            'url'  => App::$urlTheme.'/js/jquery-1.7.0.js',
            'name'  =>'jquery'
        ));

        /** nicEdit.js*/
        $this->registerScript(array(
            'url'  =>  App::$urlTheme.'/js/nicEdit.js',
            'name'  =>'nicEdit'
        ));

        /** script.js*/
        $this->registerScript(array(
            'url'  =>  App::$urlTheme.'/js/script.js',
            'name'  =>'themeScript'
        ));


        /** Добавление скрипта к всем возможным страницам при услови наследования данного контролера. */
        $this->addScript('jquery');
        $this->addScript('themeScript');
        //$this->addStyle('themeScript');


        //var_dump($this->scripts);
        //var_dump(PATH_APP.'assets'.DS.'jquery'.DS.'jquery-2.0.3.min.js');


        /*
        $this->setVar('rightHello', array(
            '<h2 class="sidebartitle">Вывод из setVar</h2>
             <p>Привет друг как дела?</em></p>
             <p><a href="http://get-simple.info/download/">Скачай меня!</a></p>',
        ));

        $this->setVar('rightHelloArray', array(
            'ch_title'  => '<h2 class="sidebartitle">Вывод из setVar через массив</h2>',
            'ch_content'=> '<p>Я просто текст, тестовый текст.</em></p>
                            <p><a href="http://get-simple.info/download/">Не когда не скачуй это</a></p>',
        ));

        $this->setChunk('Top Chunk','controller/topChunk');

        $this->setChunk('firstChunk','controller/firstChunk', array(
            'chunkVar1'=>'Первая переменная',
            'chunkVar2'=>'Вторая переменная',
            'chunkVar3'=>'Третя переменная',
            'chunkVar4'=>array('test1','test1','test1'),
        ));
        */

    }



}