<?php


/**
 * Class SimplePDO
 *
Другие полезные методы
$object->dbh->lastInsertId();
$object->dbh->quote('SQL запрос не безопасный');
$object->sth->rowCount();
 *
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
     * Класический не безопасный на выполнение
     *
     * @param $sql
     * @return mixed Колчество затронутых строк
     */
    public function exec($sql) {
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
     * Метод для запроса обновления записи в БД. Еквивалентен методу query()
     * но возвращает количество затронутых при обновлении записей.
     *
     * Запросы осуществляються:
     * <pre>
     * ->update("UPDATE table
     *          SET title=:title, article=:article, date=:date
     *          WHERE id=:id ",
     *      array(
     *          'title'     => $title,
     *          'article'   => $article,
     *          'date'      => time(),
     *          'id'        => $id
     *          )
     *      )
     *
     * ->update("UPDATE table
     *          SET title=?, article=?, date=?
     *          WHERE id=? ",
     *      array(
     *          $title,
     *          $article,
     *          $date,
     *          $id
     *          )
     *      )
     *
     * ->update( "UPDATE table
     *          SET title='".$title."', article='".$article."', date='".$date."'
     *          WHERE id='".$id."' ")
     * <pre>
     *
     * @param $sql
     * @param array $data
     * @return bool
     */
    public function update($sql, array $data=null) {
        if(is_null($data)){
            var_dump($this->sth);
            $this->sth = $this->dbh->prepare($sql);
            $result = $this->sth->execute();
        }else{
            $this->sth = $this->dbh->prepare($sql);
            $result = $this->sth->execute($data);
        }
        return $result;
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
    }

}

