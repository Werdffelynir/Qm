<?php

class ControllerIndex extends Controller
{

    public function actionIndex()
    {
        $this->data['title']   = 'Демо Сайт';
        $this->data['content'] = 'Здравствуйте, это сайт демонстрирует основную структуру и постоение преложений.';

        echo $this->data['title']."<br>";
        echo $this->data['content']."<br>";

        $this->show('main');
    }

    public function actionTest()
    {
        $this->data['title']   = 'Страница Тест';
        $this->data['content'] = 'Вы находитесь на странице тест.';

        $this->show('main');
    }


}





