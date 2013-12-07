<?php

class ControllerControllers extends StartController
{
    public function after()
    {
        $this->setСhunk('chunkAbout','pControllers/sideBarAbout');
    }

    public function actionIndex()
    {

        //$this->data['title'] = 'Controller Controllers actionIndex';
        $this->data['content'] = 'Краткая документация по используванию фреймворка.';

        $this->show('main');
    }

    public function actionTest()
    {
        //$this->setСhunk('chunkAbout','pControllers/chunkAbout');

        $this->data['title'] = 'Controller Controllers actionTest';
        $this->data['content'] = 'Краткая документация по используванию фреймворка.';

        $this->show('main');
    }




}
