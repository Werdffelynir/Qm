<?php

class ControllerIndex extends BaseSiteController
{
    /* Разрешить распаковывать переданные данные в вид */
    //protected $extracted = true;

    protected $userId = false;
    protected $userName = false;

    public function after(){}


    /**
     * **********************************************************************************
     * Page Home
     * **********************************************************************************
     */
    public function actionIndex()
    {

        $this->data['mainTitle'] = 'Быстрый, простой MVC PHP Framework.';
        $this->data['mainContent'] = $this->partial("home/index");

        $this->show('main');

    }


    /**
     * **********************************************************************************
     * Page Login
     * **********************************************************************************
     */
    public function actionLogin()
    {
        if( !empty($_POST['email']) OR !empty($_POST['password']) ){

            $users = $this->model("Users");
            $searchUser = $users->searchUser($_POST['email'], md5($_POST['password']));

            if(!$searchUser){
                App::redirect(App::$url.'/index/login');
            }else{
                QmUser::auth($searchUser['id']);
                App::redirect(App::$url.'/index/index');
            }
        }else{
            $this->data['mainTitle'] = 'Login';
            $this->data['mainContent'] = $this->partial('home/login');
            $this->show('main');
        }
    }


    /**
     * **********************************************************************************
     * Page Logout
     * **********************************************************************************
     */
    public function actionLogout()
    {
        QmUser::unAuth();
        App::redirect(App::$url.'/index/login');
    }


    /**
     * **********************************************************************************
     * Page Documentation
     * **********************************************************************************
     */


    public function actionDoc()
    {
        $re = App::$request;

        $this->data['mainTitle'] = 'Documentation Page';
        $this->data['mainContent'] = 'Controller static text. appDoc/Controllers/ControllerIndex.php
        :: class ControllerIndex - actionDoc. Url '.$re;

        $this->show('main');
    }


    /**
     * **********************************************************************************
     * Page Controllers
     * **********************************************************************************
     */
    public function actionControllers()
    {
        $this->setChunk('sidebarFirst','controllers/sidebarGen');

        $model = $this->model("Pages");
        $dbInfo = $model->getPageByLink('controllers');

        $this->data['mainTitle'] = $dbInfo['title'];
        $this->data['mainTitle'] = $dbInfo['title'];
        $this->data['mainContent'] = htmlspecialchars_decode($dbInfo['content']);

        $this->show('main');
    }


    /**
     * **********************************************************************************
     * Page Models
     * **********************************************************************************
     */
    public function actionModels()
    {
        $this->setChunk('sidebarFirst','chunks/helpMessage');

        $this->data["pageTitle"] = "Qm - Модели";

        $ModelPages = $this->model("Pages");
        $getCurrent = $ModelPages->getPageByLink('models');

        $this->data['mainTitle'] = $getCurrent['title'];
        $this->data['mainContent'] = htmlspecialchars_decode($getCurrent['content']);

        $this->show('main');
    }


    /**
     * **********************************************************************************
     * Page Views
     * **********************************************************************************
     */
    public function actionViews()
    {
        $this->setChunk('sidebarFirst','chunks/helpMessage');

        $this->data["pageTitle"] = "Qm - Представления";

        $ModelPages = $this->model("Pages");
        $getCurrent = $ModelPages->getPageByLink('views');

        $this->data['mainTitle'] = $getCurrent['title'];
        $this->data['mainContent'] = htmlspecialchars_decode($getCurrent['content']);

        $this->show('main');
    }


    /**
     * **********************************************************************************
     * Page Download
     * **********************************************************************************
     */
    public function actionDownload()
    {
        $re = App::$request;

        $this->data['mainTitle'] = 'Download actionIndex';
        $this->data['mainContent'] = 'Download this. REQUEST: '.$re;

        $this->show('main');
    }

}





