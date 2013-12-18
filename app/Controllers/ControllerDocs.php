<?php
/**
 * Created by PhpStorm.
 * User: Werdffelynir
 * Date: 01.12.13
 * Time: 21:19
 */

class ControllerDocs extends BaseSiteController
{

    protected $extracted = false;

    public function actionIndex()
    {
        $this->data["pageTitle"] = "Documentation";

        $ModelPages = $this->model("Pages");
        $getCurrent = $ModelPages->getPageByLink('docs');

        $this->data['title'] = $getCurrent['title'];
        $this->data['content'] = htmlspecialchars_decode($getCurrent['content']);

        $this->show('main');
    }

}