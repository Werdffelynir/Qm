<?php

class Common
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
     * @var array Массив содержит зарегестрированые хуки
     */
    public static $_hookBind=array();

    /**
     * @var array Массив содержит зарегестрированые фильтры
     */
    private static $_filterBind = array();


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
     * @param  string    $chunkName
     * @param  bool      $e
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



    /**
     * Метод алис на статический метод loadClasses() класса App
     *
     * @param string    $fileName   Имя класса/файла
     * @param bool      $newObj     По умолчанию TRUE возвращает новый обект, FALSE только подключает
     * @return bool
     */
    public function classes( $fileName , $newObj=true)
    {
        return App::loadClasses( $fileName , $newObj);
    }

    /**
     * Метод алис на статический метод loadHelper() класса App
     *
     * @param string    $fileName   Имя класса/файла
     * @param bool      $newObj     По умолчанию TRUE возвращает новый обект, FALSE только подключает
     * @return bool
     */
    public function helper( $fileName,$newObj=true )
    {
        return App::loadHelper( $fileName,$newObj=true );
    }

    /**
     * Метод алис на статический метод loadExtension() класса App
     *
     * @param string    $fileName   Имя подключаемого файла
     * @return bool
     */
    public function ext( $fileName )
    {
        return App::loadExtension( $fileName );
    }


    /**
     * Метод алис на статический метод redirect() класса App
     *
     * @param string    $url    переадресация на URL
     * @param int       $delay  задержка
     * @param int       $code   код заголовка
     */
    public static function redirect($url, $delay = 0, $code = 302)
    {
        App::redirect($url, $delay, $code);
    }




    /**
     * Мнтод для проверки являеться ли запрос через AJAX
     * @return bool
     */
    public function isAjax()
    {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            return true;
        }else{
            return false;
        }
    }


    /**
     * Метод использования передаваемых парметров через строку запросов.
     * основное предназначение это передача неких параметров, но все же
     * можно найти множество других приминений для этого метода.
     *
     * <pre>
     * Например: http://qm.loc/edit/page/id/215/article/sun-light
     * /edit/page/ - это контролер и екшен, они пропускаються
     * $this->urlParam()            - id
     * $this->urlParam('id')        - 215
     * $this->urlParam('article')   - sun-light
     * $this->urlParam('allArray')  - масив всех елементов "Array ( [1] => edit [2] => page [3] => id [4] => 215 [5]..."
     * $this->urlParam('allString') - строку всех елеметов "edit/page/id/215/article/sun-light"
     * $this->urlParam('edit', 3)   - 215 (3 шага от 'edit')
     *
     * </pre>
     * @param bool $param
     * @param int $el
     * @return array|string
     */
    public function urlParam($param=false, $el=1)
    {
        if(!$param) {
            return App::$requestArray[3];
        }elseif($param == 'allArray'){
            return App::$requestArray;
        }elseif($param == 'allString'){
            return App::$request;
        }else{
            $paramTemp = substr(App::$request, strpos(App::$request, $param)+strlen($param)+1);
            $paramTemp = explode('/', $paramTemp);

            if($el > 0)
                return $paramTemp[$el-1];
            else
                return $param;
        }
    }


    /**
     * Метод для подключение моделей, параметром берет созданый раньше класс Метода
     * Возвращает обект модели с ресурсом подключеным к базе данных
     *
     * @param  string    $modelPath   Имя класса модели
     * @return bool|object
     */
    /*public function model( $modelName )
    {
        if( !class_exists($modelName) AND file_exists(PATH_APP_MODELS.$modelName.".php")) {
            include PATH_APP_MODELS.$modelName.".php";
            $newModel = new $modelName();
            return (object) $newModel;
        }else{
            return false;
        }
    }*/
    public function model( $modelPath )
    {
        if(file_exists(PATH_APP_MODELS.$modelPath.".php")) {

            if(strpos($modelPath, '/') === false){

                if(!class_exists($modelPath)){
                    include_once PATH_APP_MODELS.$modelPath.".php";
                }

                $newModel = new $modelPath();
                return (object) $newModel;

            }else{
                $modelArrayPath = explode('/',$modelPath);
                $modelName = $modelArrayPath[sizeof($modelArrayPath)-1];

                if(!class_exists($modelName)){
                    include_once PATH_APP_MODELS.$modelPath.".php";
                }

                $model = new $modelName();
                return (object) $model;
            }
        }else{
            return false;
        }
    }


    /**
     * Регистрация или вызов обработчиков событий, хука, в областе видемости. Первый аргумент
     * имя хука, второй анонимная функция или название метода в контролере, трений
     * задает аргументы для назначиного обработчика в втором аргументе. Если указан только первый
     * аргумент возвращает екземпляр этого хука, если имя не зарегестрировано возвращает NULL.
     *
     * <pre>
     * Пример:
     *  $this->hookRegister('hook-00', function(){ echo '$this->hookRegister'; });
     *  $this->hookRegister('hook-01', 'showEvent');
     *  $this->hookRegister('hook-02', 'showName', array('param1'));
     *  $this->hookRegister('hook-03', 'showNameTwo', array('param1','param2'));
     * </pre>
     *
     * @param string    $event      Название евента
     * @param null      $callback   Обработчик события обратного вызова
     * @param array     $params     Передаваемые параметры
     * @return array
     */
    public function hookRegister($event, $callback = null, array $params = array())
    {
        if (func_num_args() > 2) {
            self::$_hookBind[$event] = array($callback, $params);
        } elseif (func_num_args() > 1) {
            self::$_hookBind[$event] = array($callback);
        } else {
            return self::$_hookBind;
        }
    }

    /**
     * Тригер для зарегестрированого евента. Первый аргумент зарегестрированый ранее хук
     * методом hookRegister(), второй параметры для зарегестрированого обработчика.
     * Возвращает исключение в случае если хук не зарегестрирован
     *
     * <pre>
     * Пример:
     *  $this->hookTrigger('hook-01');
     *  $this->hookTrigger('hook-02', array('param1'));
     *  $this->hookTrigger('hook-03', array('param1','param2'));
     * </pre>
     *
     * @param string    $event
     * @param array     $params
     * @throws RuntimeException
     */
    public function hookTrigger($event, array $params = array())
    {

        if (isset(self::$_hookBind[$event]) AND $handlers = self::$_hookBind[$event]) {

            $handlersParam = (isset($handlers[1])) ? $handlers[1] : false;
            $handlersParam = (!empty($params)) ? $params : $handlersParam;

            if(is_callable($handlers[0])) {
                call_user_func($handlers[0]);
            } elseif(method_exists($this, $handlers[0]) AND $handlersParam) {
                call_user_func_array(array($this, $handlers[0]), $handlersParam);
            } elseif(method_exists($this, $handlers[0])) {
                call_user_func(array($this, $handlers[0]));
            } else
                throw new \RuntimeException('invalid callable');
        }
    }


    /**
     * @param $filterName
     * @param $callable
     * @param int $acceptedArgs
     */
    public function filterRegister($filterName, $callable, $acceptedArgs = 1){
        if(is_callable($callable)){
            self::$_filterBind[$filterName]['callable'] = $callable;
            self::$_filterBind[$filterName]['args'] = $acceptedArgs;
        }
    }

    /**
     * @param $filterName
     * @param $args
     * @throws Exception
     */
    public function filterTrigger($filterName, $args) {
        if(isset(self::$_filterBind[$filterName])) {
            if ( is_string($args) ) {
                call_user_func(array($this, self::$_filterBind[$filterName]['callable']), $args);
            }elseif( is_array($args) AND self::$_filterBind[$filterName]['args'] == sizeof($args) ){
                call_user_func_array(array($this, self::$_filterBind[$filterName]['callable']), $args);
            }
        } else {
            throw new Exception('invalid callable or invalid num arguments');
        }
    }



    /**
     * verification => ifExists , $function='isset-empty'
     * Метод проверки данных на существование или дугой тип
     *
     * @param string    $value      Данные что проверяем
     * @return null
     * @throws Exception
     */
    public function isExists($value){
        return (isset($value) AND !empty($value)) ? $value : null;
    }




} 