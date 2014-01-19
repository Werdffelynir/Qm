<?php

class ControllerIndex extends BaseSiteController
{

    protected $userId   = false;
    protected $userName = false;

    public function after(){}

    public function actionIndex()
    {
        //$modelBlog = $this->model("Blog");

        //var_dump($modelBlog->dbTwo);
        //var_dump($modelBlog->db);
        //var_dump($modelBlog->dbMySql);
        //$option = $modelBlog->dbMySql->query("SELECT * FROM tbl_post")->all();
        //var_dump($option);

        //$option = $modelBlog->db->query("SELECT * FROM pages")->all();
        //var_dump($option);

        $this->data['title']   = "Home";
        $this->data['content'] = "...";

        $this->show('main');
    }

    public function actionEvent()
    {
        $this->data['title']   = "Test Events";
        $this->data['content'] = "";

        /* Events App method() */

        App::eventRegister('e-01', function(){ echo "App::eventRegister"; });
        App::eventRegister('e-02', array($this, 'showEvent'));
        App::eventRegister('e-03', array($this, 'showName'));
        App::eventRegister('e-04', array($this, 'showNameTwo'));

        /* Events Controller method() */

        $this->hookRegister('h-00', function(){ echo '$this->hookRegister'; });
        $this->hookRegister('h-01', 'showEvent');
        $this->hookRegister('h-02', 'showName', array('Ваня'));
        $this->hookRegister('h-03', 'showNameTwo', array('Ваня','Таня'));


        App::filterRegister('f_01', array($this,'testFilter'), 1);

        $this->show('home/event');
    }

    public function showEvent() {
        echo "<h3>Test Event 'ANE'</h3>";
    }

    public function showName($name='Маша') {
        echo "<h3>Test Event '".$name."'</h3>";
    }
    public function showNameTwo($name1,$name2) {
        echo "<h3>Здравствуй '".$name1."' и '".$name2."'</h3>";
    }

    public function testFilter($text) {
        echo "<h3>".$text." handlers with App Filter</h3>";
    }





    public function actionLogin()
    {
        echo "actionLogin";
    }

    public function actionDb()
    {
        $modelBlog = $this->model("Blog");
        $option = $modelBlog->db->query("SELECT * FROM pages")->all();

        $this->data['title']   = "title";
        $this->data['content'] = $option;

        $this->show('main');

    }

}





