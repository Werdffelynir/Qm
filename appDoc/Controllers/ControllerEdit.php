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

    // Списки категорий и суб категорий.
    public $categoryDocumentation = array('-','Home','Documentation','Controllers','Models','Views','Structure','Quick start','Download' );
    public $subCategoryDocumentation = array('To all','Helpers','Functions' );


    /**
     * Общие настройки для контролера.
     */
    public function after()
    {

        // Отключение 'jquery'
        #$this->addScript('jquery', 'disabled');

        // Подключение в header 'nicEdit', 'themeScript'
        $this->addScript('nicEdit', 'header');
        $this->addScript('themeScript', 'header');

        // Общий заголовок
        $this->data["title"] = "Edit Docs";

        // Создание экземпляра модели
        // Назначение его свойству $this->modelEdit, которое может используваться
        // в других методах
        $this->modelEdit = $this->model("Edit");

        // Обращение к методу модели, выборка основных статей документации
        $menuPage = $this->modelEdit->getMenu();

        // Формировние меню
        $menu = "<ul>";
        foreach($menuPage as $title)
            $menu .= "<li><a href=\"".URL."/edit/page/".$title['id']."\">".$title['title']."</a></li>";
        $menu .= "</ul>";

        // Подключение чанка Редактирование данных
        $this->setChunk('sidebarFirst','edit/sidebarCreateMenu');
        // Подключение чанка Список Основных Страниц
        $this->setChunk('sidebarSecond','edit/sidebarEditMenu', array("menuPage"=>$menu));
    }



    /**
     *
     */
    public function actionIndex()
    {
        $allPages = $this->modelEdit->getPages();

        $this->data['mainTitle'] = 'Список всех сатей!';
        $this->data['mainContent'] = $this->partial('edit/pagesList', array('pagesList'=>$allPages));

        $this->show('main');
    }



    /**
     * Создание новой страницы
     */
    public function actionCreate()
    {
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

        $this->data['mainTitle'] = 'Создание новой страницы';
        $this->data['mainContent'] = $formEdit;
        $this->show('main');
    }


    /**
     *
     */
    public function actionPage()
    {
        $urlParam = $this->urlParam('edit', 2);

        if(!empty($urlParam)){

            $cont = (int)$urlParam;
            $contentToEdit = $this->modelEdit->getById("pages", $cont);

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
            $this->data['mainTitle']   = 'Редактирование';
            $this->data['mainContent'] = $formEdit;
            $this->show('main');
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
                    App::flashArray('update', array('type'=>'success','message'=>'Запись в базе данных успешно создана!','class'=>'fleshsuccess'));
                    App::redirect(App::$url.'/edit/page/'.$result);
                }else{
                    App::flashArray('update', array('type'=>'error','message'=>'Ошибка записи  в базу данных!','class'=>'flesherror'));
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
                    'author'        => QmUser::id(),
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