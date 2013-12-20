<?php

/**
 * Файл был сгенерирован с помощю Gen Qm Framework
 *
 * вам необходимо провести реорганизацию кода даного контролера
 */

class ControllerHelper extends BaseSiteController{

    /** Временно хранит имена видов */
    #public $view = 'mainKry';

    /** Розпаковывать массивы в виде */
    #protected $extracted = false;

    /** Конструктор задает последовательность загрузки методов */
    public function __construct()
    {
        /**  */
        parent::__construct();
    }


    /** Временно хранит имена видов */
    public function before()
    {
        /**  */
        parent::before();
    }

    /** Временно хранит имена видов */
    public function after()
    {
        /**  */
        #$this->layout = "otherMainLayout";
        #$this->view   = "otherMainView";
    }

    /** Временно хранит имена видов */
    public function actionIndex()
    {
        $this->data["pageTitle"] = "Qm - Быстрый Старт";

        $ModelPages = $this->model("Pages");
        $getCurrent = $ModelPages->getPageByLink('qs');

        $this->data['title'] = $getCurrent['title'];
        $this->data['content'] = htmlspecialchars_decode($getCurrent['content']);
        $this->show('main');
    }

    public function actionQs()
    {
        $getQmPage = $this->urlParam();

        $this->data["pageTitle"] = "Qm - Быстрый Старт";

        $ModelPages = $this->model("Pages");
        $getCurrent = $ModelPages->getPageByLink($getQmPage);

        $this->data['title'] = $getCurrent['title'];
        $this->data['content'] = htmlspecialchars_decode($getCurrent['content']);

        $this->show();
    }

    public function setup(){}
    public function mvc(){}
    public function appflow(){}
    public function firstapp(){}
    public function qmgen(){}
    public function workdb(){}
    public function ext(){}

}