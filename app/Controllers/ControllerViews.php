<?php

class ControllerViews extends StartController
{
    public function after()
    {
        $this->setСhunk('chunkAboutRightFirst','pViews/sideBarAbout');
    }

    public function actionIndex()
    {


        $this->data['title'] = 'ControllerViews actionIndex';
        $this->data['content'] = 'Views.';

        $this->show('main');
    }

    public function actionTest()
    {
        //$this->setСhunk('chunkAboutRightFirst','pControllers/chunkAboutRightFirst');

        $this->data['title'] = 'ControllerViews actionTest';
        $this->data['content'] = 'Краткая документация по используванию фреймворка.';

        $this->show('main');
    }

}
