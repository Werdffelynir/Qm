<?php

class ControllerIndex extends BaseSiteController
{
    /* Разрешить распаковывать переданные данные в вид */
    //protected $extracted = true;

    protected $userId = false;
    protected $userName = false;

    public function after(){}

    public function actionIndex()
    {

        $this->data['title'] = 'Быстрый, простой MVC PHP Framework.';
        $this->data['content'] = $this->partial("home/index");

        //$jsA = App::createObj('classes/class/jopa/QmJS');

        //var_dump($jsA);



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
        if( !empty($_POST['email']) OR !empty($_POST['password']) ){

            $users = $this->model("Users");
            $searchUser = $users->searchUser($_POST['email'], md5($_POST['password']));

            if(!$searchUser){
                App::redirect(App::$url.'/index/login');
            }else{
                QmUser::auth($searchUser['id']);
                App::redirect(App::$url.'/index/index');
            }
        }else{
            $this->data['title'] = 'Login';
            $this->data['content'] = $this->partial('home/login');
            $this->show('main');
        }
    }

    public function actionLogout()
    {
        QmUser::unAuth();
        App::redirect(App::$url.'/index/login');
    }


}





