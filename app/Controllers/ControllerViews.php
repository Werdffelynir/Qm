<?php

class ControllerViews extends BaseSiteController
{

    public function actionIndex()
    {
        $this->data['title']   = "Views";
        $this->data['content'] = "...";

        $this->show('main');
    }

}





