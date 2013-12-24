<?php


/**
 * Class SimplePDO, обертка для запросов.
 *
 * Author: OL Werdffelynir
 * Date: 07.12.13
 *
 * <pre>
 * Рекомендую использывать втроеные методы PDO для расширеных и сложных
 * запросов, даные же методы дял простых запросов.
 *
 * Методы класса:
 * Классический не безопасный метод выполнения запросов
 *     exec($sql)
 * Базовый метод запросов к базе данных, использует стандартные prepare($sql) и execute($data)
 *     query($sql, array $data=null)
 * Извлечь строку с запроса
 *     row($type="assoc")
 * Извлечь несколько строк
 *     all($type="assoc")
 * Закрыть соединение
 *     close()
 * Обертка INSERT
 *     insert($table, array $dataColumn, array $dataValue)
 * Обертка UPDATE
 *     update($table, array $dataColumn, array $dataValue, $where)
 *
 *
 * Другие полезные методы PDO
 * $object->dbh->lastInsertId();
 * $object->dbh->quote('SQL запрос не безопасный');
 * $object->sth->rowCount();
 * </pre>
*/
class SimplePDO {

    private $driver;
    private $host;
    private $port;
    private $user;
    private $pass;
    private $dbName;
    private $path;

    public $dbh;
    public $sth;

    public function __construct() {

        $classDB = QmConf("db");

        $this->driver   = ($classDB['driver'])  ? $classDB['driver']:'';
        $this->host     = ($classDB['host'])    ? "host=". $classDB['host'].";"         :'';
        $this->port     = ($classDB['port'])    ? "port=". $classDB['port'].";"         :'';
        $this->user     = ($classDB['user'])    ? "user=". $classDB['user'].";"         :'';
        $this->pass     = ($classDB['password'])? "password=". $classDB['password'].";" :'';
        $this->dbName   = ($classDB['dbName'])  ? "dbname=". $classDB['dbName'].";"     :'';
        $this->path     = ($classDB['path'])    ? $classDB['path']:'';

        if(!empty($this->driver)){
            try {
                $this->dbh = new SafePDO( $this->driver.":".$this->path.$this->host.$this->port.$this->dbName.$this->user.$this->pass );
            }catch(PDOException $e) {
                echo $e->getMessage();
            }
        }

    }


    /**
     * Классический не безопасный метод выполнения запросов
     *
     * @param $sql
     * @return mixed Колчество затронутых строк
     */
    public function exec($sql) {
        if($this->dbh == null) die("Connection with DataBase closed!");
        $count = $this->dbh->exec($sql);
        return $count;
    }


    /**
     * Базовый метод запросов к базе данных.
     * Использует стандартный метод execute() через обертку, принимает sql запрос,
     * или если указан второй параметр происходит выполнение через метод prepare()
     * возвращает екземпляр обекта
     *
     * Запросы осуществляються:
     * <pre>
     * ->query( "INSERT INTO blog (title, article, date) values (:title, :article, :date)",
     *      array(
     *          'title' => $title,
     *          'article' => $article,
     *          'date' => time()
     *          )
     *      )
     *
     * ->query( "INSERT INTO blog (title, article, date) values (?, ?, ?)",
     *      array(
     *          $title,
     *          $article,
     *          $date
     *          )
     *      )
     * ->query( "SELECT title, article, date FROM blog WHERE id=:id",
     *      array('id'=> '215')
     *      )
     * <pre>
     * @param string $sql   Принимает открытый SQL запрос или безопасный
     * @param array $data   Значения для безопасного запроса
     * @return $this        Возвращает екземпляр обекта
     */
    public function query($sql, array $data=null)
    {
        if($this->dbh == null){
            //QmError("Connection with DataBase closed!", "Соединение с Базой данны не закрыто или не существует");
            die("Connection with DataBase closed!");
        }

        if(is_null($data)){
            $this->sth = $this->dbh->prepare($sql);
            $this->sth->execute();
        }else{
            $this->sth = $this->dbh->prepare($sql);
            $this->sth->execute($data);
        }
        return $this;
    }


    /**
     * Извлечь строку с запроса
     *
     * Выберает типы: assoc, class, obj
     * @param  $type использует FETCH_ASSOC, FETCH_CLASS, и FETCH_OBJ.
     * @return mixed
     */
    public function row($type="assoc")
    {
        if($type=="assoc") $this->sth->setFetchMode(SafePDO::FETCH_ASSOC);
        if($type=="obj") $this->sth->setFetchMode(SafePDO::FETCH_OBJ);
        if($type=="class") $this->sth->setFetchMode(SafePDO::FETCH_CLASS);
        return $this->sth->fetch();
    }

    /**
     * Извлечь несколько строк
     *
     * @param  $type
     * @return array
     */
    public function all($type="assoc")
    {
        if($type=="assoc") $this->sth->setFetchMode(SafePDO::FETCH_ASSOC);
        if($type=="obj") $this->sth->setFetchMode(SafePDO::FETCH_OBJ);
        if($type=="class") $this->sth->setFetchMode(SafePDO::FETCH_CLASS);

        $result = array();

        while($rows = $this->sth->fetch())
        {
            $result[] = $rows;
        };
        return $result;
    }

    /**
     * Закрыть соединение
     */
    public function close() {
        $this->dbh = null;
        unset($this->dbh);
    }



    /**
     * Обертка INSERT
     * <pre>
     * ->insert("pages", array("title","link","content","datetime","author"),
     *      array(
     *          'title'     =>'SOME TITLE',
     *          'link'      =>'SOME LINK',
     *          'content'   =>'SOME CONTENT',
     *          'datetime'  =>'SOME DATETIME',
     *          'author'    =>'SOME AUTHOR',
     *      ));
     * Сгенерирует:
     * "INSERT INTO pages (title,link,content,datetime,author)
     *      VALUES (:title,:link,:content,:datetime,:author)"
     * и подставит необходимые значения.
     * </pre>
     *
     * @param $table            - Имя таблицы
     * @param array $dataColumn - Масив названий колонок для обновлеия
     * @param array $dataValue  - Массив значений для установленных $dataColumn
     * @return bool
     */
    public function insert($table, array $dataColumn, array $dataValue)
    {
        if(count($dataColumn) == count($dataValue) )
        {
            $constructSql = "INSERT INTO ".$table." (";
            $constructSql .= implode(", ", $dataColumn);
            $constructSql .= ") VALUES (";
            $constructSql .= ':'.implode(", :", $dataColumn);
            $constructSql .= ")";

            //$resultUpdate = $this->dbh->query($constructSql, $dataValue);
            $this->sth = $this->dbh->prepare($constructSql);
            $resultInsert = $this->sth->execute($dataValue);
            return $resultInsert;
        }else{
            return false;
        }
    }



    /**
     * Метод обертка UPDATE
     * <pre>
     * ->update("pages", array("type","link","category","title","content","datetime","author"),
     *      array(
     *          'type'     =>'SOME DATA TITLE',
     *          'link'     =>'SOME DATA LINK',
     *          'category' =>'SOME DATA CATEGORY',
     *          'title'    =>'SOME DATA TITLE',
     *          'content'  =>'SOME DATA CONTENT',
     *          'datetime' =>'SOME DATA TIME',
     *          'author'   =>'SOME DATA AUTHOR',
     *      ),
     *      "id=13"
     *  );
     *
     * ->update("pages", array("type","link","category","title","content","datetime","author"),
     *      array(
     *          'type'     =>'SOME DATA TITLE',
     *          'link'     =>'SOME DATA LINK',
     *          'category' =>'SOME DATA CATEGORY',
     *          'title'    =>'SOME DATA TITLE',
     *          'content'  =>'SOME DATA CONTENT',
     *          'datetime' =>'SOME DATA TIME',
     *          'author'   =>'SOME DATA AUTHOR',
     *      ),
     *      array( "id=:updId AND title=:updTitle",
     *          array('updId'=>13, 'updTitle'=>'SOME TITLE')
     *      )
     *  );
     * Сгенерирует: "UPDATE pages SET title=:title, type=:type, link=:link, category=:category, subcategory=:subcategory, content=:content, datetime=:datetime WHERE id=:updId AND title=:updTitle;"
     * </pre>
     *
     * @param $table            - Имя таблицы
     * @param array $dataColumn - Масив названий колонок для обновлеия
     * @param array $dataValue  - Массив значений для установленных $dataColumn
     * @param $where            - определение, строка НЕ безопасно "id=$id", или безопасный вариант array( "id=:updId", array('updId'=>$id))
     * @return bool
     */
    public function update($table, array $dataColumn, array $dataValue, $where)
    {
        if(count($dataColumn) == count($dataValue) )
        {
            $constructSql = "UPDATE ".$table." SET ";

            for($i=0; $i<count($dataColumn); $i++){
                if($i < count($dataColumn)-1 ){
                    $constructSql .= $dataColumn[$i]."=:".$dataColumn[$i].", ";
                }else{
                    $constructSql .= $dataColumn[$i]."=:".$dataColumn[$i]." ";
                }
            }

            if(is_string($where))
            {
                $constructSql .= " WHERE ".$where;
            }
            elseif( is_array($where) AND is_array($where[1]) )
            {
                $constructSql .= " WHERE ".$where[0];
                $dataValue = array_merge($dataValue,$where[1]);
            }

            $this->sth = $this->dbh->prepare($constructSql);
            $resultUpdate = $this->sth->execute($dataValue);

            return $resultUpdate;
        }else{
            return false;
        }
    }
}

