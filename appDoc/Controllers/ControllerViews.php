<?php

class ControllerViews extends BaseSiteController
{
    public function after()
    {
        $this->setChunk('sidebarFirst','chunks/helpMessage');
    }

    public function actionIndex()
    {
        $this->data["pageTitle"] = "Qm - Представления";

        $ModelPages = $this->model("Pages");
        $getCurrent = $ModelPages->getPageByLink('views');

        $this->data['title'] = $getCurrent['title'];
        $this->data['content'] = htmlspecialchars_decode($getCurrent['content']);

        $this->show('main');
    }

    public function actionTest()
    {
        //$this->setChunk('chunkAboutRightFirst','pControllers/chunkAboutRightFirst');

        $this->data['title'] = 'ControllerViews actionTest';
        $this->data['content'] = 'Краткая документация по используванию фреймворка.';

        $this->show('main');
    }

}
