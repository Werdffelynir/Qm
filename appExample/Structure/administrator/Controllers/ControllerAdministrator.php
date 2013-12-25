<?php

class ControllerAdministrator extends Controller
{
    public function actionIndex()
    {
        $this->data['title'] = ' Structure-Администратора';
        $this->data['content'] = ' Structure Structure StructureАдминистратора Content';

        echo 'Structure ControllerAdministrator.php actionIndex';

        $this->show('main');
    }

    public function actionLogin()
    {
        $this->data['title'] = ' Structure Администратора Login';
        $this->data['content'] = ' Администратора ContentStructure';

        echo 'Structure ControllerAdministrator.php actionLogin';

        $this->show('main');
    }

}





