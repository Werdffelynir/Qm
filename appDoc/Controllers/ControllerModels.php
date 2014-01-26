<?php

class ControllerModels extends BaseSiteController
{
    public function after()
    {
        $this->setChunk('sidebarFirst','chunks/helpMessage');
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
        //$this->setChunk('chunkAboutRightFirst','pControllers/chunkAboutRightFirst');

        $this->data['title'] = 'ControllerModels actionTest';
        $this->data['content'] = 'Краткая документация по используванию фреймворка.';

        $this->show('main');
    }

}
