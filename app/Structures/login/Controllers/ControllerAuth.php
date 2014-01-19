<?php

class ControllerAuth extends Controller
{
    public function actionIndex()
    {
        $this->data['title']   = 'Structure-Auth';
        $this->data['content'] = 'Structure Structure StructureAuth Content';

        echo 'Structure ControllerAuth.php actionIndex';

        $this->show('main');
    }

    public function actionLogin()
    {
        $this->data['title']   = 'Structure Login Login';
        $this->data['content'] = 'StructureAuth ContentStructure';

        echo 'Structure ControllerAuth.php actionLogin';

        $this->show('main');
    }

}





