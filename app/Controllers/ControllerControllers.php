<?php

class ControllerControllers extends BaseSiteController
{

    public function actionIndex()
    {
        $this->data['title']   = "Controllers";
        $this->data['content'] = "...";

        $this->show('main');
    }

}





