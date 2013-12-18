<?php

class ControllerControllers extends BaseSiteController
{
    public function after()
    {
        $this->setСhunk('chunkAboutRightFirst','pControllers/sideBarAbout');
    }

    public function actionIndex()
    {

        //$this->data['title'] = 'Controller Controllers actionIndex';
        $this->data['content'] = 'Краткая документация по используванию фреймворка.';

        $this->show('main');
    }

    public function actionTest()
    {
        //$this->setСhunk('chunkAboutRightFirst','pControllers/chunkAboutRightFirst');

        $this->data['title'] = 'Controller Controllers actionTest';
        $this->data['content'] = 'Краткая документация по используванию фреймворка.';

        $this->show('main');
    }




}
