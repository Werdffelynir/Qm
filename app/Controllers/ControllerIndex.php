<?php

class ControllerIndex extends StartController
{
    /* Разрешить распаковывать переданные данные в вид */
    //protected $extracted = true;

    //public function after(){}

    public function actionIndex()
    {
        $this->data['title'] = 'Быстрый, простой MVC PHP Framework.';
        $this->data['content'] = $this->partial('pHome/partialContent');

        $this->show('main');
    }

    public function actionLogin()
    {
        $this->data['title'] = 'Login';
        $this->data['content'] = $this->partial('pHome/partialLogin');



        $this->show('main');
    }

    public function actionTest()
    {
        echo  'ZZZZZZZZZZZZZZZZZZZZZZZZZZZ actionTest ZZZZZZZZZZZZZZZZZZZZZZZZZZZ';
        //$this->data['content'] = $this->partial('pHome/partialLogin');

        //$this->show('main');
    }


}





