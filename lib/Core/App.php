<?php
/**
 * Created by PhpStorm.
 * User: Werdffelynir
 * Date: 07.12.13
 * Time: 14:36
 */

class App {

    /** @var string */
    public $path;
    /** @var string */
    public $host;

    /** @var string */
    public $appPath;
    /** @var string */
    public $url;
    /** @var string */
    public $urlNude;
    /** @var string */
    public $urlHttps;

    /** @var string Префикс для контролера. (ControllerAbout.php) */
    private $controllerPrefix = "Controller";
    /** @var string Префикс для екшена. (actionLogin.php) */
    private $actionPrefix = "action";
    /** @var string Префикс для URL. (http://my-site.com/index.php?controller/actions) */
    private $requestPrefix; // = "?";

    /** @var string */
    public $route;
    /** @var string */
    public $structures;

    /** @var string */
    public static $request;
    /** @var string */
    public static $requestFull;

    /** @var string */
    public $config = array();

    /** @vars string внутриние свойства */
    public $_url;
    public $_requestUri;

    public function __construct()
    {
        if (file_exists(PATH_LIB . "configuration.php")) {
            $this->config = QmConf();
        } else {
            throw new \Exception("Config file not exist!");
        }

        /** Помещение всех возможно подключенных модулей, список с конф-файла в массив свойства.*/
        $structures = $this->config['structure'];
        if (!empty($structures))
            $this->structures = $structures;

        $requestURI = explode('/', $_SERVER['REQUEST_URI']);
        $scriptName = explode('/',$_SERVER['SCRIPT_NAME']);

        for($i= 0; $i < sizeof($scriptName); $i++){
            if ($requestURI[$i] == $scriptName[$i]){
                unset($requestURI[$i]);
            }
        }

        $requestUriParse = array_values($requestURI);

        self::$requestFull = $requestURI;
        self::$request = implode("/",$requestUriParse);

        $this->route = $requestUriParse;

        $this->run();
    }



    /**
     *
     *
     */
    private function run()
    {
        /** Извлечение первого елемента в роуте */
        $url = array_shift($this->route);

        /** Если присутствуют запрещенные сиволы. Установка в конфигурации */
        if($this->config['safeUrl']){
            if (!preg_match("|^[a-zA-Z0-9\.,-_]*$|", $url))
                throw new Exception("Invalid Path");
        }

        /** Проверка являеться ли первый параметр одним из каталогов структуры-модуля
         * обявленных в конфиг файле */
        /** Если являеться Структурным-модулем */
        if ($structureFolder = $this->isStructureFile($url)) {
            $urlСontrol = array_shift($this->route);

            /** Назначение возможного имени контроллера в стркутурном-модуле */
            $controllerStructName = $this->controllerPrefix . $urlСontrol;

            /** Если файл существует, запускаем его */
            if (file_exists($this->config['pathApp'] . DS . 'Structure' . DS . $structureFolder . DS . 'Controllers' . DS . $controllerStructName . '.php')) {
                $this->runController($controllerStructName, $structureFolder);

                /** Если файл НЕ существует, Запус по умолчанию, указаного в конфиг-файле  */
            } elseif (file_exists($this->config['pathApp'] . DS . 'Structure' . DS . $structureFolder . DS . 'Controllers' . DS . $this->controllerPrefix . 'Index' . '.php')) {

                $this->runController($this->controllerPrefix . 'Index', $structureFolder);

            } else {

                /** Сначала нужно обратно положить роут */
                array_unshift($this->route, $url);
                /** Запуск контроллера по умолчанию */
                $this->runController($this->controllerPrefix . $this->config['defaultController']);
            }


        } else {

            /** Назначение возможного имени контроллера */
            $controllerName = $this->controllerPrefix . ucfirst($url);

            /** Если файл существует, запускаем его */
            if (file_exists($this->config['pathApp'] . DS . 'Controllers' . DS . $controllerName . '.php')) {
                $this->runController($controllerName);

                /** Если файл НЕ существует, Запус по умолчанию, указаного в конфиг-файле  */
            } else {
                /** Сначала нужно обратно положить роут */
                array_unshift($this->route, $url);
                /** Запуск контроллера по умолчанию */
                $this->runController($this->controllerPrefix . $this->config['defaultController']);
            }
        }

    }


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
     */
    private function runController($controllerName, $structure = false)
    {
        /** Запуск контролеров с конфигурации "runStartController"*/
        $this->runStartController();

        if (!$structure)
            include APP . DS . 'Controllers' . DS . $controllerName . '.php';
        else
            include APP . DS . 'Structure' . DS . $structure . DS . 'Controllers' . DS . $controllerName . '.php';

        $controller = new $controllerName();

        /** если это первая страницы без елементов, запуск метода по умолчанию*/
        if (empty($this->route) || empty($this->route[0])) {
            /** Подстановка префикса название метода*/
            $actionPrefix = $this->actionPrefix . 'index';
            $controller->$actionPrefix();
        } else {
            /** если нет параметров*/
            if (empty($this->route)) {
                /** Подстановка префикса название метода*/
                $method = $this->actionPrefix . 'index';
            } else {
                /** Выборка метода и подстановка префикса название метода*/
                $methodLive = array_shift($this->route);
                $method = $this->actionPrefix . $methodLive;
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
                include PATH . 'theme' . DS . $this->config['Error 404'];
            }
        }
    }


    /**
     * Метод устанвлевает и запускает все указаные в настройках "runStartController" классы конролера
     */
    private function runStartController()
    {
        $start = $this->config['runStartController'];

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
    }


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

            foreach ($structures as $structure) {
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


}


/*
$requestURI = explode('/', $_SERVER['REQUEST_URI']);
$scriptName = explode('/',$_SERVER['SCRIPT_NAME']);
for($i= 0; $i < sizeof($scriptName); $i++){
    if ($requestURI[$i] == $scriptName[$i]){
        unset($requestURI[$i]);
    }
}
$command = array_values($requestURI);
*/