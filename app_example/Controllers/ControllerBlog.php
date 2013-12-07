<?php

class ControllerBlog extends StartController
{
    public function after()
    {
        //$this->setСhunk('chunkAbout','pControllers/sideBarAbout');
    }

    public function actionIndex()
    {
        $this->data['title']   = 'Демо Сайт';
        $this->data['content'] = 'Здравствуйте, это сайт демонстрирует основную структуру и постоение преложений.';

        $this->show('main');
    }

    public function actionTest()
    {
        $this->data['title']   = 'Страница Тест';
        $this->data['content'] = 'Вы находитесь на странице тест.';

        $this->show('main');
    }




}
