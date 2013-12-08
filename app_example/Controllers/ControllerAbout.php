<?php

class ControllerAbout extends StartController
{
    public function after()
    {
        //$this->setСhunk('chunkAboutRightFirst','pModels/sideBarAbout');
    }

    public function actionIndex()
    {


        $this->data['title'] = 'ControllerModels actionIndex';
        $this->data['content'] = 'Краткая документация по используванию фреймворка.';

        $this->show('main');
    }

    public function actionTest()
    {
        //$this->setСhunk('chunkAboutRightFirst','pControllers/chunkAboutRightFirst');

        $this->data['title'] = 'ControllerModels actionTest';
        $this->data['content'] = 'Краткая документация по используванию фреймворка.';

        $this->show('main');
    }

}
