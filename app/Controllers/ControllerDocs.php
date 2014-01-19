<?php

class ControllerDocs extends BaseSiteController
{

    public function actionIndex()
    {
        $this->data['title']   = "Docs";
        $this->data['content'] = "...";

        $this->show('main');
    }

}





