<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Comp-2
 * Date: 29.11.13
 * Time: 19:12
 * To change this template use File | Settings | File Templates.
 */

abstract class Model {

    public $db;

    public function __construct()
    {
        $this->beforeConnect();
        $this->db = new SimplePDO();
        $this->afterConnect();
    }
    public function beforeConnect(){}
    public function afterConnect(){}


    /**
     * Выберает все с указаной таблицы
     *
     * @param $tbl      название таблицы
     * @param $data     название таблицы
     * @return array
     */
    public function getAll($tbl, $data=null)
    {
        if($data==null){
            $sql = "SELECT * FROM ".$tbl;
        }elseif(is_string($data)){
            $sql = "SELECT ".$data." FROM ".$tbl;
        }elseif(is_array($data)){
            $column = implode(", ", $data);
            $sql = "SELECT ".$column." FROM ".$tbl;
        }

        return $this->db->query($sql)->all();
    }


    /**
     * Выберает все с указаной таблицы по id
     *
     * @param $tbl      название таблицы
     * @param $id       id записи
     * @return mixed
     */
    public function getById($tbl, $id)
    {
        $sql = "SELECT * FROM ".$tbl." WHERE id='".$id."'";
        return $this->db->query($sql)->row();
    }


    /**
     * Выберает все с указаной таблицы по названию колонки
     *
     * @param $tbl      название таблицы
     * @param $attr     название колонки
     * @param $attrVal  значение в колонке
     * @return array
     */
    public function getByAttr($tbl, $attr, $attrVal)
    {
        $sql = "SELECT * FROM ".$tbl." WHERE ".$attr."='".$attrVal."'";
        return $this->db->query($sql)->all();
    }


    public function insert($tbl, array $data)
    {

    }

    public function update($tbl, array $data)
    {

    }
}
