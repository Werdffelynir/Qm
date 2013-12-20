<?php

class ControllerModels extends BaseSiteController
{
    public function after()
    {
        $this->setСhunk('chunkAboutRightFirst','pModels/sideBarAbout');
    }

    public function actionIndex()
    {
        $this->data["pageTitle"] = "Qm - Модели";

        $ModelPages = $this->model("Pages");
        $getCurrent = $ModelPages->getPageByLink('models');

        $this->data['title'] = $getCurrent['title'];
        $this->data['content'] = htmlspecialchars_decode($getCurrent['content']);

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
