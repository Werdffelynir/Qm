<?php

class BaseSiteController extends Controller{

   // public function __construct(){
        // загрузка метода отображение елементов сайтбара по умолчаию
        //$this->start();
   // }

    /** public function before(){
         загрузка метода отображение елементов сайтбара и других повторяющехся
         * елементов по умолчаию
        $this->start();
    } */


    public function init()
    {
        /** Назначение имени страницы по умолчанию*/
        $this->data["pageTitle"] = App::$config["appTitle"];


        /** Назначение Чанков */
        $this->setChunk('copyright','chunks/copyright');
        $this->setChunk('topMenu','chunks/topMenu');
        $this->setChunk('about','sidebars/about.chunk');
        $this->setChunk('start','sidebars/start.chunk');
        $this->setChunk('menuLearn','sidebars/menuLearn.chunk');
        //$this->setChunk('sidebar/chunkAbout','sidebar/chunkAbout');
        //$this->setChunk('sidebar/chunkStart','sidebar/chunkStart');
/*
        $this->setVar('v-01', array(
            '<h2 class="sidebartitle">Вывод из setVar</h2>
             <p>Привет друг как дела?</em></p>
             <p><a href="http://get-simple.info/download/">Скачай меня!</a></p>',
        ));


        $this->setData('d-01', 'set data string');
        $this->setData('d-02', array(
            'title'=>'Title set data array',
            'content'=>'Content set data array',
        ));
        $this->setData('d-03', array(
            'title'=>'Title d-03 set data array',
            'content'=> array(
                "text" => 'Content -> text',
                "category" => 'Content -> category',
            ),
        ));
        $this->setData('d-04', array(
            'title'=>'Title d-04 set data array',
            'content'=> array(
                "text" => 'Content -> text',
                "category" => array(
                    "news" => 'Content -> category -> news',
                    "other" => 'Content -> category -> other',
                ),
            ),
        ));
*/









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


        /** jquery-2.0.3
        $this->registerScript(array(
            'path'  => PATH_THEME.'js'.DS.'jquery-1.7.0.js',
            'name'  =>'jquery'
        ));*/

        /** nicEdit.js
        $this->registerScript(array(
            'path'  => PATH_THEME.'js'.DS.'nicEdit.js',
            'name'  =>'nicEdit'
        ));*/

        /** script.js
        $this->registerScript(array(
            'path'  => PATH_THEME.'js'.DS.'script.js',
            'name'  =>'themeScript'
        )); */


        /** Добавление скрипта к всем возможным страницам при услови наследования данного контролера. */
        //$this->addScript('jquery');
        //$this->addStyle('test');


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