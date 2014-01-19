<?php

class Controller extends Rendering{


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
    protected $layout;


    /**
     * Временно хранит имена видов
     */
    protected $view;


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
     * @var array Массив содержит зарегестрированые хуки
     */
    public static $_hookBind=array();


    /**
     * Конструктор задает последовательность загрузки методов
     */
    public function __construct()
    {
        $this->before();

        $this->init();

        /** @var  layout входящий файл темы по умролчанию */
        $this->layout   = App::$config["defaultViewStartFile"];
        /** @var  layout вид основной по умочанию */
        $this->view     = App::$config["defaultThemeStartFile"];
        /** @var  url */
        $this->url      = App::$url;

        $this->after();
    }



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

        $viewName = ($viewName) ? $viewName : App::$config['defaultViewStartFile'];

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
     *  out('name_view', true);
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

            $this->beforeOut();

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
                    'view' => $view,
                    'data' => $data,
                );

                include PATH_APP_THEME.$this->layout.".php";
            } else {
                include PATH_APP_VIEWSPARTIALS.$view.".php";
            }
        }

        $this->afterOut();
    }
    /**
     * Выполняеься до out()
     */
    public function beforeOut(){}
    /**
     * Выполняеься после out()
     */
    public function afterOut(){}


    /**
     * Используеться совместно с методом out()
     * В шаблоне назначаються позиции вывода данных
     *
     *<pre>
     * Пример:
     *
     * @param string    $position
     * @param array     $OmData
     */
    public function outTheme($position, $OmData)
    {
        $OmDataTemp = $OmData;
        if(count($OmDataTemp) > 1 ){
            foreach ($OmDataTemp as $OmDataValue) {
                if($position == $OmDataValue['position']){
                    $OmData['position'] = $OmDataValue['position'];
                    $OmData['view'] = $OmDataValue['view'];
                    $OmData['data'] = $OmDataValue['data'];
                }
            }
        }

        if($position == $OmData['position']){
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
     * 'blockName_*' - перемнная в основном шаблоне 'main.php' по умолчанию,
     * 'viewLeft'    - вид в калоге Views
     * 'keyLeft'     - переменная которая будет видна в виде 'viewLeft'
     *
     * $this->render(array(
     *    'blockName_Left'      => array('viewLeft', array('keyLeft'->'My data')),
     *    'blockName_General'   => array('viewContent', array('keyConten'->'My data')),
     *    'blockName_Right'     => array('viewRight', array('keyRight'->'My data')),
     *    )
     *);
     *
     * В шаблоне позиции назначать следующим образом:
     *  <?php echo $this->blockName_Left;
     * </pre>
     *
     * @param array $viewsAndData
     */
    public function render( array $viewsAndData )
    {
        $this->beforeRender();
        $data = array();
        foreach ($viewsAndData as $keyBlockName => $dataView) {
            extract($dataView[1]);
            $keyInclude = PATH_APP_VIEWSPARTIALS.$dataView[0].".php";
            ob_start();
            include $keyInclude;
            $this->$keyBlockName = ob_get_contents();
            ob_clean();
        }
        include PATH_APP_THEME.$this->layout.".php";
        $this->afterRender();
    }
    public function beforeRender(){}
    public function afterRender(){}



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
        //if(file_exists($styleData['path'])){
            $this->_styles[$styleData['name']] = array(
                'url' => $styleData['url'],
                'path' => (isset($styleData['path'])) ? $styleData['path'] : null,
                'name' => $styleData['name'],
                'showIn' => (isset($styleData['showIn'])) ? $styleData['showIn'] : "header",
                'position' => count($this->_styles)+1,
            );
        //}else{
        //    return false;
        //}
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
                            $this->styles[$regStyle] = $_script;
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
        //$baseUrl = App::$config['baseUrl'];

        if($scrName) {
            foreach( $this->scripts as $scripts){
                if($scripts['name'] == $scrName ){
                    //$regScriptUrl = substr($scripts['path'], strpos($scripts['path'],$baseUrl) + strlen($baseUrl) + 1 ) ;
                    //$regScriptUrl = $this->url.'/'.str_replace('\\','/',$regScriptUrl);
                    $temp_scr .= '<script type="text/javascript" src="'.$scripts['url'].'"></script>'."\n";
                }
            }
            echo $temp_scr;

        }elseif($showIn == 'header') {
            //var_dump($this->scripts);
            foreach( $this->scripts as $scripts){

                if($scripts['showIn'] == $showIn ){
                    //$regScriptUrl = substr($scripts['path'], strpos($scripts['path'], $baseUrl) + strlen($baseUrl) + 1 ) ;
                    //$regScriptUrl = $this->url.'/'.str_replace('\\','/',$regScriptUrl);
                    $temp_scr .= '<script type="text/javascript" src="'.$scripts['url'].'"></script>'."\n";
                }
            }
            echo $temp_scr;

        } elseif($showIn == 'footer') {
            foreach( $this->scripts as $scripts){

                if($scripts['showIn'] == $showIn ){
                    //$regScriptUrl = substr($scripts['path'], strpos($scripts['path'], $baseUrl) + strlen($baseUrl) + 1 ) ;
                    //$regScriptUrl = $this->url.'/'.str_replace('\\','/',$regScriptUrl);
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
        //$baseUrl = App::$config['baseUrl'];

        if(!empty($this->styles)){
            $temp_scr = "";
            foreach( $this->styles as $styles){
                //$regStyleUrl = substr($styles['path'], strpos($styles['path'], $baseUrl) + strlen($baseUrl) + 1 ) ;

                //$regStyleUrl = $this->url.'/'.str_replace('\\','/',$regStyleUrl);
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
     * @var array
     */
    private static $_filterBind = array();

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
        /*switch($function){
            case "isset":
                return (isset($value)) ? $value : null;
                break;
            case "empty":
                return (!empty($value)) ? $value : null;
                break;
            case "isset-empty":
                return (isset($value) AND !empty($value)) ? $value : null;
                break;
            default:
                throw new Exception("Ошибка второй параметр не зарегестрирован в методе.");
        }*/
    }


}