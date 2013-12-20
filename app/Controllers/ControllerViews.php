<?php

class ControllerViews extends BaseSiteController
{
    public function after()
    {
        $this->setСhunk('chunkAboutRightFirst','pViews/sideBarAbout');
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
        //$this->setСhunk('chunkAboutRightFirst','pControllers/chunkAboutRightFirst');

        $this->data['title'] = 'ControllerViews actionTest';
        $this->data['content'] = 'Краткая документация по используванию фреймворка.';

        $this->show('main');
    }

}
