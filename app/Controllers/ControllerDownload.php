<?php

class ControllerDownload extends BaseSiteController
{

    public function actionIndex()
    {
        $this->data['title']   = "Download";
        $this->data['content'] = "...";

        $this->show('main');
    }

}