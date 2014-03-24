<?php
/**
 * MVC PHP Framework Quick Minimalism
 * Version: 0.3.0
 * File:    App.php
 * Author:  OL Werdffelynir [http://w-code.ru] [oleg@w-code.ru]
 * Date:    11.03.14
 * Docs:    http://qm.w-code.ru
 *
 * Ядро системы, основная обработка запросов, путей, сруктуры..
 */

namespace Core;


class App
{

    /**
     * @var array массив с сгенерироваными видами URL к приложегнию
     * App::$getURL['base'] полный url "http://my-site.loc/qmfarmework"
     * App::$getURL['public'] полный url "http://my-site.loc/qmfarmework/public"
     * App::$getURL['str'] полный url без приставки запроса http "my-site.loc/qmfarmework"
     * App::$getURL['nude'] часть url что после домена "qmfarmework/public"
     * App::$getURL['safe'] полный url безопасный
     * App::$getURL['host'] домен "my-site.loc"
     */
    public static $getURL=array();

    /** @var array свойства конфигурации */
    public static $config = array();

    /**
     * @var string $langCode Установка языка 'ru', 'en' например
     * @var array $langData Днные перевода
     * @var bool $identifyLan Определять язык клиента по умолчанию true
     */
    public static $langCode = null;
    public static $langData = null;
    protected static $identifyLan = true;

    /**
     * @var instance
     */
    protected static $instance;

    /**
     * Содержит елементы роутера активных запросов приложения
     * @var array $router
     * App::$router['request']
     * App::$router['slug']
     * App::$router['controllerFolder']
     * App::$router['controller']
     * App::$router['method']
     * App::$router['args']
     * App::$router['argsReg']
     */
    public static $router = array();
    


    /**
     * Срок хранения кук
     * @var array $expireCookTime Срок хранения кук
     * @var array $decodeCook Куки, кодировать значения
     */
    protected static $expireCookTime;
    protected static $decodeCook = true;

    private $_regex;
    public static $debug;
    public static $autoloadFiles = array();

    /**
     * Action properties. Системное хранение данных хуков и фильтров
     * @var array $_hookBind        Массив содержит зарегестрированые хуки
     * @var array $_filterBind      Массив содержит зарегестрированые фильтры
     * @var array $_flashStorage    Массив содержит зарегестрированые флеш сообщения
     */
    public static $_hookBind = array();
    private static $_filterBind = array();
    private static $_flashStorage = array();

    
    private function __construct(){}

    /**Closed methods*/
    private function __clone(){}
    private function __wakeup(){}

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
            return self::$instance;
        }
        return self::$instance;
    }

    public function init()
    {
        /* Определение местного url */
        $this->findUrl();

        // Определения URL параметров с запроса
        $basePath = (isset(self::$config['basePath']))? $basePath = self::$config['basePath']:false;
        if($basePath){
            if(strlen($basePath) < 2 ){
                $parts = array_diff(explode('/', $_SERVER['REQUEST_URI']), array(''));
            }else{
                $parts = array_diff(explode('/', str_replace($basePath, '', $_SERVER['REQUEST_URI'])), array(''));
            }
        }elseif((self::$config['appDir'] != self::$getURL['host'])){
            $parts = explode('/', $_SERVER['REQUEST_URI']);
            foreach ($parts as $k => $v) {
                if ($v != self::$config['appDir']) {
                    unset($parts[$k]);
                } else {
                    unset($parts[$k]);
                    break;
                }
            }
        }else
            $parts = array_diff(explode('/', $_SERVER['REQUEST_URI']),array(''));
        
        $parts = array_values($parts);

        // bind system router array
        self::$router = array(
            'controllerFolder' => false,
            'argsReg' => false,
        );

        // Применения параметра "routeType" если установлен по переименованию, 
        // выполняеться если в конфигурации выставлин параметр "routeType"=>'simple';
        // дальнейшая обработка выполняеться по умолчанию как и без этого параметра,
        // при типе 'regex' обработка происходит в методе runController()
        if (self::$config["routeType"] == 'simple') {
            $partsLine = join('/', $parts);
            foreach (self::$config["routeReName"] as $reOnce) {
                if (strpos($partsLine, $reOnce[0]) > "-1") {
                    $priority = (isset($reOnce[3])) ? $reOnce[3] : 1;
                    $routerArray[$priority] = array($reOnce[0], $reOnce[1]);
                }
            }
            if (!empty($routerArray)) {
                ksort($routerArray);
                $replace = array_shift($routerArray);
                $partsLineReplace = str_ireplace($replace[0], $replace[1], $partsLine);
                $parts = explode('/', $partsLineReplace);
            }

        } elseif (self::$config["routeType"] == 'regex') {

            $partsLine = join('/', $parts);
            foreach (self::$config["routeReName"] as $reOnce) {
                $priority = (isset($reOnce[2])) ? $reOnce[2] : 10;
                $this->routeRegEx($reOnce[0], array($reOnce[1][0], $reOnce[1][1]), $priority);
                if (preg_match($this->_regex['reg'], $partsLine, $matches)) {
                    $routerArray[$this->_regex['priority']]['callback'] = $this->_regex['callback'];
                    $routerArray[$this->_regex['priority']]['argsReg'] = $matches;
                }
            }
            if (!empty($routerArray)) {
                krsort($routerArray);
                $_parts = array_shift($routerArray);
                $parts = $_parts['callback'];
                self::$router['argsReg'] = $_parts['argsReg'];
            }
        }

        // Назначение роутов в защищенные массив, используеться только ядром
        self::$router['request'] = (is_array($parts)) ? $parts : false;
        self::$router['slug'] = join('/', $parts);
        // По типу структуры определение 
        self::$router['controllerFolder'] = '';
        if (self::$config['handlerType'] == 'folder') {
            self::$router['controllerFolder'] = ($c = array_shift($parts)) ? $c : self::$config['defaultControllerFolder'];
            self::$router['controller'] = ($c = array_shift($parts)) ? $c : self::$config['defaultController'];
        } else {
            self::$router['controller'] = ($c = array_shift($parts)) ? $c : self::$config['defaultController'];
        }
        self::$router['method'] = ($m = array_shift($parts)) ? $m : self::$config['defaultMethod'];
        self::$router['args'] = (isset($parts)) ? $parts : array();
     

        // bind includes files array indexes
        self::$autoloadFiles['helpers'] = array();
        self::$autoloadFiles['classes'] = array();
        self::$autoloadFiles['extensions'] = array();
        self::$autoloadFiles['other'] = array();

        // run auto includes files
        $this->autoloadFiles();

        // lang install settings.
        // Еязык не установлен
        // Сначала определяеться язык записаный к куки
        // или определяеться по браузеру
        // или берется с конфигурации
        // или выставляеться по умолчанию
        if (self::$langCode == null) {
            if (App::getCookie('lang') != null) {
                self::$langCode = App::getCookie('lang');
            } elseif (self::$identifyLan) {
                self::$langCode = strtolower(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2));
            } elseif (isset(App::$config['language'])) {
                self::$langCode = strtolower(App::$config['language']);
            } else {
                self::$langCode = 'ru';
            }
        }
        
        // run controller searcher and loader
        $this->runController();
    }


    /**
     * Запуск запрашиемого контролера
     */
    private function runController()
    {
        // Префиксы для запрашиваемых
        $prefix['controller'] = (self::$config['defaultControllerPrefix']) ? self::$config['defaultControllerPrefix'] : 'Controller';
        $prefix['method'] = (self::$config['defaultMethodPrefix']) ? self::$config['defaultMethodPrefix'] : 'action';

        // По типу структуры определение 
        if (self::$config['handlerType'] == 'folder') {
            // Опредиление возможных имен и путей
            $controllerFolder = ucfirst(self::$router['controllerFolder']);
            $controller = ucfirst(self::$router['controller']);
            $method = $prefix['method'] . ucfirst(self::$router['method']);
            $cPath = PATH_APP . 'Controllers' . DS . $controllerFolder . DS . $controller . '.php';
        } else {
            // Опредиление возможных имен и путей
            $controller = $prefix['controller'] . ucfirst(self::$router['controller']);
            $method = $prefix['method'] . ucfirst(self::$router['method']);
            $cPath = PATH_APP . 'Controllers' . DS . $controller . '.php';
        }

        if (file_exists($cPath)) {

            include $cPath;

            // вывод страницы 404. Класс не определен в файле
            if (!class_exists($controller)){
                if(self::$debug)
                    self::ExceptionError('Error 404. Page not found! Class <b>' . $controller . '{}</b> not exists in to file <b>' . $cPath . '</b>!');
                else
                    self::inclFile(PATH_PUBLIC.self::$config['Error 404']);
            }

            // Создание екземпляра класса
            $controllerObj = new $controller();

            // Назначения возможных actions с контролера
            $actions = $controllerObj->actions();

            if (method_exists((object)$controllerObj, $method)) {
                if (empty(self::$router['args'])) {
                    $controllerObj->$method();
                } else {
                    call_user_func_array(array($controllerObj, $method), self::$router['args']);
                }

                // Проверка на существование actions
            } elseif (!empty($actions) AND array_key_exists(self::$router['method'], $actions)) {
                // если actions в контролере определен и файл вызова существует вызываем его
                $actionPath = PATH_APP . $actions[self::$router['method']] . '.php';
                if (file_exists($actionPath)) {
                    require_once($actionPath);
                } else {
                    // вывод страницы 404. Actions не найден
                    if(self::$debug){
                        self::ExceptionError('Error 404. Page not found! Actions request <b>[' . self::$router['method'] . ']</b> file <b> ' . $cationPath . '()</b> not exist!');
                    } else {
                        self::inclFile(PATH_PUBLIC.self::$config['Error 404']);
                    }
                }
            } else {
                // вывод страницы 404. Метод не найден
                if(self::$debug)
                    self::ExceptionError('Error 404. Page not found! Method <b>function ' . $method . '()</b> not exists!');
                else
                    self::show404();
            }

        } else {
            // вывод страницы 404. Файла контролера нет.
            if(self::$debug)
                self::ExceptionError('Error 404. Page not found! Controller file <b>' . $cPath . '</b> not exists!');
            else
                self::inclFile(PATH_PUBLIC.self::$config['Error 404']);
        }

    }


    // Keep the original routing rule for debugging/unit tests
    // Custom capture, format: <:var_name|regex>
    // Alphanumeric capture (0-9A-Za-z-_), format: <:var_name>
    // Numeric capture (0-9), format: <#var_name>
    // Wildcard capture (Anything INCLUDING directory separators), format: <*var_name>
    // Wildcard capture (Anything EXCLUDING directory separators), format: <!var_name>
    // Add the regular expression syntax to make sure we do a full match or no match
    public function routeRegEx($route, $callback, $priority = 10)
    {
        $route = preg_replace('/\<\:(.*?)\|(.*?)\>/', '(?P<\1>\2)', $route);
        $route = preg_replace('/\<\:(.*?)\>/', '(?P<\1>[A-Za-z0-9\-\_]+)', $route);
        $route = preg_replace('/\<\#(.*?)\>/', '(?P<\1>[0-9]+)', $route);
        $route = preg_replace('/\<\*(.*?)\>/', '(?P<\1>.+)', $route);
        $route = preg_replace('/\<\!(.*?)\>/', '(?P<\1>[^\/]+)', $route);
        $route = '#^' . $route . '$#';
        $this->_regex['reg'] = $route;
        $this->_regex['priority'] = $priority;
        $this->_regex['callback'] = $callback;
        return true;
    }


    /** Все конфигурации помещаються в статическое свойство */
    public function setConfig($config)
    {
        //присвоение конфигурации статик-свойству
        self::$config = $config;

        //опредиление путей
        $appPathInfo = pathinfo($config['path']);
        self::$config['appPath'] = $appPathInfo['dirname'] . DS . $appPathInfo['basename'] . DS;
        self::$config['appDir'] = $appPathInfo['basename'];

        //опредиление настроек
        self::$debug = self::$config['debug'];
        self::$config['Error 404'] = (isset(self::$config['Error 404']))?self::$config['Error 404']:false;
        // Установка определения языка
        if (isset(self::$config['identifyLanguage']))
            self::$identifyLan = self::$config['identifyLanguage'];
        self::$expireCookTime = 600;
    }


    /**
     * Определение местного URL
     */
    private function findUrl()
    {
        $httpHost = $_SERVER['HTTP_HOST'];

        if(!isset(self::$config['baseUrl'])){

            $scrNamArr = explode("/", trim($_SERVER['SCRIPT_NAME']));
            array_pop($scrNamArr);

            $scrNamArr = array_filter($scrNamArr, function ($el) {
                    return !empty($el);
                }
            );

            $pathFolder = join('/', $scrNamArr);

            if (empty($pathFolder)) {
                $httpHostFull = $httpHost;
            } else {
                $httpHostFull = $httpHost . "/" . $pathFolder;
            }

        }else{
            $pathFolder = '';
            $httpHostFull = self::$config['baseUrl'];
        }

        self::$getURL=array(
            'nude'=>$pathFolder,
            'base'=>"http://" . $httpHostFull,
            'public'=>"http://" . $httpHostFull . "/public",
            'str'=>$httpHostFull,
            'safe'=>"https://" . $httpHostFull,
            'host'=>$httpHost,
        );

    }

    /**
     * Метод вызова ошибки, отладчик
     *
     * @param string $errorMsg Сообщения о ошибке
     * @param null $fileName Конкретные данные, например имя файла
     * @param bool $die
     */
    public static function ExceptionError($errorMsg = 'File not exists', $fileName = null, $die = true)
    {
        try {
            throw new \Exception("TRUE.");
        } catch (\Exception $e) {
            echo "

<div style='padding: 10px; font-family: \"Aeromatics\", Arial, Helvetica, sans-serif; font-size: 11px; color:#FFF; background: #3C3F41; text-align:left;'>

    <h2 style='font-size: 14px; color:#FF9900;'>Warning! throw Exception. </h2>

    <h2>Message: " . $errorMsg . " </h2>";

            if ($fileName != null):
                echo "<code style='display: block; padding: 10px; font-size: 12px; font-weight: bold; font-family: Consolas, Courier New, monospace; color:#CBFEFF; background: #2B2B2B'>"
                    . $fileName .
                    "</code>";
            endif;

            echo "<div style='display: block; padding: 10px; color:#828282; '>
        Function setup: " . $e->getFile() . "
        <br>
        Line: " . $e->getLine() . "
    </div>

    <h3>Trace As String: </h3>
    <code style='display: block; padding: 10px; font-size: 12px; font-weight: bold; font-family: Consolas, Courier New, monospace; color:#CBFEFF; background: #2B2B2B'>
        " . str_replace('#', '<br>#', $e->getTraceAsString()) . "<br>
    </code>

    <h3>Code: </h3>
    <code style='display: block; padding: 10px; font-size: 12px; font-weight: bold; font-family: Consolas, Courier New, monospace; color:#CBFEFF;  background: #2B2B2B'>
        " . $e->getCode() . "
    </code>

</div>";
            if ($die)
                die();
        }
    }


    /**
     * Автозагрузка файлов хелперов, файлов Классов, файлов Расширений
     * указаных в конфиг настройка приложения
     */
    private static function autoloadFiles()
    {
        // Include custom file application 'functions.php',
        if(file_exists(PATH_APP.'functions.php'))
            include( PATH_APP.'functions.php' );

        if (!empty(self::$config['helpersFilesAutoload'])) {
            foreach (self::$config['helpersFilesAutoload'] as $hFile) {
                App::$autoloadFiles['helpers'][] = $hFile;
                if (file_exists(PATH_APP.'Helpers/'.$hFile.'.php'))
                    include PATH_APP.'Helpers/'.$hFile.'.php';
            }
        }
        if (!empty(self::$config['classesFilesAutoload'])) {
            foreach (self::$config['classesFilesAutoload'] as $cFile) {
                App::$autoloadFiles['classes'][] = $cFile;
                if (file_exists(PATH_APP.'Classes/'.$cFile.'.php'))
                    include PATH_APP.'Classes/'.$cFile.'.php';
            }
        }
        if (!empty(self::$config['extensionsFilesAutoload'])) {
           foreach (self::$config['extensionsFilesAutoload'] as $eFile) {
                App::$autoloadFiles['extensions'][] = $eFile;
                if (file_exists(PATH_APP.'Extensions/'.$eFile.'.php'))
                    include PATH_APP.'Extensions/'.$eFile.'.php';
            }
        }
    }


    /**
     * Подключение файлов что не подписаны на автозагрузку 'autoloadFiles' или с иных директорий
     *
     * <pre>
     * \Core\App::inclFile('File1','h'); || \Core\App::inclFile('File1','helper');
     * \Core\App::inclFile(array('File1','File2','File3',),'c');
     *
     * \Core\App::inclFile(_PATH_.'File1');
     * \Core\App::inclFile(array(_PATH_.'File1',_PATH.'File2',_PATH_.'File3',));
     * </pre>
     *
     * @param string|array $file имя файла или путь к файлу с иминем без расширения
     * @param bool $directory доступны директории 'helper' alis 'h', 'class' alis 'c', 'extension' alis 'e',
     * @param string $exe расширение файла, по умолчанию '.php'
     * @return bool
     */
    public static function inclFile($file, $directory = false, $exe = '.php')
    {
        $directory = ($directory) ? strtolower($directory) : false;

        if (!$directory) {
            if (is_string($file)) {
                if (in_array($file, self::$autoloadFiles['other'])) {
                    return true;
                } else {
                    if (file_exists($file . $exe)) {
                        include $file . $exe;
                        self::$autoloadFiles['other'][] = $file;
                        return true;
                    } else {
                        die('File <b>' . $file . '.php</b> dont exists!');
                    }
                }
            } elseif (is_array($file)) {
                foreach ($file as $fileOne) {
                    if (in_array($fileOne, self::$autoloadFiles['other'])) {
                        return true;
                    } else {
                        if (file_exists($fileOne . $exe)) {
                            include $fileOne . $exe;
                            self::$autoloadFiles['other'][] = $fileOne;
                        } else {
                            die('File <b>' . $fileOne . '.php</b> dont exists!');
                        }
                    }
                }
            }

        } elseif (is_string($directory)) {
            if ($directory == 'h' || $directory == 'helper') {
                if (in_array($file, self::$autoloadFiles['helpers'])) {
                    return true;
                } else {
                    if (file_exists(PATH_APP . 'Helpers' . DS . $file . '.php')) {
                        include PATH_APP . 'Helpers' . DS . $file . '.php';
                        self::$autoloadFiles['helpers'][] = $file;
                        return true;
                    } else {
                        die('Helper file <b>' . $file . '.php</b> dont exists!');
                    }
                }
            } elseif ($directory == 'c' || $directory == 'class') {
                if (in_array($file, self::$autoloadFiles['classes'])) {
                    return true;
                } else {
                    if (file_exists(PATH_APP . 'Classes' . DS . $file . '.php')) {
                        include PATH_APP . 'Classes' . DS . $file . '.php';
                        self::$autoloadFiles['classes'][] = $file;
                        return true;
                    } else {
                        die('Helper file <b>' . $file . '.php</b> dont exists!');
                    }
                }
            } elseif ($directory == 'e' || $directory == 'extension') {
                if (in_array($file, self::$autoloadFiles['extensions'])) {
                    return true;
                } else {
                    if (file_exists(PATH_APP . 'Extensions' . DS . $file . '.php')) {
                        include PATH_APP . 'Extensions' . DS . $file . '.php';
                        self::$autoloadFiles['extensions'][] = $file;
                        return true;
                    } else {
                        die('Extension file <b>' . $file . '.php</b> dont exists!');
                    }
                }
            }
        } elseif (is_array($directory)) {
            if ($directory == 'h' || $directory == 'helper') {

                foreach ($file as $fileOne) {
                    if (in_array($fileOne, self::$autoloadFiles['helpers'])) {
                        return true;
                    } else {
                        if (file_exists($fileOne . $exe)) {
                            include $fileOne . $exe;
                            self::$autoloadFiles['helpers'][] = $fileOne;
                        } else {
                            die('Helper <b>' . $fileOne . '.php</b> dont exists!');
                        }
                    }
                }

            } elseif ($directory == 'c' || $directory == 'class') {

                foreach ($file as $fileOne) {
                    if (in_array($fileOne, self::$autoloadFiles['classes'])) {
                        return true;
                    } else {
                        if (file_exists($fileOne . $exe)) {
                            include $fileOne . $exe;
                            self::$autoloadFiles['classes'][] = $fileOne;
                        } else {
                            die('File in directory Classes <b>' . $fileOne . '.php</b> dont exists!');
                        }
                    }
                }

            } elseif ($directory == 'e' || $directory == 'extension') {

                foreach ($file as $fileOne) {
                    if (in_array($fileOne, self::$autoloadFiles['extensions'])) {
                        return true;
                    } else {
                        if (file_exists($fileOne . $exe)) {
                            include $fileOne . $exe;
                            self::$autoloadFiles['extensions'][] = $fileOne;
                        } else {
                            die('File in directory Extensions <b>' . $fileOne . '.php</b> dont exists!');
                        }
                    }
                }
            }
        }
    }


    /**
     * Инициализация языквого файла, установка языковых параметров к cookies, переназначение языка
     * Применять для изминения языка приложения
     *
     * @param bool $langCode
     * @param bool $cookie
     */
    public static function initLang($langCode = false, $cookie = false)
    {
        // Обновление параметра
        if ($langCode) {
            self::$langCode = $langCode;
        } else {
            $langCode = self::$langCode;
        }

        // Пишем в кукисы, если ее не существует
        if ($cookie OR self::getCookie('lang') == null)
            self::addCookie('lang', $langCode);

        $file = PATH_APP . 'Languages' . DS . $langCode . '.php';

        if (file_exists($file)) {
            $langData = include $file;

            self::$langData = array(
                'name' => $langData['name'],
                'code' => $langData['code'],
                'image' => $langData['image'],
                'words' => $langData['words']
            );

        } else
            if (self::$debug)
                self::ExceptionError('Language file not exists!. <b>' . $file . '</b>');

    }


    /**
     * Достать перевод, или параметр с языкового файла,
     * находится в каталоге приложения ./Languages/ru.php например
     *
     * @param   string  $key    Ключ массива перевода
     * @param   bool    $e
     * @return  null
     */
    public static function lang($key, $e = false)
    {
        if ($key == 'name')
            return self::$langData['name'];
        if ($key == 'code')
            return self::$langData['code'];
        if ($key == 'image')
            return self::$langData['image'];

        if ($e) echo (isset(self::$langData['words'][$key])) ? self::$langData['words'][$key] : "{ $key }";
        else return (isset(self::$langData['words'][$key])) ? self::$langData['words'][$key] : null;
    }

    public static function mergeLang(array $addData)
    {
        if(!empty(self::$langData)){
            self::$langData['words'] = array_merge(self::$langData['words'],$addData);
        }else{
            if (self::$debug)
                self::ExceptionError('Language file not exists!. <b>' . $file . '</b>');
            else
                return false;
        }
    }


    /**
     * Сохранение данных в куках браузера, по упрощенной и надежной схеме.
     * Хранение кук происходит в шифрованом виде base64, парамент шифрования
     * настраевается в конфигурационном файле приложения, по умолчанию включено.
     *
     * @param string $key Имя
     * @param string $value Значение
     * @param null $expire Время хранения
     * @param null $path Путь
     * @param null $domain Домен
     * @return bool
     */
    public static function addCookie($key, $value, $expire = null, $path = null, $domain = null)
    {
        if ($expire == null) {
            $expire = time() + self::$expireCookTime;
        }

        if ($domain == null) {
            $domain = $_SERVER['HTTP_HOST'];
        }

        if ($path == null) {
            $path = '/' . self::$getURL['nude'] . '/';
        }
        if (self::$decodeCook)
            $value = base64_encode($value);
        
        if (self::$debug)
            return setcookie($key, $value, $expire, $path, $domain);
        else
            if(!headers_sent())
                return setcookie($key, $value, $expire, $path, $domain);
            else
                return false;
    }


    /**
     * Извклечь существующею куку
     *
     * @param   string      $key    имя куки
     * @return  null|string
     */
    public static function getCookie($key)
    {
        if (isset($_COOKIE[$key])) {
            if (self::$decodeCook)
                return base64_decode($_COOKIE[$key]);
            else
                return $_COOKIE[$key];
        } else {
            return null;
        }
    }

    /**
     * Удаление существующей куки
     *
     * @param   string  $key    имя
     * @param   null    $domain
     * @param   null    $path
     * @return  bool
     */
    public static function deleteCookie($key, $domain = null, $path = null)
    {

        if ($domain === null) {
            $domain = $_SERVER['HTTP_HOST'];
        }

        if ($path === null) {
            $path = '/' . self::$getURL['nude'] . '/';
        }

        return setcookie($key, false, time() - 3600, $path, $domain);
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
     *  //showEvent - функция или класс с мтодом array('className','method')
     *  $this->hookRegister('hook-01', 'showEvent');
     *  $this->hookRegister('hook-02', 'showName', array('param1'));
     *  $this->hookRegister('hook-03', 'showNameTwo', array('param1','param2'));
     * </pre>
     *
     * @param string $event Название евента
     * @param null $callback Обработчик события обратного вызова
     * @param array $params Передаваемые параметры
     * @return array
     */
    public static function hookRegister($event, $callback = null, array $params = array())
    {
        if (func_num_args() > 2) {
            self::$_hookBind[$event] = array($callback, $params);
        } elseif (func_num_args() > 1) {
            self::$_hookBind[$event] = array($callback);
        } else {
            return self::$_hookBind;
        }
        return true;
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
     * @param string $event
     * @param array $params
     * @throws App::ExceptionError
     */
    public static function hookTrigger($event, array $params = array())
    {

        if (isset(self::$_hookBind[$event]) AND $handlers = self::$_hookBind[$event]) {

            $handlersParam = (isset($handlers[1])) ? $handlers[1] : false;
            $handlersParam = (!empty($params)) ? $params : $handlersParam;

            if (is_callable($handlers[0])) {
                call_user_func($handlers[0]);
            } elseif (method_exists(__CLASS__, $handlers[0]) AND $handlersParam) {
                call_user_func_array(array(__CLASS__, $handlers[0]), $handlersParam);
            } elseif (method_exists(__CLASS__, $handlers[0])) {
                call_user_func(array(__CLASS__, $handlers[0]));
            } else {
                if (App::$debug)
                    App::ExceptionError('Invalid callable');
            }
        }
    }


    /**
     * Регистрация фильтра.
     *
     * @param string $filterName Имя фильтра
     * @param callable $callable солбек, функция или класс-метод
     * @param int $acceptedArgs количество принимаюих аргументов
     */
    public static function filterRegister($filterName, $callable, $acceptedArgs = 1)
    {
        if (is_callable($callable)) {
            self::$_filterBind[$filterName]['callable'] = $callable;
            self::$_filterBind[$filterName]['args'] = $acceptedArgs;
        }
    }

    /**
     * Тригер для зарегестрированого фильтра.
     *
     * @param string $filterName Имя фильтра
     * @param string|array $args входящие аргументы
     * @throws App::ExceptionError
     */
    public static function filterTrigger($filterName, $args)
    {
        if (isset(self::$_filterBind[$filterName])) {
            if (is_string($args)) {
                call_user_func(array(__CLASS__, self::$_filterBind[$filterName]['callable']), $args);
            } elseif (is_array($args) AND self::$_filterBind[$filterName]['args'] == sizeof($args)) {
                call_user_func_array(array(__CLASS__, self::$_filterBind[$filterName]['callable']), $args);
            }
        } else {
            if (App::$debug)
                App::ExceptionError('<b>Error filterTrigger</b> Invalid callable or invalid num arguments');
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
     * @param string $key Ключ флеш сообщения
     * @param mixed $value Значение
     * @param bool $keep Продлить существования сообщения до следущего реквкста; по умолчанию TRUE
     *
     * @return mixed
     */
    public static function flash($key = null, $value = null, $keep = true)
    {
        if (!isset($_SESSION)) session_start();
        $flash = '_flash';

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
                unset($_SESSION[$flash][$key]);
            }
            return $old;
        } elseif (func_num_args()) {
            $flashMessage = isset($_SESSION[$flash][$key]) ? $_SESSION[$flash][$key] : null;
            unset(self::$_flashStorage[$key]);
            unset($_SESSION[$flash][$key]);
            return $flashMessage;
        } else {
            return self::$_flashStorage;
        }
    }

    /**
     * @param null  $view
     * @param array $data   array('content'=>'content text')
     * @param bool  $e
     * @return string
     */
    public static function show404( $view=null, array $data=null, $e=true )
    {
        header("HTTP/1.0 404 Not Found");

        ob_start();

        if($data != null)
            extract($data);

        if($view==null AND file_exists(PATH_PUBLIC.self::$config['Error 404'].'.php')){
            include PATH_PUBLIC.self::$config['Error 404'].'.php';
        }else{

            if(file_exists($view.'.php')){
                include $view.'.php';
            }else{
                echo '<html>
                <head><title>Error 404</title></head><body style="background-color: #3C3F41">
                <div style="margin: 100px; text-align: center; font-family: \'Aeromatics\', Arial, Helvetica, sans-serif; text-shadow: 0 1px 1px #000;">
                    <h1  style="color: #ff0f00" >ERROR 404! Page not exists!</h1>
                    <p><a style="color: #5894CC" href="'.self::$getURL['base'].'">back to start page</a></p>
                    <div style="width:500px;margin:0 auto;padding:10px;background-color: #2B2B2B;color: #dce9ff;border-radius:6px;text-align: left; font-size: 12px;">
                        <p>К сожалению, запрашиваемой Вами страницы не существует на нашем сайте. Возможно, это случилось по одной из следующих причин:</p>
                        <ul>
                            <li>Вы ошиблись при наборе адреса страницы (URL)</li>
                            <li>Перешли по «битой» (неработающей, неправильной) ссылке</li>
                            <li>Запрашиваемой страницы никогда не было на сайте или она была удалена</li>
                            <li>Злой бандеровец утащил</li>
                        </ul>
                    </div>
                </div>
                </body></html>';
            }
        }

        $getContents = ob_get_contents();
        ob_clean();

        if($e)
            echo $getContents;
        else
            return $getContents;
    }

} // END class App