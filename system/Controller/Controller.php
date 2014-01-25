<?php

class Controller extends Common{


    protected $routeReName = array();

    /**
     * Розпаковывает масивы (стандартная PHP функция)
     * Если указано распаковка с масива в контролере, вложеные двнные
     * будут доступны в роспакованой форме.
     * РЕКОМЕНДОВАНО для небольших приложений, использывать для свойства $data
     * После включения даные будут виддны не $data['myTitle'] а $myTitle
     */
    protected $extracted = false;


    /**
     * Имя шаблона, устанавлеваеться с конфигурации lib/config.php
     * если в конфигурации не задано, используеться это свойство
     */
    protected $layout=null;


    /**
     * Временно хранит имена видов
     */
    protected $view=null;


    /**
     * Базовый URL адрес
     */
    public $url;


    /**
     * Временно хранит передаваемые в вид данные
     * используеться совместно с методом showData()
     */
    protected $data = array();


    /**
     * Временно хранит имена позиций в виде для метода out()
     */
    protected $outThemePosition = array();


    /** @var array
     * $_scripts     - Хранит в массиве все зарегестрированые пути к файлам скриптов
     * $scripts      - Хранит в массиве все отображанмые в данном контролере скрипты
     * $scriptsIsLoad  - Хранит в массиве все уже загружыные на страницу */
    public $_scripts = array();
    public $scripts = array();
    public $scriptsIsLoad = array();

    /** @var array
     * $_styles     - Хранит в массиве все зарегестрированые пути к файлам стилей
     * $styles      - Хранит в массиве все отображанмые в данном контролере скрипты
     * $stylesIsLoad  - Хранит в массиве все уже загружыные на страницу */
    public $_styles = array();
    public $styles = array();
    public $stylesIsLoad = array();


    /**
     * Имена классов имеющихся в каталоге Classes и их активность
     */
    public $classesData = array();


    /**
     * Конструктор задает последовательность загрузки методов
     */
    public function __construct()
    {
        $this->before();

        $this->init();

        /** @var  layout входящий файл темы по умролчанию */
        if(is_null($this->layout))
            $this->layout   = App::$config["defaultThemeStartFile"];
        /** @var  layout вид основной по умочанию */
        if(is_null($this->view))
            $this->view     = App::$config["defaultViewStartFile"];
        //$actions = $this->actions();
        //if(!empty($actions))
            $this->view     = App::$config["defaultViewStartFile"];

        /** @var  url */
        $this->url      = App::$url;

        $this->after();
    }

    /**
     * @return array
     */
    public function actions(){return array();}


    /**
     * Загружаеться перед загрузкой подключаемых классов
     **/
    public function before(){}
    /**
     * Загружаеться после всех загрузок
     **/
    public function after(){}


    /**
     * Загружаеться самым первым.
     **/
    public function init(){}


    /**
     * Метод подключения видов с контролеров или шаблона (theme).
     * Принцип метода подключение функцией include() динамической составной преложения
     * по умолчанию это центральный динамический блок страницы, при этом header и footer
     * остаються относительно статическими
     *
     * для контролеров, елементарное подключение вида назначаеться методом show() без параметров:
     * show('nameView');
     *
     * для контролеров, подключает прямо один вид без layout
     * show('nameView', true);
     *
     * Нзначение вывода в шаблоне (theme) layout
     * show($viewName, true, true);
     *
     * @param bool|string $viewName  Имя вида, или если указано три аргумента позиция отображения должно быть $viewName
     * @param bool        $nested    Являеться вложенным ли, если TRUE вернет вид без layout
     * @param bool        $theme     если оявлена в шаблоне TRUE
    */
    public function show($viewName=false, $nested=false, $theme=false)
    {

        $viewName = ($viewName) ? $viewName : $this->view;

        /** Если указано распаковка с масива в контролере, вложеные двнные
         *  будут доступны в виде в роспакованой форме */
        if(isset($this->extracted) AND !empty($this->data))
            extract($this->data);

        if(!$theme){
            if(!$nested){
                include PATH_APP_THEME.$this->layout.".php";
            } else {
                include PATH_APP_VIEWSPARTIALS.$viewName.".php";
            }
        } else {
            include PATH_APP_VIEWSPARTIALS.$viewName.".php";
        }
    }


    /**
     * Метод для передачи данных в вид или шаблон
     * Первый аргумент регистрация имени, воторй данные.
     * Метод работает аналогом с премым присваеванием $this->data['myTitle'] = 'xxx';
     *
     * <pre>
     * Назначение данных производить в контроллере:
     *
     *  $this->setData('myData', 'Это мой заголовок к статье!');
     *
     *  $this->setData('myData', array(
     *      'title'   =>'Это мой заголовок к статье!',
     *      'content' =>'А это статья... о ежиках и белочках',
     *  ));
     *
     *  $this->setData('myData', array(
     *      'title'   =>'Это мой заголовок к статье!',
     *      'content' => array(
     *                        'text'    => 'А это статья... о ежиках и белочках',
     *                        'category'=> 'животные',
     *                        ),
     *  ));
     *
     *  $this->setData('myData', array(
     *      'title'   =>'Это мой заголовок к статье!',
     *      'content' => array(
     *                        'text'      => 'А это статья... о ежиках и белочках',
     *                        'category'  => array(
     *                                            'news'  => 'новости',
     *                                            'other' => 'другое',
     *                                          ),
     *                        ),
     *  ));
     *
     * в виде:
     * $this->data('myData' [, array | string]); Подробно в описании метода
     * </pre>
     *
     * @param string  $name   зегестрируемое имя
     * @param null    $data   данные, строка или массив
     */
    public function setData( $name, $data=null )
    {
        if(is_array($data)){
            foreach ($data as $dataVar=>$dataVal) {
                if(!is_numeric($dataVar)){
                    $this->data[$name][$dataVar] = $dataVal;
                }else{
                    $this->data[$name] = $dataVal;
                }
            }
        }else{
            $this->data[$name] = $data;
        }
    }


    /**
     * Метод для вывод данных в виде. Создан для совместного использования с методом
     * show().
     * Назначение данных производить в контроллере методом setData();
     *
     * <pre>
     * Пример:
     *  $hooker->data("myData");
     *
     *  $hooker->data("myData", "title");
     *
     *  $hooker->data("myData", array("content"=>"text"));
     *
     *  $hooker->data("myData", array(
     *                                "content" => array('category'=>'news')
     *                              )
     *  );
     * </pre>
     *
     * @param string        $dataName   Имя
     * @param string|array  $dataArg    Значения с массива
     * @param bool          $e          тип возврата
     * @return string
     */
    public function data($dataName, $dataArg = null, $e = true)
    {
        if (array_key_exists($dataName, $this->data)) {

            if (!is_null($dataArg)) {

                if (is_array($dataArg)) {

                    $dataKey = key($dataArg);

                    if (is_array($dataArg[$dataKey])) {

                        $dataKeyIn = key($dataArg[$dataKey]);
                        $dataArgIn = $dataArg[$dataKey][$dataKeyIn];

                        if ($e) echo $this->data[$dataName][$dataKey][$dataKeyIn][$dataArgIn];
                        else return $this->data[$dataName][$dataKey][$dataKeyIn][$dataArgIn];

                    } elseif (is_string($dataArg[$dataKey])) {

                        $argIn = (string)$dataArg[$dataKey];
                        if ($e) echo $this->data[$dataName][$dataKey][$argIn];
                        else return $this->data[$dataName][$dataKey][$argIn];
                    }

                } elseif (is_string($dataArg)) {
                    if ($e) echo $this->data[$dataName][$dataArg];
                    else return $this->data[$dataName][$dataArg];
                }
            } else {
                if ($e) echo $this->data[$dataName];
                else return $this->data[$dataName];
            }
        } else {
            return '';
        }
    }



    /**
     *<pre>
     * для контролеров, если используеться один Position
     *  $this->out( '__namePosition__' , '__nameView__' , $myArrayData );
     *
     * для контролеров, если используеться несколько Position
     *  $this->out(array(
     *      array('__namePositionOne__', '__nameViewOne__', $myArrayDataOne),
     *      array('__namePositionTwo__', '__nameViewTwo__', $myArrayDataTwo),
     *      // ...
     *  ));
     *
     * для контролеров, без layout импользывать четвертый параметр с true
     *  out('__namePosition__', '__nameView__', $myArrayData, true);
     *
     * Нзначение вывода в шаблоне (theme) layout. Происходит другим методом outTheme
     *  outTheme('__namePosition__', $OmData);
     * Имя переменной $OmData обезательно без изминений
     *</pre>
     *
     * @param string    $position
     * @param bool      $view
     * @param bool      $data
     * @param bool      $nested
     */
    public function out( $position, $view=false, $data=false, $nested=false)
    {

        // Определение на вывод в одину позицию шаблона outTheme(), или в несколько
        if(is_array($position))
        {
            $layouts = array();
            $countLayouts = 0;

            ob_start();

            foreach ($position as $output) {
                extract($output[2]);
                include PATH_APP_VIEWSPARTIALS.$output[1].".php";
                $layouts[$countLayouts]['position'] = $output[0];
                $layouts[$countLayouts]['view'] = $output[1];
                $layouts[$countLayouts]['data'] = $output[2];
                ob_clean();
                $countLayouts++;
            }
            $OmData = $layouts;
            include PATH_APP_THEME.$this->layout.".php";
        }
        else
        {
            if(!$nested){
                $OmData = array(
                    'position' => $position,
                    'view' => ($view) ? $view : $this->view,
                    'data' => $data,
                );

                include PATH_APP_THEME.$this->layout.".php";
            } else {
                include PATH_APP_VIEWSPARTIALS.$view.".php";
            }
        }
    }


    /**
     * Используеться совместно с методом out()
     * В шаблоне назначаються позиции вывода данных
     *
     *<pre>
     * Пример:
     *
     * @param string        $position
     * @param array|string  $OmData
     */
    public function outTheme($position, $OmData)
    {

        $OmDataTemp = $OmData;

        if( !isset($OmDataTemp["position"]) ){
            foreach ($OmDataTemp as $OmDataValue) {

                if($position == $OmDataValue['position']){
                    $OmData['position'] = $OmDataValue['position'];
                    $OmData['view'] = $OmDataValue['view'];
                    $OmData['data'] = $OmDataValue['data'];
                }

            }
        }

        if($position == $OmData['position']){
            if(isset($OmData['data']) AND is_array($OmData['data']))
                extract($OmData['data']);

            include PATH_APP_VIEWSPARTIALS.$OmData['view'].".php";
        }
        unset($OmData);
    }


    /**
     * Метод render() реализовывает подключение в основной шаблон theme видов с контролера
     * Еще один способ, имеет иной подход от предведущих.
     *
     *<pre>
     * Пример:
     * // Одиночный вывод
     * $this->render('__namePosition__', '__nameView__', array('title'=>'My test title'));
     *
     * // массовый вывод
     * 'blockName_*' - перемнная в основном шаблоне 'main.php' по умолчанию,
     * 'viewLeft'    - вид в калоге Views
     * 'keyLeft'     - переменная которая будет видна в виде 'viewLeft'
     *
     * $this->render(array(
     *    'blockName_Left'      => array('viewLeft', array('keyLeft'=>'My data')),
     *    'blockName_General'   => array('viewContent', array('keyContent'=>'My data')),
     *    'blockName_Right'     => array('viewRight', array('keyRight'=>'My data')),
     *    )
     *);
     *
     * //В шаблоне позиции назначать следующим образом:
     *  <?php echo $this->blockName_Left;
     * </pre>
     *
     * @param array|string  $dataPos    если вамив то массовый вывод, строка позиция
     * @param               $view       если одиночный вид
     * @param array         $dataArr    если одиночный данные в виде массива
     */
    public function render( $dataPos, $view=false, array $dataArr=array() )
    {
        if(is_array($dataPos)){
            foreach ($dataPos as $keyBlockName => $dataView) {
                if(isset($dataView[1]) AND is_array($dataView[1]))
                    extract($dataView[1]);
                $keyInclude = PATH_APP_VIEWSPARTIALS.$dataView[0].".php";
                ob_start();
                include $keyInclude;
                $this->$keyBlockName = ob_get_clean();
            }
        }elseif(is_string($dataPos)){
            if(!$view) $view = $this->view;
            if(!empty($dataArr)) extract($dataArr);
            $keyInclude = PATH_APP_VIEWSPARTIALS.$view.".php";
            ob_start();
            include $keyInclude;
            $this->$dataPos = ob_get_clean();
        }
        include PATH_APP_THEME.$this->layout.".php";

    }

    /**
     * Метод вывода в шаблон видов с данными, совметсный с методом render()
     *
     * <pre>
     * Скорость: в 10-100 раз медленее чем выводить напрямую
     * $this->__namePosition__ (0.0008 / 1000 it)
     * $this->renderTheme( __namePosition__ ) (0.0222 / 1000 it)
     * </pre>
     *
     * @param string    $renderPosition     названеи позиции указаной в контролере методом render()
     */
    public function renderTheme( $renderPosition )
    {
        if(isset($this->$renderPosition))
            echo $this->$renderPosition;
    }


        /**
     * Регистрация скрипта javascript в области видимости контролера
     *
     * <pre>
     * $this->registerScript(array(
     *  'path'      => путь к скрипту
     *  'name'      => регистрационное имя
     *  'showIn'    => отображения на странице 'header' - в хедере, 'footer' - в футере
     *  'position   => позиция
     * ));
     * <pre>
     *
     * @param array $scriptData Массив параметров для регистрации скрипта
     * @return bool
     */
    public function registerScript( array $scriptData )
    {
        $this->_scripts[$scriptData['name']] = array(
            'url' => $scriptData['url'],
            'path' => (isset($scriptData['path'])) ? $scriptData['path'] : null,
            'name' => $scriptData['name'],
            'showIn' => (isset($scriptData['showIn'])) ? $scriptData['showIn'] : "header",
            'position' => count($this->_scripts)+1,
        );
    }


    /**
     * Регистрация стиля CSS в области видимости контролера
     *
     * <pre>
     * $this->registerStyle(array(
     *  'path'      => путь к скрипту
     *  'name'      => регистрационное имя
     *  'showIn'    => отображения на странице 'header' - в хедере, 'footer' - в футере
     *  'position   => позиция
     * ));
     * <pre>
     * @param  array    $styleData Массив параметров для регистрации скрипта
     * @return bool
     */
    public function registerStyle( array $styleData )
    {
        /*
        if(isset($styleData['path']) AND file_exists($styleData['path'])){
            $path = $styleData['path'];
        }else{
            $path = 'ERROR. file not exists! '.$styleData['path'];
        }
        */

            $this->_styles[$styleData['name']] = array(
                'url' => $styleData['url'],
                'path' => (isset($styleData['path'])) ? $styleData['path'] : null,
                'name' => $styleData['name'],
                'showIn' => (isset($styleData['showIn'])) ? $styleData['showIn'] : "header",
                'position' => count($this->_styles)+1,
            );

    }


    /**
     * Метод добавление скриптов в контролере. Добавляет зарегестрированые скрипты по его имени при регистрации.
     * Регистрация производиться зарание, методом "$this->registerScript()"
     *
     * <pre>
     * // Добавляет скрипт 'jquery' в хедере
     * $this->addScript('jquery');
     * $this->addScript('jquery', 'header'); То же что и выше.
     *
     * // Добавляет скрипт 'jquery' в футере
     * $this->addScript('jquery', 'footer');
     *
     * // Отключает скрипт
     * $this->addScript('jquery', 'disabled');
     * </pre>
     *
     * @param array|string  $regScriptName
     * @param bool          $showIn
     * @return bool
     */
    public function addScript( $regScriptName, $showIn=false)
    {
        if(is_string($regScriptName)){

            foreach($this->_scripts as $_script){
                if($_script['name'] == $regScriptName){
                    if( $showIn == 'disabled'){
                        unset($this->scripts[$regScriptName]);
                    }else{
                        $this->scripts[$regScriptName] = $_script;
                        ($showIn) ? $this->scripts[$regScriptName]['showIn'] = $showIn : null;
                    }
                }
            }
        }elseif(is_array($regScriptName)){
            foreach($this->_scripts as $_script){
                foreach($regScriptName as $regScript){
                    if($_script['name'] == $regScript)
                    {
                        if( $showIn == 'disabled'){
                            unset($this->scripts[$regScript]);
                        }else{
                            $this->scripts[$regScript] = $_script;
                            ($showIn) ? $this->scripts[$regScript]['showIn'] = $showIn : null;
                        }
                    }
                }
            }
        }else{
            return false;
        }
    }


    /**
     * Метод практически выполняет тужн роль что и addScript(), но не принемает позиции отображения на странице.
     * Регистрация производиться зарание методом "$this->registerStyle()"
     *
     * <pre>
     * // Добавляет скрипт 'jquery' в хедере
     * $this->addScript('myCss');
     *
     * // Отключает скрипт
     * $this->addScript('myCss', 'disabled');
     * </pre>
     *
     * @param array|string  $regStyleName
     * @param bool          $disabled
     * @return bool
     */
    public function addStyle( $regStyleName, $disabled=false )
    {
        if(is_string($regStyleName)){
            foreach($this->_styles as $_styles){
                if($_styles['name'] == $regStyleName){
                    if( $disabled == 'disabled'){
                        unset($this->styles[$regStyleName]);
                    } else {
                        $this->styles[$regStyleName] = $_styles;
                    }
                }
            }
        }elseif(is_array($regStyleName)){
            foreach($this->_styles as $_styles){
                foreach($regStyleName as $regStyle){
                    if($_styles['name'] == $regStyle){
                        if( $disabled == 'disabled')
                            unset($this->styles[$regStyle]);
                        else
                            $this->styles[$regStyle] = $_styles;
                    }
                }
            }
        }else{
            return false;
        }
    }


    /**
     * Отображение всех подключенных скриптов не посредственно в активном шаблоне "theme",
     * Для использования метода скрипты должны быть зарегестрированы сначала методом "registerScript('path','name')"
     * в общем контролере или в другом.
     * Дальше подключены в контролере общем  или другом, методом addScript('name')
     *
     * И выведены данным методом  например в "myTheme/main.php".
     * echo $this->showScripts();
     * между тегов <head>
     *
     * @param string $showIn
     * @param bool $scrName
     * @return bool|string
     */
    public function showScripts($showIn='header', $scrName=false)
    {
        $temp_scr = "";

        if($scrName) {
            foreach( $this->scripts as $scripts){
                if($scripts['name'] == $scrName ){
                    $temp_scr .= '<script type="text/javascript" src="'.$scripts['url'].'"></script>'."\n";
                }
            }
            echo $temp_scr;

        }elseif($showIn == 'header') {
            foreach( $this->scripts as $scripts){
                if($scripts['showIn'] == $showIn ){
                    $temp_scr .= '<script type="text/javascript" src="'.$scripts['url'].'"></script>'."\n";
                }
            }
            echo $temp_scr;

        } elseif($showIn == 'footer') {
            foreach( $this->scripts as $scripts){
                if($scripts['showIn'] == $showIn ){
                    $temp_scr .= '<script type="text/javascript" src="'.$scripts['url'].'"></script>'."\n";
                }
            }
            echo $temp_scr;

        } else {
            return false;
        }


    }


    /**
     * Отображение всех подключенных скриптов-стилей не посредственно в активном шаблоне "theme",
     * Для использования метода стили должны быть зарегестрированы сначала методом "registerStyle('path','name')"
     * в общем контролере или в другом.
     * Дальше подключены в контролере общем или другом, методом addStyle('name')
     *
     * И выведены данным методом  например в "myTheme/main.php".
     * echo $this->showStyles();
     * между тегов <head>
     *
     * @return string
     */
    public function showStyles()
    {
        if(!empty($this->styles)){
            $temp_scr = "";
            foreach( $this->styles as $styles){
                $temp_scr .= '<link rel="stylesheet" type="text/css" href="'.$styles['url'].'" />'."\n";
            }
            echo $temp_scr;
        }else{
            return false;
        }
    }


    /**
     * Комбинация вывода скриптов и стилей
     * $this->showScripts();
     */
    public function showHeader()
    {
        $toHead = $this->showStyles();
        $toHead .= $this->showScripts('header');
        return $toHead;
    }


}