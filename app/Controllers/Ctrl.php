<?php

class Ctrl extends Controller{

    public function __construct()
    {
        $this->start();
    }

    public function start()
    {

        $this->setVar('rightHello', array(
            '<h2 class="sidebartitle">Другой контроллер</h2>
             <p>Привет друг как дела?</em></p>
             <p><a href="http://get-simple.info/download/">Скачай меня!</a></p>',
        ));

        $this->setVar('rightHelloArray', array(
            'ch_title'  => '<h2 class="sidebartitle">Вывод из setVar через массив</h2>',
            'ch_content'=> '<p>Я просто текст, тестовый текст.</em></p>
                            <p><a href="http://get-simple.info/download/">Не когда не скачуй это</a></p>',
        ));

        $this->setСhunk('Top Chunk','controller/topChunk');

        $this->setСhunk('firstChunk','controller/firstChunk', array(
            'chunkVar1'=>'Первая переменная',
            'chunkVar2'=>'Вторая переменная',
            'chunkVar3'=>'Третя переменная',
            'chunkVar4'=>array('test1','test1','test1'),
        ));
    }
}