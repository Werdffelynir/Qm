<?php

class App{

    /** @var string полный url "http://my-site.loc/qmfarmework" */
    public static $url;
    /** @var string полный url "http://my-site.loc/qmfarmework" */
    public static $urlTheme;
    /** @var string полный url без приставки запроса http "my-site.loc/qmfarmework" */
    public static $urlStr;
    /** @var string url то что после домена "http://my-site.loc/qmfarmework" */
    public static $urlNude;
    /** @var string полный url безопасный */
    public static $urlHttps;
    /** @var string домен "my-site.loc" */
    public static $urlHost;

    /** @var array свойства конфигурации */
    public static $config = array();


    /** @var array массив запроса */
    public $route;
    /** @var array массив структур-модклей*/
    public $structures;


    /** @var string строка запроса, данные $route но в виде строки, от корня преложения*/
    public static $request;
    /** @var string строка запроса реальная, будет отличаться от $request при использувании "RouteReName" */
    public static $requestReal;
    /** @var string строка запроса, данные $route но в виде строки, полный от доменного имени*/
    public static $requestArray;


    /** @vars string системные свойства */
    private $_url;
    private $_requestUri;
    /** */
    private $_load;
    /**  @vars string files | folders Тип контролера */
    private $_typeController;

    public static $staticRouteReName  = array();
    public static $dynamicRouteReName = array();

    /** @var array Массив содержит зарегестрированые евенты */
    public static $_eventBind = array();

    /** @var array Массив содержит зарегестрированые фильтры */
    private static $_filterBind = array();

    /** @var array Массив содержит зарегестрированые флей сообщения */
    public static $_flashStorage = array();

	public function __construct()
	{
        /** Все конфигурации помещаються в статическое свойство */
        self::$config = QmConf();

        /* Определение местного url */
        $this->findUrl();

        /** Помещение всех возможно подключенных модулей, список с конф-файла в массив свойства.*/
        //if( $structures = self::$config["structureAutoload"] ){
        if(isset(self::$config["structureAutoload"])){
            $this->structures = self::$config["structureAutoload"];
        }

        /** Оброботка строки запроса, диление на роутинг */
        $requestUri = explode('/',$_SERVER['REQUEST_URI']);
        $scriptName = explode('/',$_SERVER['SCRIPT_NAME']);

        $requestElem = sizeof($scriptName);
        for($i= 0; $i < $requestElem; $i++){
            if ($requestUri[$i] == $scriptName[$i]){
                unset($requestUri[$i]);
            }
        }

        $_requestUriParse = array_values($requestUri);
        $requestUriParse = array_diff($_requestUriParse, array(''));

        self::$requestArray = $requestUri;
        self::$request = implode("/",$requestUriParse);

        /** Применения параметра "routeReName" если установлен */
        if(!empty(self::$config["routeReName"])){

            self::$staticRouteReName = self::$config["routeReName"];
            /** $nowName - искомая часть для замены на $toReName, поиск в self::$request
             * home => index/index
             * self::$request - в строке запроса ( home/about переобразит на index/index/about ) */
            foreach(self::$staticRouteReName as $nowName=>$toReName){
                if(strpos(self::$request,$nowName) > "-1"){
                    self::$requestReal = self::$request;
                    self::$request = str_ireplace($nowName, $toReName, self::$request);
                    $requestUriParse = explode("/", self::$request);
                    self::$requestArray = $requestUriParse;
                    continue;
                }
                /*if($nowName == self::$request) ... }*/
            }
        }

        $this->route = $requestUriParse;
	}


    public function findUrl()
    {
        $httpHost =  $_SERVER['HTTP_HOST'];

        $scrNamArr = explode("/", trim($_SERVER['SCRIPT_NAME']));
        array_pop($scrNamArr);

        $scrNamArr = array_filter( $scrNamArr, function($el){
                return !empty($el); }
        );

        $pathFolder = join('/',$scrNamArr);

        if(empty($pathFolder)){
            $httpHostFull = $httpHost;
        }else{
            $httpHostFull = $httpHost."/".$pathFolder;
        }

        self::$urlNude  = $pathFolder;
        self::$url      = "http://".$httpHostFull;
        self::$urlTheme = "http://".$httpHostFull."/".self::$config["nameApp"]."/viewstheme/".self::$config["nameTheme"];
        self::$urlStr   = $httpHostFull;
        self::$urlHttps = "https://".$httpHostFull;
        self::$urlHost  = $httpHost;
    }


    /**
     * Запускает весь процес обродотки
     */
    public function run()
    {

        $defaultControllerPrefix = self::$config['defaultControllerPrefix'];

        /** Извлечение первого елемента в роуте, это контролер илибо каталог структуры-модуля */
        $url = array_shift($this->route);

        /** Если присутствуют запрещенные сиволы. Установка в конфигурации */
        if(self::$config['safeUrl']){
            if (!preg_match("|^[a-zA-Z0-9\.,-_]*$|", $url)){
                throw new Exception("Invalid Path");
            }
        }

        /** Если включен дебагер, и запос идет на генератор кода подключаем его */
        if(self::$config['appMode'] == "debug" AND $url == "generator") {
            include PATH_SYS.'Generator/index.php';
            die();
        }

        /** Проверка являеться ли первый параметр одним из каталогов структуры-модуля обявленных в конфиг-файле */
        if ($structureFolder = $this->isStructureFile($url) AND isset($this->structures) ) {

            $urlControl = array_shift($this->route);

            /** Назначение возможного имени контроллера в стркутурном-модуле */
            $controllerStructureName = $defaultControllerPrefix . ucfirst($urlControl);

            /** Если файл существует, запускаем его */
            if (file_exists(PATH_APP_STRUCTURE . $structureFolder . DS . 'Controllers' . DS . $controllerStructureName . '.php')) {

                $this->runController($controllerStructureName, $structureFolder);

                /** Если файл НЕ существует, Запус по умолчанию, указаного в конфиг-файле  */
            } elseif (file_exists(PATH_APP_STRUCTURE . $structureFolder . DS . 'Controllers' . DS . $defaultControllerPrefix . 'Index' . '.php')) {

                $this->runController($defaultControllerPrefix . 'Index', $structureFolder);

            } else {

                /** Сначала нужно обратно положить роут */
                array_unshift($this->route, $url);
                /** Запуск контроллера по умолчанию */
                $this->runController($defaultControllerPrefix . ucfirst(self::$config['defaultController']));
            }

        /** Проверка на тип контролера, обявленного в конфиг-файле, каталог или файл */
        } elseif (App::$config["appHandlerType"]=='folders' AND $this->isFolderType($url) ) {

            $urlControl = array_shift($this->route);

            if (file_exists(PATH_APP_CONTROLLERS . $url . DS . $urlControl . '.php')) {

                $this->runController($urlControl, false, $url );

            } elseif (is_dir(PATH_APP_CONTROLLERS . $url)) {

                /** Сначала нужно обратно положить роут */
                array_unshift($this->route, $urlControl);
                /** Запуск контроллера по умолчанию по типу folders */
                $this->runController(self::$config['defaultControllerFolder'], false, $url );

            }else{

                /** Сначала нужно обратно положить роут */
                array_unshift($this->route, $urlControl);
                /** Запуск контроллера по умолчанию */
                $this->runController($defaultControllerPrefix . ucfirst(self::$config['defaultController']));
            }

        /** Обработка стандартного контролера */
        } else {
            /** Назначение возможного имени контроллера */
            $controllerName = $defaultControllerPrefix . ucfirst($url);

            /** Если файл контролера существует, запускаем его */
            if (file_exists(PATH_APP_CONTROLLERS.$controllerName . '.php')) {

                $this->runController($controllerName);

                /** Если файл НЕ существует, Запус по умолчанию, указаного в конфиг-файле  */
            } else {

                /** Сначала нужно обратно положить роут, дял дальнейших действий */
                array_unshift($this->route, $url);

                /** Запуск контроллера по умолчанию */
                $this->runController($defaultControllerPrefix . ucfirst(self::$config['defaultController']));
            }

        }

    } // END run()


    /**
     * Метод загружает Классы-Контролеры преложения.
     *
     * Или
     *
     * Если указан второй параметр метод загружает Классы-Контролеры модулей преложения, которые были
     * подписаны в конфиг-файле.
     *
     * @param $controllerName
     * @param bool $structure
     * @param bool $typeFolder
     */
    private function runController($controllerName, $structure = false, $typeFolder = false)
    {

        /** Проверка запущена ли структура или тип фолдер, структура должна быть назначена в конфигурации */
        if ($structure){
            include PATH_APP_STRUCTURE . $structure . DS . 'Controllers' . DS . $controllerName . '.php';
        }elseif($typeFolder){
            include PATH_APP_CONTROLLERS . $typeFolder . DS . $controllerName . '.php';
        }else{
            include PATH_APP_CONTROLLERS . $controllerName . '.php';
        }

        $controller = new $controllerName();
        $defaultMethodPrefix = self::$config['defaultMethodPrefix'];

        /** если это первая страницы без елементов, запуск метода по умолчанию*/
        if (empty($this->route) || empty($this->route[0])) {

            /** Подстановка префикса название метода*/
            $actionPrefix = $defaultMethodPrefix . 'Index';

            $controller->$actionPrefix();

        } else {

            /** если нет параметров*/
            if (empty($this->route)) {
                /** Подстановка префикса название метода*/
                $method = $defaultMethodPrefix . 'Index';

            } else {

                /** Выборка метода и подстановка префикса название метода*/
                $methodLive = array_shift($this->route);
                $method = $defaultMethodPrefix . $methodLive;
                //var_dump($methodLive);
            }

            /** Вызов необходимого класса и метода, если он существует*/
            if (method_exists((object)$controller, $method)) {

                if (empty($this->route)) {
                    $controller->$method();
                } else {
                    $controller->params = $this->route;
                    call_user_func_array(array($controller, $method), $this->route);
                }

            } else {
                /** Ничего не удалось найти с методом */
                if(file_exists(PATH_APP.'ViewsTheme/error404.php'))
                    include PATH_APP.'ViewsTheme/error404.php';
                else
                    include PATH_SYS.'Views/error404.php';
            }
        }
    }

    /**
     * Метод устанвлевает и запускает все указаные в настройках "runStartController" классы конролера

    private function runStartController()
    {
        if(!isset(self::$config['runStartController']))
            return false;
        $start = self::$config['runStartController'];

        if (!empty($start)) {
            if (is_array($start)) {
                foreach ($start as $controller) {
                    if (file_exists(PATH_APP_CONTROLLERS . $controller . ".php"))
                        include PATH_APP_CONTROLLERS . $controller . ".php";
                }
            } elseif (is_string($start)) {
                if (file_exists(PATH_APP_CONTROLLERS . $start . ".php"))
                    include PATH_APP_CONTROLLERS . $start . ".php";
            }
        }
    } */


    /**
     * Метод для проверки являеться ли строка именем одного из каталога структуры-модуля
     * Например: ('admin' == 'controller')
     *
     * @param $fileName
     * @return bool
     */
    private function isStructureFile($fileName)
    {

        if (!empty($this->structures)) {
            $checkFile = false;
            $structures = (array)$this->structures;
            foreach ($structures["structure"] as $structure) {

                if ($structure == $fileName) {
                    $checkFile = true;
                    return $structure;
                }
            }
            if (!$checkFile)
                return false;
        } else {
            return false;
        }
    }

    /**
     * @param $folder
     * @return bool
     */
    private function isFolderType($folder)
    {
        if (file_exists(PATH_APP_CONTROLLERS.$folder)) return true;
        else return false;
    }


    /**
     * Метод позволяет подгружать классы / файлы "хелпера" что не указаны для автозагрузки.
     * Возвращает новый обект или FALSE в случании если класс указан в автозагрузке или не существует
     *
     * @param string    $fileName   Имя класса
     * @param bool      $newObj     Создание нового обекта
     * @return bool
     */
    public static function loadClasses($fileName, $newObj=true)
    {
        if(in_array($fileName, self::$config["classesAutoload"]["classes"])){
            return new $fileName();
        }elseif(file_exists(PATH_APP_CLASSES.$fileName.'.php')){
            include PATH_APP_CLASSES.$fileName.'.php';
            if($newObj)
                return new $fileName();
        }else{
            return false;
        }
    }


    /**
     * Метод позволяет подгружать классы / файлы "хелпера" что не указаны для автозагрузки
     * Возвращает новый обект или FALSE в случании если класс указан в автозагрузке или не существует
     *
     * @param string    $fileName   Имя класса
     * @param bool      $newObj     Создание нового обекта
     * @return bool
     */
    public static function loadHelper($fileName, $newObj=true)
    {
        if(in_array($fileName, self::$config["helpersAutoload"]["classes"])){
            return new $fileName();
        }elseif(file_exists(PATH_APP_HELPERS.$fileName.'.php')){
            include PATH_APP_HELPERS.$fileName.'.php';
            if($newObj)
                return new $fileName();
        }else{
            return false;
        }
    }


    /**
     *  Метод подгружает классы / файлы "расширения" что не указаны для автозагрузке
     * Возвращает FALSE если класс / файл не существует
     *
     * @param   string    $extPath   Имя класса
     * @return  bool
     */
    public static function loadExtension($extPath)
    {
        if(in_array($extPath, self::$config["extensionAutoload"]["classes"])){
            return true;
        }elseif(file_exists(PATH_APP_EXTENSION.$extPath.'.php')){
            include PATH_APP_EXTENSION.$extPath.'.php';
        }
    }


    /** ********************************************************************************************************
     **
     **                                         STATIC METHODS
     **
     ********************************************************************************************************** */

    /**
     * @param $classPath
     * @param bool $include
     * @return bool
     */
    public static function createObj($classPath, $include=false)
    {
        if (file_exists(PATH_APP. $classPath . '.php')) {

            /*if($classPosition = strrpos("/", $classPath)){
                $className = substr($classPath, $classPosition+strlen($classPosition));
            }*/

            if($include){
                include PATH_APP. $classPath . '.php';
            }
            return new $classPath();

        } else {
            return false;
        }
    }


    /**
     * Перенаправление по указаному роуту, относитльный URL. Метод настраивает ответ с кодом HTTP и время задержки.
     * Если время задержки задано, эта функция всегда будет возвращать логическое TRUE,
     * Если заголовок уже прошел код всеравно будет остановлен halt()
     *
     * @param string    $url    Переадресация на URL
     * @param int       $delay  Редирек с задержкой с секунднах
     * @param int       $code   HTTP код заголовка; по умолчанию 302
     * @return bool
     */
    public static function redirect($url, $delay = 0, $code = 302)
    {
        if (!headers_sent()){
            if ($delay)
                header('Refresh: ' . $delay . '; url=' . $url, true);
            else
                header('Location: ' . $url, true, $code);
            exit();
        }else{
            return false;
        }
    }


    /**
     * Пренудительный редирект, обходит отправленые заголовки
     *
     * @param string    $url    Переадресация на URL
     */
    public static function redirectForce($url)
    {
        if (!headers_sent()) {
            header('Location: ' . $url);
        } else {
            // echo "<script>document.location.href='".$url."';</script>\n";
            // echo "<noscript><meta http-equiv='refresh' content='0; url='".$url."' /></noscript>\n";
            echo "<html><head><title>REDIRECT</title></head><body>";
            echo '<script type="text/javascript">';
            echo 'window.location.href="'.$url.'";';
            echo '</script>';
            echo '<noscript>';
            echo '<meta http-equiv="refresh" content="0; url='.$url.'" />';
            echo '</noscript>';
            echo "</body></html>";
        }
        echo "Headers already!\n";
        echo "</body></html>";
        exit;
    }



/** ********************************************************************************************************
    EVENTS
********************************************************************************************************** */
     
    /**
     * Runs a callable with an array of arguments. Throws a RuntimeError on invalid callables.
     * Запуск переданой Callable функции с агрументами если переданы. Callable не являеться
     * сallable возвращает исключение.
     *
     * @param callable $callable    Callable функция
     * @param array    $args        Аргументы для Callable функции
     * @return mixed Выполнения Callable функции
     * @throws RuntimeException
     */
    public static function apply($callable, $args = array())
    {
        if (!is_callable($callable)) {
            throw new \RuntimeException('invalid callable');
        }
        return call_user_func_array($callable, $args);
    }

    /**
     * Вызывает Callable с переменными аргументами
     *
     * @param callable $callable Callable функция
     * @param mixed    $arg      Аргументы для Callable функции
     * @param mixed    $arg,...  Неограниченое количество аргументов
     *
     * @return mixed Выполнения Callable функции
     */
    public static function call($callable, $arg = null)
    {
        $args = func_get_args();
        $callable = array_shift($args);
        return self::apply($callable, $args);
    }


    /**
     *
     * Регистрация или вызов обработчиков событий, в областе видемости. Первый аргумент
     * имя хука, второй анонимная функция или иной колбек название метода к примеру.
     * Если указан только первый аргумент возвращает екземпляр этого события,
     * если имя не зарегестрировано возвращает NULL.
     *
     * <pre>
     * Пример:
     *  App::eventRegister('e-01', function(){ echo "Code..."; });
     *  App::eventRegister('e-02', array($this, 'method'));
     *  App::eventRegister('e-03', array('Class', 'method'));
     * </pre>
     *
     * @param string   $event    Название евента
     * @param callable $callback Обработчик события обратного вызова
     *
     * @return mixed
     */
    public static function eventRegister($event = null, $callback = null)
    {
        if (func_num_args() > 1) {
            self::$_eventBind[$event][] = $callback;
        } elseif (func_num_args()) {
            return isset(self::$_eventBind[$event]) ? self::$_eventBind[$event] : null;
        } else {
            return self::$_eventBind;
        }
    }


    /**
     * Тригер для зарегестрированого евента
     *
     * <pre>
     * Пример:
     *  App::eventTrigger('e-02');
     *  App::eventTrigger('e-03', array('param1'));
     *  App::eventTrigger('e-04', array('param1','param2'));
     * </pre>
     *
     * @param string    $event  Название евента
     * @param array     $args   Передаваемые параметры
     */
    public static function eventTrigger($event, array $args = array())
    {
        //var_dump(self::eventRegister($event));
        if ($handlers = self::eventRegister($event)) {
            foreach ($handlers as $callback) {
                self::apply($callback, $args);
            }
        }
    }

    /** **************************************************
    FILTER
     *************************************************** */


    /**
     * @param $filterName
     * @param $callable
     * @param int $acceptedArgs
     */
    public static function filterRegister($filterName, $callable, $acceptedArgs = 1)
    {
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
    public static function filterTrigger($filterName, $args) {
        if(isset(self::$_filterBind[$filterName])) {
            if ( is_string($args) ) {
                call_user_func(self::$_filterBind[$filterName]['callable'], $args);
            }elseif( is_array($args) AND self::$_filterBind[$filterName]['args'] == sizeof($args) ){
                call_user_func_array(self::$_filterBind[$filterName]['callable'], $args);
            }
        } else {
            throw new Exception('invalid callable or invalid num arguments');
        }
    }



/** ********************************************************************************************************
    FLASH SESSION STORAGE
********************************************************************************************************** */


    /**
     * Метод регистрации и вывода флеш сообщений в виде массива после перезагрузки страницы,
     * если указан первый аргумент производится вывод ранее записаного сообщения, если
     * указано два аргумента сообщение записуеться.
     * Если аргкменты не указаны выводит все имеющие записи
     *
     * <pre>
     * Регистрация сообщения:
     * App::flashArray('update', array('type'=>'success','message'=>'Запись в базе данных успешно обновлена!','class'=>'fleshsuccess'));
     * Вывод после переадрисации:
     * App::flashArray('update');
     * результатом будет массив.
     * </pre>
     *
     * @param null|string   $key    Ключ флеш сообщения
     * @param null|array    $value  Массив с данными для передаччи
     * @return mixed
     */
    public static function flashArray($key = null, $value = null)
    {
        if (!isset($_SESSION)) session_start();
        $flash = 'qm_flash';

        if (func_num_args() > 1) {
            $flashMessage = isset($_SESSION[$flash][$key]) ? $_SESSION[$flash][$key] : null;
            $_SESSION[$flash][$key] = serialize($value);
            self::$_flashStorage[$key] = serialize($value);
            return unserialize($flashMessage);
        } elseif (func_num_args()) {
            $flashMessage = isset($_SESSION[$flash][$key]) ? $_SESSION[$flash][$key] : null;
            unset(self::$_flashStorage[$key]);
            unset( $_SESSION[$flash][$key] );
            return unserialize($flashMessage);
        } else {
            return unserialize(self::$_flashStorage);
        }
    }

    /**
     * Выводит или регистрирует флеш сообщения для даной страницы или следующей переадрисации.
     * Указать два аргумента для регистрации сообщения, один для вывода. Если указать претий аргумент
     * в FALSE, сообщение будет удалено поле первого вывода.
     *
     * <pre>
     * Регистрация сообщения:
     * App::flash('edit','Запись в базе данных успешно обновлена!');
     * Вывод после переадрисации:
     * App::flash('edit');
     * </pre>
     *
     * @param string    $key    Ключ флеш сообщения
     * @param mixed     $value  Значение
     * @param bool      $keep   Продлить существования сообщения до следущего реквкста; по умолчанию TRUE
     *
     * @return mixed
     */
    public static function flash($key = null, $value = null, $keep = true)
    {
        if (!isset($_SESSION)) session_start();
        $flash = 'qm_flash';

        if (func_num_args() > 1) {
            $old = isset($_SESSION[$flash][$key]) ? $_SESSION[$flash][$key] : null;
            if (isset($value)) {
                $_SESSION[$flash][$key] = $value;
                if ($keep) {
                    self::$_flashStorage[$key] = $value;
                } else {
                    unset(self::$_flashStorage[$key]);
                }
            } else {
                unset(self::$_flashStorage[$key]);
                unset( $_SESSION[$flash][$key] );
            }
            return $old;
        } elseif (func_num_args()) {
            $flashMessage = isset($_SESSION[$flash][$key]) ? $_SESSION[$flash][$key] : null;
//            unset(self::$_flashStorage[$key]);
//            unset( $_SESSION[$flash][$key] );
            return $flashMessage;
        } else {
            return self::$_flashStorage;
        }
    }


} // END class App