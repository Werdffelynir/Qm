<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Comp-2
 * Date: 06.12.13
 * Time: 20:54
 * To change this template use File | Settings | File Templates.
 */

class ControllerEdit extends StartController{

    public function actionIndex()
    {
        $this->data['title'] = 'Быстрый, простой MVC PHP Framework.';
        $this->data['content'] = 'Быстрый, простой MVC PHP Framework.';

        $this->show('main');
    }

}