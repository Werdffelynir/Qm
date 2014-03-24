<?php
/**
 * MVC PHP Framework Quick Minimalism
 * Version: 0.3.0
 * File:    config.php
 * Author:  OL Werdffelynir [http://w-code.ru] [oleg@w-code.ru]
 * Date:    11.03.14
 * Docs:    http://qm.w-code.ru
 *
 * Конфигурация qm приложения.
 */

return array(

    /** Информационные настройки
     * *********************************************** */
     
    /* Имя приложения */
    'name'=>'My Web Qm Application',

    /* email приложения */
    'email'=>'email@email.com',

    /* Копирайт */
    'copy'=>'&copy; 2013-2014 OLWerdffelynir Web Developer <a href="http://w-code.ru">W-Code.ru</a>',

    /** Определять язык клиента и устанавить его по умолчанию */
    'identifyLanguage' => true,
    
    /** Установить Язык */
    'language' => 'ru',

    /** Соль, ключ безопасности дял cookie */
    'salt' => 'rename_me',
    'encodeCookie' => true,

    /** Время жизни cookie для встроеной функции */
    //'cookieTime' => 3600,

    
    /** Настройки производительности
     * *********************************************** */
         
    /*
     * Работа приложение в режиме debug - true или production - false
     * Включает отображение ошибок php, включает окно отладки,
     * включает чувствительность к рабочим методам ядра, контролера.
     * РЕКОМЕНДУЕТЬСЯ включать при разработке и отключать на продакшен.
     */
    'debug' => true,


    /** 
     * Базовый путь к каталогу.
     * Базовый url сайта.
     *
     * ПАРАМЕТРЫ НЕ ОБЕЗАТЕЛЬНЫ для большенства случаев.
     * (Перед тем как править настройку проверте параметр RewriteBase в файле .htaccess)
     * Устанавливать есди возникли проблемы с авто определением пути.
     * Например если путь к приложению http://w-code.ru/qmframework/... 
     *      и система не определила путь, нужно поставить параметр "/qmframework/"
     * Если приложение лежит в корне http://w-code.ru/...  
     *      параметр можно закоментрировать или поставить значение "/"
     */
    //"basePath" => '/qm_new/skeletron/',
    //"baseUrl" => 'w-code.ru',

    
    /* Тип оборботки рабочих файлов ядром, тип определет структуру выполнения обработки контролера и модели в преложении.
    Может быть "files" или "folders".
    Пример для контролера:
    Тип "files" включает структуру - app/Controllers/ControllerCataloge.php (http://site.loc/cataloge/index)
    Тип "folders" включает структуру - app/Controllers/cataloge/index.php (http://site.loc/cataloge/index)
    Для сравнения:
    Файл "ControllerCataloge.php" обрабатываеться при запросе "cataloge" одноименный класс
    "ControllerCataloge" умолчанию метод "actionIndex", приставки "Controller" файла и "action"
    метода обезательны для области видимости.
    В случаи "folders" при запросе "cataloge" попадаем в каталог "cataloge", по умолчанию ищеться файл "index.php"
    и в нутри могут быть методы без приставки "action", все находяться в  области видимости, для ограничение можно
    изменять модифекатор, или обявлять внутри доступ методом $this->access(["public|private"]).
    */
    "handlerType" => "files",
    
    /* Приложение имеет два типа роутинга:
     * false  - отключен
     * 'simple' - определят посимвольно (мало влияющий на производительность)
     * 'regex'  - определят по регулярным выражениях
     * примеры в документации http://w-code.ru/projects/qm/router
     * примеры в документации http://qm.w-code.ru/doc/router
     */
    "routeType" => false,
    
    /* Настройка роутеров приложения для параметра 'simple' 
     * синтаксис: array( 'you_new_addres', 'Controller/method' [, num priority]  )
     * синтаксис: array( 'you_new_addres',  array( 'Controller', 'method' ) [, num priority]  )
     
    "routeReName" => array(
        array( 'home', 'index/index' ),
        array( 'test', 'controller/method' ),
        //array( 'home', array( 'index', 'index' ) ),
        //array( 'page', array( 'Comtroller', 'method' ), 100 ),
    ),*/
    /* Настройка роутеров приложения для параметра 'regex'
     * синтаксис: array( 'router_regex', 'Controller/method' [, num priority]  )

    "routeReName" => array(
        array( 'home', array( 'index', 'index' ), 100 ),
        array( 'home/<:user_id>', array( 'index', 'user_id' ), 20 ),
        array( 'login/<:id>', array( 'index', 'login' ) ),
        array( 'page/view/<#user_id>', 'Controller/method', 100 ),
        array( 'page/view/<#user_id>', array( 'controller', 'method' ), 100 ),
    ),*/
    
    
    /* Подключение автозагрузки файлов директориии 'CLasses' */
    "classesFilesAutoload" => array(),
    
    /* Подключение автозагрузки классов директориии 'Extensions'  */
    "extensionsFilesAutoload" => array(
        //'phpmailer/class.phpmailer'   // доступен класс PHPMailer
        //'mPDF/mpdf'                   // доступен класс mPDF
    ),

    /* Подключение автозагрузки классов директориии 'Helpers'  */
    "helpersFilesAutoload" => array(),
    

    /** Настройки соединение с базой данных
     * *********************************************** */
    
    /* Настройка обявляет автозагрузку класса, только первого в списке если их несколько,
    свойство с екземпляром PDO создаеться при обращении к модели.
    Если настройка установленная в FALSE, то все екземпляы PDO необходимо создавать вручную. */
    "dbAutoConnect" => true,
    

    /* Настройки подключений к БД*/
    "dbConnects" => array(
    
        /* Настройки подключения к базе данных. через PDO SQLite */
        "db" => array(
            "driver" => "sqlite",
            "path" => dirname(__DIR__) . "/app/DataBase/qm_db.sqlite",
        ),
        /* Настройки подключения к базе данных. через PDO MySQL
        "dbMySql" => array(
            "driver"    => "mysql",
            "host"      => "localhost",
            "dbname"    => "test",
            "user"      => "root",
            "password"  => "",
        ),*/
        /* Настройки подключения к базе данных. через PDO Oracle
        "dbOci" => array(
            "driver" => "oci",
            "dbname" => "//dev.mysite.com:1521/orcl.mysite.com;charset=UTF8",
            "user"=>"user",
            "password"=>"password"
        ),*/
    ),
    
    
    
    /** Запуск Файлов Преложения
     * *********************************************** */
    
    /* Контролер запускаеться по умолчанию */
    "defaultController" => "index",
    
    /* При параметре "handlerType" => "folder" директория для поиска "defaultController" */
    "defaultControllerFolder" => "index",
    
    /* Префикс имени файла контролера по умолчанию, 
     * активны настройки если параметр "handlerType" => "files", 
     * если "handlerType" => "folder" префиксы имен файловне не учитываються */
    "defaultControllerPrefix" => "Controller",
    
    /* Префикс имени публичного метода контролера по умолчанию */
    "defaultMethodPrefix" => "action",

    /* Метод что запускаеться по умолчанию */
    "defaultMethod" => "index",

    /* Файл запуска оновного шаблона "defaultTheme", находиться в дир. "application/public/template.php" */
    "defaultLayoutStartFile" => "template",

    /* Вид по умолчанию */
    "defaultViewStartFile" => "main",

    /* Шаблон для страницы 404 */
    "Error 404" => "error404",

    
);