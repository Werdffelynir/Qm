<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Comp-2
 * Date: 06.12.13
 * Time: 20:54
 * To change this template use File | Settings | File Templates.
 */

class ControllerEdit extends StartController{

    public $modelEdit;

    public function before(){
        parent::before();

    }


    public function after()
    {
        $this->data["pageTitle"] = "Edit Docs";
        $this->setСhunk('chunkAboutRightFirst','pEdit/chunkAboutRightFirst');

        $this->modelEdit = $this->model("Edit");
        $menuPage = $this->modelEdit->getMenu();

        $menu = "<ul>";
        foreach($menuPage as $title)
            $menu .= "<li><a href=\"edit/page/".$title['id']."\">".$title['title']."</a></li>";
        $menu .= "</ul>";
        $this->setСhunk('chunkAboutRightMenu','pEdit/chunkAboutRightMenu', array("menuPage"=>$menu));
    }



    public function actionIndex()
    {

        $this->data['title'] = 'Список всех сатей!';
        $allPages = $this->modelEdit->getPages();
        $this->data['content'] = $this->partial('pEdit/allPages', array('allPages'=>$allPages));

        $this->show('main');
    }



    /** Создание новой страницы */
    public function actionCreatePage()
    {
        // Отключение 'jquery'
        $this->addScript('jquery', 'disabled');

        // Подключение в футере 'nicEdit', 'themeScript'
        $this->addScript('nicEdit', 'footer');
        $this->addScript('themeScript', 'footer');

        // Импорт части вида в общий контент, тут я просто перечесляю необходимые мне катгории.
        $formEdit = $this->partial('pEdit/formEdit',  array(
            'category'=>array('Home','Documentation','Controllers','Models','Views','Structure','Quick start','Download' )
        ));

        $this->data['title'] = 'Создание новой страницы';
        $this->data['content'] = $formEdit;
        $this->show('main');
    }


    /** Метод для сохранения отправленного с формы */
    public function actionCreatePageSave()
    {
        if(!empty($_POST['title']) AND !empty($_POST['category'])){

            $savePage['title']      = $_POST['title'];
            $savePage['category']   = $_POST['category'];
            $savePage['content']    = htmlspecialchars($_POST['content']);
            $savePage['datetime']   = time();
            $savePage['author']     = QmUser::id();

        $result = $this->modelEdit->saveNewPage($savePage);
            if($result)
                QmFunc::redirect(URL.'/edit');
            else
                QmFunc::redirect(URL.'/edit/createpage');
        }else{
            QmFunc::redirect(URL.'/edit/createpage');
        }
    }


    public function actionCreateSubPage()
    {
        $this->data['title'] = 'actionCreateSubPage:';
        $this->data['content'] = '$menu';
        $this->show('main');
    }


    public function actionPage()
    {


        $this->data['title'] = 'Редактирование:';
        $this->data['content'] = '$menu';

        $this->show('main');
    }




}