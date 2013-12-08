<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Comp-2
 * Date: 06.12.13
 * Time: 20:54
 * To change this template use File | Settings | File Templates.
 */

class ControllerEdit extends StartController{

    public function after()
    {
        $this->data["pageTitle"] = "Edit Docs";
        $this->setСhunk('chunkAboutRightFirst','pEdit/chunkAboutRightFirst');

        $modelEdit = $this->model("Edit");
        $menuPage = $modelEdit->getMenu();
        $menu = "<ul>";
        foreach($menuPage as $title)
            $menu .= "<li><a href=\"edit/page/".$title['id']."\">".$title['title']."</a></li>";
        $menu .= "</ul>";
        $this->setСhunk('chunkAboutRightMenu','pEdit/chunkAboutRightMenu', array("menuPage"=>$menu));
    }

    public function actionIndex()
    {
        $this->data['title'] = 'Быстрый, простой MVC PHP Framework.';
        $this->data['content'] = '$menu';

        $this->show('main');
    }
/*

    Home
    Documentation
    Controllers
    Models
    Views
    Download
    Edit

*/
    public function actionCreatePage()
    {
        $formEdit = $this->partial('pEdit/formEdit',  array(
            'category'=>array('Home','Documentation','Controllers','Models','Views','Structure','Quick start','Download' )
        ));
        $this->data['title'] = 'actionCreatePage:';
        $this->data['content'] = $formEdit;
        $this->show('main');
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