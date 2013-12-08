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
        $modelEdit = $this->model("Edit");
        $menuPage = $modelEdit->getMenu();


        $menu = "<ul>";
        foreach($menuPage as $title)
            $menu .= "<li><a href=\"edit/page/".$title['id']."\">".$title['title']."</a></li>";
        $menu .= "</ul>";



        $this->data['title'] = 'Быстрый, простой MVC PHP Framework.';
        $this->data['content'] = $menu;

        $this->show('main');
    }

    public function actionCreate()
    {
        $this->data['title'] = 'Редактирование:';
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