<?php
/**
 * Файл был сгенерирован с помощю Gen Qm Framework
 *
 * Необходимо провести реорганизацию кода даного контролера
 */

class ControllerIndex extends ControllerStructure{

    public $views;
    public $models;

    public function before()
    {
        $this->views = 'administrator/ViewsPartials/';
        $this->models = 'administrator/Models/';
    }


    public function actionIndex()
    {
        $this->data['title'] = ' Structure Administrator';
        $this->data['content'] = 'Main page';

        #Code...

        $this->show($this->views.'main');
    }

    public function actionHello()
    {
        $result = $this->model($this->models.'BaseAdmin');

        $this->data['title'] = 'Hello World';
        $this->data['content'] = 'Model result: '.$result->all();

        #Code...

        $this->show($this->views.'hello');
    }

}