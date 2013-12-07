<?php

class ControllerContacts extends StartController
{

    public function actionIndex()
    {
        $re = App::$request;

        $this->data['title'] = 'Download actionIndex';
        $this->data['content'] = 'Download this. REQUEST: '.$re;

        $this->show('main');
    }

}
