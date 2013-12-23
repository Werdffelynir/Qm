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
     * Выберает все записи с указаной таблицы.
     * Если указан второй аргумент выбирает только те что вказаны в нем
     *
     * <pre>
     * Например:
     *
     * ->getAll("table");
     *
     * ->getAll("table", "title, content, author");
     *
     * ->getAll("table", array(
     *      "title",
     *      "content",
     *      "author"
     * ));
     *
     * </pre>
     * @param $tbl      название таблицы
     * @param $data     (string|array)
     *                  если string через запятую, выберает указаные,
     *                  если array по значених выберает указаные
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
     * <pre>
     * Например:
     *
     * ->getById("table", 215);
     *
     * ->getById("table", 215, "title, content, author");
     *
     * ->getById("table", 215, array(
     *      "title",
     *      "content",
     *      "author"
     * ));
     *
     * </pre>
     * @param $tbl      название таблицы
     * @param $id       id записи
     * @param $data     (string|array)
     *                  если string через запятую, выберает указаные,
     *                  если array по значених выберает указаные
     * @return mixed
     */
    public function getById($tbl, $id, $data=null)
    {
        if($data==null){
            $sql = "SELECT * FROM ".$tbl." WHERE id='".$id."'";
        }elseif(is_string($data)){
            $sql = "SELECT ".$data." FROM ".$tbl." WHERE id='".$id."'";
        }elseif(is_array($data)){
            $column = implode(", ", $data);
            $sql = "SELECT ".$column." FROM ".$tbl." WHERE id='".$id."'";
        }

        return $this->db->query($sql)->row();
    }


    /**
     * Выберает одну запись с указаной таблицы по названию колонки
     *
     * <pre>
     * Например:
     *
     * ->getByAttr("table", "column", "column_value");
     *
     * ->getByAttr("table", "column", "column_value", "title, content, author");
     *
     * ->getByAttr("table", "column", "column_value", array(
     *      "title",
     *      "content",
     *      "author"
     * ));
     *
     * </pre>
     *
     * @param $tbl      название таблицы
     * @param $attr     название колонки
     * @param $attrVal  значение в колонке
     * @param $data     (string|array)
     *                  если string через запятую, выберает указаные,
     *                  если array по значених выберает указаные
     * @return array
     */
    public function getByAttr($tbl, $attr, $attrVal, $data=null)
    {
        if($data==null){
            $sql = "SELECT * FROM ".$tbl." WHERE ".$attr."='".$attrVal."'";
        }elseif(is_string($data)){
            $sql = "SELECT ".$data." FROM ".$tbl." WHERE ".$attr."='".$attrVal."'";
        }elseif(is_array($data)){
            $column = implode(", ", $data);
            $sql = "SELECT ".$column." FROM ".$tbl." WHERE ".$attr."='".$attrVal."'";
        }

        return $this->db->query($sql)->row();
    }



    /**
     * Выберает все с указаной таблицы по названию колонки
     *
     * <pre>
     * Например:
     *
     * ->getAllByAttr("table", "column", "column_value");
     *
     * ->getAllByAttr("table", "column", "column_value", "title, content, author");
     *
     * ->getAllByAttr("table", "column", "column_value", array(
     *      "title",
     *      "content",
     *      "author"
     * ));
     *
     * </pre>
     * @param $tbl
     * @param $attr
     * @param $attrVal
     * @param $data     (string|array)
     *                  если string через запятую, выберает указаные,
     *                  если array по значених выберает указаные
     * @return array
     */
    public function getAllByAttr($tbl, $attr, $attrVal, $data=null)
    {
        if($data==null){
            $sql = "SELECT * FROM ".$tbl." WHERE ".$attr."='".$attrVal."'";
        }elseif(is_string($data)){
            $sql = "SELECT ".$data." FROM ".$tbl." WHERE ".$attr."='".$attrVal."'";
        }elseif(is_array($data)){
            $column = implode(", ", $data);
            $sql = "SELECT ".$column." FROM ".$tbl." WHERE ".$attr."='".$attrVal."'";
        }
        return $this->db->query($sql)->all();
    }

}
