<?php

class Blog extends Model
{

    public $dbTwo;
    public $dbMySql;

    public function afterConnect(){
        //$this->dbTwo = $this->connect("dbTwo");
        //$this->dbMySql = $this->connect("dbMySql");
    }


    public function all()
    {
        $sql = "SELECT * FROM pages";
        return $this->db->query($sql)->all();
    }
}