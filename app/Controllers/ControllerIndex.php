<?php

class ControllerIndex extends StartController
{
    /* Разрешить распаковывать переданные данные в вид */
    //protected $extracted = true;

    protected $userId = false;
    protected $userName = false;

    public function after(){}

    public function actionIndex()
    {
        $this->data['title'] = 'Быстрый, простой MVC PHP Framework.';
        $this->data['content'] = $this->partial('pIndex/partialContent');

       //$this->addScript('nicEdit');

       //$this->addScript('nicEdit', 'footer');
       //$this->addScript(array('nicEdit', 'jquery'), 'footer');

       //var_dump($this->_scripts);
       // echo "\n\n";
       //var_dump($this->scripts);

        //$st = 'E:\__SERVER\domains\qm.loc\app\assets\jquery\jquery-2.0.3.min.js';
        //$regScriptUrl = substr($st, strpos($st, QmConf("baseUrl")) + strlen(QmConf("baseUrl")) + 1 ) ;
        //$regScriptUrl = URL.'/'.str_replace('\\','/',$regScriptUrl);

        //var_dump($regScriptUrl);





        $this->show('main');
    }

    public function actionLogin()
    {
        if(!empty($_POST['email'])){

            $users = $this->model("Users");
            $searchUser = $users->searchUser($_POST['email'], md5($_POST['password']));

            if(!$searchUser)
                QmFunc::redirect(URL.'/index/login');

            QmUser::auth($searchUser['id']);
            QmFunc::redirect(URL.'/index/index');
        }else{
            $this->data['title'] = 'Login';
            $this->data['content'] = $this->partial('pIndex/partialLogin');
            $this->show('main');
        }
    }

    public function actionLogout()
    {
        QmUser::unAuth();
        QmFunc::redirect(URL.'/index/login');
    }


}





