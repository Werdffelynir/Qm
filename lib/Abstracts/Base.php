<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Comp-2
 * Date: 29.11.13
 * Time: 19:16
 * To change this template use File | Settings | File Templates.
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
        $this->before();
        if(QmConf('defaultLayout'))
            $this->layout = QmConf('defaultLayout');
        $this->after();
    }
    /**
     * Загружаеться самым первым, может используваться для создания неких настроек
     **/
    public function beforeLoadClasses(){}
    public function init(){}
    /**
     * Загружаеться перед загрузкой подключаемых классов
     **/
    public function before(){}
    /**
     * Загружаеться после всех загрузок
     **/
    public function after(){}





    /**
     * Берет в переменную чатсть вида и возвращает в переменную, работает на подобии
     * метода out() но не импортирует данные в layout
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
    public function setСhunk( $chunkName, $chankView, array $dataChunk=null )
    {
        $viewInclude = PATH_APP_VIEWS.$chankView.'.php';

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
     *
     */
    public function autoloadClasses()
    {
        ClLoader::run()->scan();
        $this->classesData = ClLoader::run()->classesData;
    }


    /**
     * @param $fileClassName
     * @return mixed
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
        $model = $modelName;
        include PATH_APP."Models".DS.$model.".php";
        $newModel = new $model();
        return (object) $newModel;
    }




}