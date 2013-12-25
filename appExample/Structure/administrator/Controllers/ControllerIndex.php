<?php

class ControllerIndex extends Controller
{
    public function actionIndex()
    {
        $this->data['title'] = 'Модуль Администратора';
        $this->data['content'] = 'Модуль Администратора Content';

        echo 'Structure ControllerIndex.php actionIndex';

        $this->show('main');
    }

    public function actionLogin()
    {
        $this->data['title'] = 'Модуль Администратора Login';
        $this->data['content'] = 'Модуль Администратора Content';

        echo 'Structure ControllerIndex.php actionLogin';

        $this->show('main');
    }

}





