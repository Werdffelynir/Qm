<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Comp-2
 * Date: 06.12.13
 * Time: 20:54
 * To change this template use File | Settings | File Templates.
 */

class ControllerEdit extends BaseAdminController{


    /** Обект модели */
    public $modelEdit;

    public $categoryDocumentation = array('-','Home','Documentation','Controllers','Models','Views','Structure','Quick start','Download' );
    public $subCategoryDocumentation = array('To all','Helpers','Functions' );


    /**
     *
     */
    public function after()
    {
        $this->data["pageTitle"] = "Edit Docs";

        $this->setChunk('sidebarFirst','edit/sidebarCreateMenu');

        $this->modelEdit = $this->model("Edit");
        $menuPage = $this->modelEdit->getMenu();

        $menu = "<ul>";
        foreach($menuPage as $title)
            $menu .= "<li><a href=\"".URL."/edit/page/".$title['id']."\">".$title['title']."</a></li>";
        $menu .= "</ul>";

        $this->setChunk('sidebarSecond','edit/sidebarEditMenu', array("menuPage"=>$menu));
    }



    /**
     *
     */
    public function actionIndex()
    {
        /** подключение скриптов на главной странице она же список всех страниц имеющихся */
        //$this->addScript('jquery');
        //$this->addScript('themeScript');

        //var_dump($this->styles);

        $this->data['title'] = 'Список всех сатей!';
        $allPages = $this->modelEdit->getPages();
        $this->data['content'] = $this->partial('edit/pagesList', array('pagesList'=>$allPages));

        $this->show('main');
    }



    /**
     * Создание новой страницы
     */
    public function actionCreate()
    {
        // Отключение 'jquery'
        $this->addScript('jquery', 'disabled');

        // Подключение в футере 'nicEdit', 'themeScript'
        $this->addScript('nicEdit', 'header');
        $this->addScript('themeScript', 'header');

        // Импорт части вида в общий контент, тут я просто перечесляю необходимые мне катгории.
        $formEdit = $this->partial('edit/formEdit',  array(
            'typeID'       => "create",                     // тип формы
            'type'       => "create",                       // тип формы
            'catList'    => $this->categoryDocumentation,   // список категорий
            'subCatList' => $this->subCategoryDocumentation,// список субкатегорий
            'title'      => null,  // бинды
            'link'       => null,  // ...
            'content'    => null,  // ...
            'category'   => null,  // ...
            'subcategory'=> null,  // ...
            'datetime'   => null,  // ...
            'author'     => null,  // ...
            'id'         => null,  // ...
        ));

        $this->data['title'] = 'Создание новой страницы';
        $this->data['content'] = $formEdit;
        $this->show('main');
    }


    /**
     *
     */
    public function actionPage()
    {
        //var_dump();
        //$cont = 'Page id: '. $this->urlParam('edit', 1); //App::$requestFull[1];,
        $urlParam = $this->urlParam('edit', 2);

        if(!empty($urlParam)){

            //$this->extracted = true;

            // Отключение 'jquery'
            //$this->addScript('jquery', 'disabled');
            // Подключение в футере 'nicEdit', 'themeScript'
            $this->addScript('nicEdit', 'header');
            $this->addScript('themeScript', 'header');

            //var_dump($this->_scripts);

            $cont = (int)$urlParam;
            $contentToEdit = $this->modelEdit->getById("pages", $cont);

            /** Query content for editor
            $this->data['form_title']       = $contentToEdit['title'];
            $this->data['form_content']     = $contentToEdit['content'];
            $this->data['form_category']    = $contentToEdit['category'];
            $this->data['form_subcategory'] = $contentToEdit['subcategory'];
            $this->data['form_datetime']    = $contentToEdit['datetime'];
            $this->data['form_author']      = $contentToEdit['author'];
            $this->data['form_id']          = $contentToEdit['id'];
             */

            /** Импорт части вида в общий контент, тут я просто перечесляю необходимые мне катгории.*/
            $formEdit = $this->partial('edit/formEdit',  array(
                'typeID'     => 'update',                       // тип формы
                'type'       => $contentToEdit['type'],         // тип формы
                'catList'    => $this->categoryDocumentation,   // список категорий
                'subCatList' => $this->subCategoryDocumentation,// субкаткгорйи список
                'title'      => $contentToEdit['title'],        // данные существующей статьи
                'link'       => $contentToEdit['link'],         // ...
                'content'    => $contentToEdit['content'],      // ...
                'category'   => $contentToEdit['category'],     // ...
                'subcategory'=> $contentToEdit['subcategory'],  // ...
                'datetime'   => $contentToEdit['datetime'],     // ...
                'author'     => $contentToEdit['author'],       // ...
                'id'         => $contentToEdit['id'],           // ...
            ));

            /** View content page */
            // $this->data['title']    = 'Редактирование:';
            // $this->data['content']  = $formEdit;

            //$this->data['category'] = $this->categoryDocumentation;

            $this->data['title']   = 'Редактирование';
            $this->data['content'] = $formEdit;
            $this->show('main');

            /** Include partial template */
            //$this->show('pEdit/formEdit');
        }else{
            App::redirect(App::$url.'/edit');
        }

    }


    /**
     * Метод для сохранения отправленного с формы
     */
    public function actionSave()
    {

        if(!empty($_POST['title']) AND !empty($_POST['category'])){

            if($_POST['typeID'] == "create")
            {
                $savePage['title']      = $_POST['title'];
                $savePage['type']       = $_POST['type'];
                $savePage['link']       = $_POST['link'];
                $savePage['category']   = $_POST['category'];
                $savePage['subcategory']= $_POST['subcategory'];
                $savePage['content']    = htmlspecialchars($_POST['content']);
                $savePage['datetime']   = time();
                $savePage['author']     = QmUser::id();
                $result = $this->modelEdit->saveNewPage($savePage);

                if($result){
                    App::redirect(App::$url.'/edit');
                }else{
                    App::redirect(App::$url.'/edit/create');
                }
            }
            elseif($_POST['typeID'] == "update")
            {

                $result = $this->modelEdit->updatePage(array(
                    'title'         => $_POST['title'],
                    'type'          => $_POST['type'],
                    'link'          => $_POST['link'],
                    'category'      => $_POST['category'],
                    'subcategory'   => $_POST['subcategory'],
                    'content'       => htmlspecialchars($_POST['content']),
                    'datetime'      => time(),
                    'id'            => $_POST['id']
                ));

                if($result){
                    App::flashArray('update', array('type'=>'success','message'=>'Запись в базе данных успешно обновлена!','class'=>'fleshsuccess'));
                    App::redirect(App::$url.'/edit/page/'.$_POST['id']);
                }else{
                    App::flashArray('update', array('type'=>'error','message'=>'Ошибка записи  в базу данных!','class'=>'flesherror'));
                    App::redirect(App::$url.'/edit/page/'.$_POST['id']);
                }
            }






        }else{
            App::redirect(App::$url.'/edit/create');
        }
    }



    /**
     *

    public function actionCreateSubPage()
    {
        $this->data['title'] = 'actionCreateSubPage:';
        $this->data['content'] = '$menu';
        $this->show('main');
    }*/





    /**
     *
     */
    public function actionDelete()
    {
        $result = $this->modelEdit->deletePage($this->urlParam());
        if($result)
            App::redirect(App::$url.'/edit');
        else
            App::redirect(App::$url.'/edit/create');

        $this->show('main');
    }



}