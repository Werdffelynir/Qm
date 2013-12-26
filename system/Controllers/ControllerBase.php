<?php
/**
 * Abstract Class Base
 *
 * MVC PHP Framework Quick Minimalism
 * File:    Base.php
 * Version: 0.2.0
 * Author:  OL Werdffelynir
 * Date:    07.12.13
 */
class Base {

    /**
     * Имя шаблона, устанавлеваеться с конфигурации lib/config.php
     * если в конфигурации не задано, используеться это свойство
     */
    public $layout  = 'main';

    /**
     * Имена классов имеющихся в каталоге Classes и их активность
     */
    public $classesData = array();

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
     * Конструктор задает последовательность загрузки методов
     */
    public function __construct()
    {
        $this->init();
        $this->beforeLoadClasses();
        $this->autoloadClasses();
        $this->afterLoadClasses();

        $this->before();
        if(QmConf('defaultLayout'))
            $this->layout = QmConf('defaultLayout');
        $this->after();
    }

    /**
     * Загружаеться самым первым.
     **/
    public function init(){}


    /**
     * Методы отвечают назвнию, запускаються при подключении всех
     * дополнительных классов, до и после.
     */
    public function beforeLoadClasses(){}
    public function afterLoadClasses(){}


    /**
     * Загружаеться перед загрузкой подключаемых классов
     **/
    public function before(){}
    /**
     * Загружаеться после всех загрузок
     **/
    public function after(){}


    /**
     * Берет в переменную чатсть вида и возвращает в переменную,
     * работает на подобии метода out() но не импортирует данные
     * в layout.
     * Аналогично можно использывать и сам метод out() или show()
     */
    public function partial( $viewName, array $data=null )
    {
        if(!is_null($data))
            extract($data);

        $toInclude = PATH_APP_VIEWS.$viewName.".php";

        ob_start();

        include $toInclude;

        $getContents = ob_get_contents();

        ob_clean();

        return $getContents;
    }


    /**
     * Устанавлевает удобную передачу данных в вид или шаблон
     * Работает подобно showData? но с иным подходом
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
     */
    public function setChunk( $chunkName, $chunkView, array $dataChunk=null )
    {
        $viewInclude = PATH_APP_VIEWS.$chunkView.'.php';

        // Если вид чанка не существует отображается ошибка
        if(!file_exists($viewInclude)){
            //throw new Exception("Chunk View don't exists!");
            //print("Файл [ ".$viewInclude." ] несуществует!");
            errorFileExists($viewInclude);
            exit;
        }

        ob_start();
        ob_implicit_flush(false);

        if(!is_null($dataChunk))
            extract($dataChunk);

        include $viewInclude;

        $this->chunk[$chunkName] = ob_get_clean();
    }


    /**
     * Производит авто загрузку дополнительных классов
     */
    public function autoloadClasses()
    {
        ClLoader::run()->scan();
        $this->classesData = ClLoader::run()->classesData;
    }


    /**
     * Подгружает указаный файл, который должен размещаться в каталоге "Classes"
     * активного приложения.
     * @param $fileClassName    Имя класса
     * @return mixed            Обект класса
     */
    public function loadClass($fileClassName)
    {
        $neObject = ClLoader::run()->loadClass($fileClassName);
        $this->classesData = ClLoader::run()->classesData;
        return $neObject;
    }




    /**
     * Метод для подключение моделей, параметром берет созданый раньше класс Метода
     * Возвращает обект модели с ресурсом подключеным к базе данных
     */
    public function model( $modelName )
    {
        if(!class_exists($modelName)){
            $model = $modelName;
            include PATH_APP."Models".DS.$model.".php";
            $newModel = new $model();
            return (object) $newModel;
        }else{
            return false;
        }
    }


    /**
     * @param $modelName
     * @return bool|mixed
     */
    public function incModel( $modelName )
    {
        $file = PATH_APP."Models".DS.$modelName.".php";
        if(!file_exists($file)) die("File ".$file." not exists!");
        if(!class_exists($modelName)){
            $this->beforeIncModel();
            $includeModel = include PATH_APP."Models".DS.$modelName.".php";
            $this->afterIncModel();
            return $includeModel;
        }else{
            return false;
        }
    }

    public function beforeIncModel(){}
    public function afterIncModel(){}


    /**
     * @param $className
     * @return bool|mixed
     */
    public function incClass( $className )
    {
        $file = PATH_APP."Classes".DS.$className.".php";
        if(!file_exists($file)) die("File ".$file." not exists!");
        if(!class_exists($className)){
            $this->beforeIncClass();
            $includeClass = include $file;
            $this->afterIncClass();
            return $includeClass;
        }else{
            return false;
        }
    }

    public function beforeIncClass(){}
    public function afterIncClass(){}

}