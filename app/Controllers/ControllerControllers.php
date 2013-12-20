<?php

class ControllerControllers extends BaseSiteController
{
    public function after()
    {
        $this->setСhunk('chunkAboutRightFirst','pControllers/sideBarAbout');
    }

    public function actionIndex()
    {
        $this->data["pageTitle"] = "Qm - Контролеры.";

        $ModelPages = $this->model("Pages");
        $getCurrent = $ModelPages->getPageByLink('controllers');

        $this->data['title'] = $getCurrent['title'];
        $this->data['content'] = htmlspecialchars_decode($getCurrent['content']);

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
