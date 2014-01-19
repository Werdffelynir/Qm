<?php

class ControllerModels extends BaseSiteController
{

    public function actionIndex()
    {
        $this->data['title']   = "Models";
        $this->data['content'] = "...";

        $this->show('main');
    }

}





