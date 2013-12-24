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

    /** Временно хранит имена видов */
    public function actionSql()
    {
        $modelEdit = $this->model("Edit");

        /*$result =  $modelEdit->db->insert("pages", array("type","link","category","title","content","datetime","author"),
            array(
                'type'     =>'SOME TITLE',
                'link'     =>'SOME LINK',
                'category' =>'SOME CATEGORY',
                'title'    =>'SOME TITLE',
                'content'  =>'SOME CONTENT',
                'datetime' =>'SOME TIME',
                'author'   =>'SOME AUTHOR',
            ));
        var_dump($result);*/
        /*
                    $result =  $modelEdit->db->update("pages", array("type","link","category","title","content","datetime","author"),
                        array(
                            'type'     =>'SOME TITLE 3',
                            'link'     =>'SOME LINK 3',
                            'category' =>'SOME CATEGORY 3',
                            'title'    =>'SOME TITLE 3',
                            'content'  =>'SOME CONTENT 3',
                            'datetime' =>'SOME TIME 3',
                            'author'   =>'SOME AUTHOR 3',
                        ),
                        array("id=:updId AND title=:updTitle",
                            array('updId'=>13, 'updTitle'=>'SOME TITLE2')
                        )
                    );

                $result =  $modelEdit->db->upgrade("pages", array("type","link","category","title","content","datetime","author"),
                    array(
                        'type'     =>'SOME TITLE 12',
                        'link'     =>'SOME LINK 12',
                        'category' =>'SOME CATEGORY 12',
                        'title'    =>'SOME TITLE 12',
                        'content'  =>'SOME CONTENT 12',
                        'datetime' =>'SOME TIME 12',
                        'author'   =>'SOME AUTHOR 12',
                    ),
                    "id=12"
                );

        var_dump($result);*/

    }

    public function actionModel()
    {
        $this->incModel("Edit");
        $obj = new Edit;
        var_dump($obj);
    }

}