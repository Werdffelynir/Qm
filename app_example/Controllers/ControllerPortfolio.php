<?php
/**
 * Created by PhpStorm.
 * User: Werdffelynir
 * Date: 01.12.13
 * Time: 21:19
 */

class ControllerPortfolio extends StartController
{

    protected $extracted = true;

    public function actionIndex()
    {
        /**/
        $this->data["pageTitle"] = "Documentation";

        $content='';
        $blog = $this->model("Blog");
        $bResult = $blog->all();

        foreach($bResult as $b){
            $content .= $b['content'];
        }

        $this->data['title'] = 'Title Docs Page';
        $this->data['content'] = $content;

        $this->show('main');
    }

}