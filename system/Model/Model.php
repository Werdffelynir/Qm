<?php
/**
 * Class Model
 *
 * Author: OLWerdffelynir
 * Date: 07.12.13
 */
 class Model {

    public $db;
    public $dbConfConName = array();
    public $dbConfConSett = array();

    public function __construct()
    {
        $this->beforeConnect();
        $this->dbConfConSett = App::$config['dbConnects'];

        if( App::$config["dbAutoConnect"] == true) {
            if(!empty($this->dbConfConSett)) {

                $ConnectKeyName = key($this->dbConfConSett);
                $this->dbConfConName[] = $ConnectKeyName;
                $dbConfigConnects = array_shift($this->dbConfConSett);

                include_once PATH_SYS_CLASSES.$dbConfigConnects["class"].".php";

                if(class_exists('SimplePDO'))
                    $this->$ConnectKeyName = new SimplePDO($dbConfigConnects);

            }
        }

        $this->afterConnect();
    }

    public function beforeConnect(){}
    public function afterConnect(){}

    public function connect($dbNameConnect)
    {
        if(array_key_exists($dbNameConnect, $this->dbConfConSett) AND !in_array($dbNameConnect, $this->dbConfConName)){
            $this->dbConfConName[] = $dbNameConnect;
            $dbConfigConnects = array_shift($this->dbConfConSett);

            include PATH_SYS_CLASSES.$dbConfigConnects["class"].".php";

            if(!class_exists('SimplePDO'))
                return $this->$dbNameConnect = new SimplePDO($dbConfigConnects);
            /*else
                return $this->$dbNameConnect = new SimplePDO($dbConfigConnects);*/

        }else{
            return false;
        }
    }




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
     * @param $where    Часть запроса SQL where
     * @return array
     */
    public function getAll($tbl, $data=null, $where='')
    {
        $sql = '';
        if($data==null){
            $sql = "SELECT * FROM ".$tbl;
        }elseif(is_string($data)){
            $sql = "SELECT ".$data." FROM ".$tbl;
        }elseif(is_array($data)){
            $column = implode(", ", $data);
            $sql = "SELECT ".$column." FROM ".$tbl;
        }
        $sql .= (!empty($where)) ? ' WHERE '.$where : '' ;
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
