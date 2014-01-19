<?php

class Rendering
{

    /**
     * Используеться для передачи данных подобно к методу $data, но имеет иной подход
     * к реализации вывода.
     * Работет совместно с методом setVar()
     */
    public $var;


    /**
     * Передаче в вид или шаблон частей кода разметки, такой себе мини виджет
     * Работет совместно с методом setChunk()
     */
    public $chunk;


    /**
     * Берет в переменную чатсть вида и возвращает в переменную,
     * работает на подобии метода out() но не импортирует данные
     * в layout.
     *
     *<pre>
     * Пример:
     * $content = $this->partial("blog/topSidebar", array( "var" => "value" ));
     *</pre>
     *
     * @param  string   $viewName   путь к виду
     * @param  array    $data       массив данных для передачи в вид
     * @return string
     */
    public function partial( $viewName, array $data=null )
    {
        if(!is_null($data))
            extract($data);

        $toInclude = PATH_APP_VIEWSPARTIALS.$viewName.".php";

        ob_start();

        include $toInclude;

        $getContents = ob_get_contents();

        ob_clean();

        return $getContents;
    }


    /**
     * Устанавлевает передачу данных в вид или шаблон напрямкю.
     * Первый аргумет регестрация имени, второй массив с данными
     * Работает подобно data, аналог но с иным подходом
     *
     *<pre>
     * Пример:
     * $this->setVar("myVar", array( "var" => "value" ));
     *
     * myVar регестрация имени, при обращении:
     * $this->var['__val__'];
     * или многомерный
     * $this->var['__my_data__']['__val__'];
     * возвращает переданые данные.
     *</pre>
     *
     * @param string $varName   зарегестрированое имя
     * @param array $dataVar    передача данных
     */
    public function setVar( $varName, array $dataVar=null )
    {
        foreach ($dataVar as $keyVar=>$valueVar) {
            if(!is_numeric($keyVar)){
                $this->var[$varName][$keyVar] = $valueVar;
            }else{
                $this->var[$varName] = $valueVar;
            }
        }
    }


    /**
     *
     * Метод подтягивает часть вида и может передать в него данные, результат
     * будет передан в основной вид или тему, также есть возможность вернуть результат
     * в переменную указав четвертый параметр в true.
     *
     *<pre>
     * Пример:
     * $this->setChunk("topSidebar", "blog/topSidebar", array( "var" => "value" ));
     *
     * в шаблон blog/topSidebar.php передаеться переменная $var с значением  "value".
     *
     * В необходимом месте основного вида или темы нужно обявить чанк
     * напрямую:
     * echo $this->chunk["topSidebar"];
     * или методом:
     * $this->chunk("topSidebar");
     *</pre>
     * @param string    $chunkName  зарегестрированое имя
     * @param string    $chunkView  путь у виду чанка
     * @param array     $dataChunk  передача данных в вид чанка
     * @param bool      $returned   по умочнию FALSE производится подключения в шаблон, если этот параметр TRUE возвращает контент
     * @return string
     */
    public function setChunk( $chunkName, $chunkView, array $dataChunk=null, $returned=false )
    {
        $viewInclude = PATH_APP_VIEWSPARTIALS.$chunkView.'.php';

        // Если вид чанка не существует отображается ошибка
        if(!file_exists($viewInclude)){
            errorFileExists($viewInclude);
            exit;
        }

        ob_start();
        ob_implicit_flush(false);

        if(!is_null($dataChunk))
            extract($dataChunk);

        include $viewInclude;

        if(!$returned)
            $this->chunk[$chunkName] = ob_get_clean();
        else
            return ob_get_clean();
    }

    /**
     * Вызов зарегестрированого чанка. Первый аргумент имя зарегестрированого чанка
     * второй тип возврата метода по умолчанию ECHO, если FALSE данные будет возвращены
     *
     * <pre>
     * Пример:
     *  $this->chunk("myChunk");
     * </pre>
     *
     * @param string    $chunkName
     * @param bool      $e
     * @return bool
     */
    public function chunk( $chunkName, $e=true )
    {
        if(isset($this->chunk[$chunkName])){
            if($e)
                echo $this->chunk[$chunkName];
            else
                return $this->chunk[$chunkName];
        }else{
            return null;
        }
    }

} 