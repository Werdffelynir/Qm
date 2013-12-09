<?php

abstract class Controller extends Base {


    /**
     * Временно хранит имена видов
     */
    public $view;


    /**
     * Временно хранит передаваемые в вид данные
     * используеться совместно с методом showData()
     */
    protected $data    = array();


    /**
     * Временно хранит имена видов
     */
    protected $tpl;


    /**
     * Временно хранит имена позиций в виде для метода out()
     */
    protected $outThemePosition = array();


    /**
     * Розпаковывает масивы (стандартная PHP функция)
     * Если указано распаковка с масива в контролере, вложеные двнные
     * будут доступны в роспакованой форме.
     * РЕКОМЕНДОВАНО для небольших приложений, использывать для свойства $data
     * После включения даные будут виддны не $data['myTitle'] а $myTitle
     */
    protected $extracted = false;




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
     * Конструктор задает последовательность загрузки методов
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Метод для вывод данных в виде. Создан для совместного использования с методом
     * show(). Назначение данных производить в контроллере:
     * $this->data['myTitle'] = "Это мой заголовок к статье!"ж
     * в виде:
     * $this->showData('myTitle');
     */
    public function showData($dataParam, $e=true)
    {
        $this->beforeShow();
        if(array_key_exists($dataParam, $this->data)){
            if($e)
                echo $this->data[$dataParam];
            else
                return $this->data[$dataParam];
        } else {
            return '';
        }
        $this->afterShow();
    }
    public function beforeShow(){}
    public function afterShow(){}


    /**
     * Метод подключения видов с контролеров или шаблона (theme).
     * Принцип метода подключение функцией include() динамической составной преложения
     * по умолчанию это центральный динамический блок страницы, при этом header и footer
     * остаються относительно статическими
     *
     * для контролеров, елементарное подключение вида назначаеться методом show() без параметров:
     * out('name_view');
     *
     * для контролеров, подключает прямо один вид без layout
     * out('name_view', true);
     *
     * Нзначение вывода в шаблоне (theme) layout
     * out('name_view', true, true);
     */

    public function show($viewName, $nested=false, $theme=false)
    {
        /** Если указано распаковка с масива в контролере, вложеные двнные
         *  будут доступны в виде в роспакованой форме
         */
        if($this->extracted == true AND !empty($this->data))
            extract($this->data);

        if(!$theme){
            if(!$nested){
                include PATH_THEME.$this->layout.".php";
            } else {
                include PATH_APP_VIEWS.$viewName.".php";
            }
        } else {
            include PATH_APP_VIEWS.$viewName.".php";
        }
    }

    /**
     *
     * для контролеров, если используеться один Position
    $this->out( '__namePosition__' , '__nameView__' , $myArrayData );
     *
     * для контролеров, если используеться несколько Position
    $this->out(array(
    array('__namePositionOne__', '__nameViewOne__', $myArrayDataOne),
    array('__namePositionTwo__', '__nameViewTwo__', $myArrayDataTwo),
    ...
    ));
     *
     * для контролеров, без layout импользывать четвертый параметр с true
     * out('name_view', true);
     *
     * Нзначение вывода в шаблоне (theme) layout. Происходит другим методом outTheme
     * outTheme('__namePosition__', $OmData);
     * Имя переменной $OmData обезательно без изминений
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
                include PATH_APP_VIEWS.$output[1].".php";
                $layouts[$countLayouts]['position'] = $output[0];
                //$this->outThemePosition[count($this->outThemePosition)+1] = $output[0];
                $layouts[$countLayouts]['view'] = $output[1];
                $layouts[$countLayouts]['data'] = $output[2];
                ob_clean();
                $countLayouts++;
            }
            $OmData = $layouts;
            include PATH_THEME.$this->layout.".php";
        }
        else
        {
            if(!$nested){
                $OmData = array(
                    'position' => $position,
                    'view' => $view,
                    'data' => $data,
                );

                include PATH_THEME.$this->layout.".php";
            } else {
                include PATH_APP_VIEWS.$view.".php";
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
            include PATH_APP_VIEWS.$OmData['view'].".php";
        }
        unset($OmData);
    }


    /**
     * Метод render() реализовывает подключение в основной шаблон theme видов с контролера
     * Еще один способ, имеет иной подход от предведущих.
     *
     * Пример:
     * 'blockName_*' - перемнная в основном шаблоне 'main.php' по умолчанию,
     * 'viewLeft'    - вид в калоге Views
     * 'keyLeft'     - переменная которая будет видна в виде 'viewLeft'
     * $this->render(array(
     *    'blockName_Left'      => array('viewLeft', array('keyLeft'->'My data')),
     *    'blockName_General'   => array('viewContent', array('keyConten'->'My data')),
     *    'blockName_Right'     => array('viewRight', array('keyRight'->'My data')),
     *    )
     *);
     *
     * В шаблоне позиции назначать следующим образом:
     *  <?php echo $this->blockName_Left;
     */
    public function render( array $viewsAndData )
    {
        $this->beforeRender();
        $data = array();
        foreach ($viewsAndData as $keyBlockName => $dataView) {
            extract($dataView[1]);
            $keyInclude = PATH_APP_VIEWS.$dataView[0].".php";
            ob_start();
            include $keyInclude;
            //$data[$keyBlockName] = ob_get_contents();
            $this->$keyBlockName = ob_get_contents();
            ob_clean();
        }
        include PATH_THEME.$this->layout.".php";
        $this->afterRender();
    }
    public function beforeRender(){}
    public function afterRender(){}



    /**
     * <pre>
     * $this->registerScript(array(
     *  'path'      => путь к скрипту
     *  'name'      => регистрационное имя
     *  'showIn'    => отображения на странице 'header' - в хедере, 'footer' - в футере
     *  'position   => позиция
     * ));
     * <pre>
     * @param array $scriptData Массив параметров для регистрации скрипта
     * @return bool
     */
    public function registerScript( array $scriptData )
    {
        if(file_exists($scriptData['path'])){
            $this->_scripts[$scriptData['name']] = array(
                'path' => $scriptData['path'],
                'name' => $scriptData['name'],
                'showIn' => ($scriptData['showIn'])?$scriptData['showIn']:"header",
                'position' => count($this->_scripts)+1,
            );
        }else{
            return false;
        }
    }


    /**
     * <pre>
     * $this->registerStyle(array(
     *  'path'      => путь к скрипту
     *  'name'      => регистрационное имя
     *  'showIn'    => отображения на странице 'header' - в хедере, 'footer' - в футере
     *  'position   => позиция
     * ));
     * <pre>
     * @param array $styleData Массив параметров для регистрации скрипта
     * @return bool
     */
    public function registerStyle( array $styleData )
    {
        if(file_exists($styleData['path'])){
            $this->_styles[$styleData['name']] = array(
                'path' => $styleData['path'],
                'name' => $styleData['name'],
                'showIn' => ($styleData['showIn'])?$styleData['showIn']:"header",
                'position' => count($this->_styles)+1,
            );
        }else{
            return false;
        }
    }


    /**
     * Метод добавление скриптов в контролере. Добавляет зарегестрированые скрипты по его имени при регистрации.
     * Регистрация производиться зарание методом "$this->registerScript()"
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
     * @param  $regScriptName Зарегестрированое имя.
     * @param  $showIn        отображения на странице 'header' - в хедере, 'footer' - в футере, или 'disabled' для отключения
     * @return bool           возвращает false в случаи если скрипт не зарегестрирован
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
                            unset($this->scripts[$regScriptName]);
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
     * <pre>
     * // Добавляет скрипт 'jquery' в хедере
     * $this->addScript('myCss');
     *
     * // Отключает скрипт
     * $this->addScript('myCss', 'disabled');
     * </pre>
     * @param $regStyleName Зарегестрированое имя.
     * @param $disabled     Параметр disabled, если 'disabled', отключет его
     * @return bool         возвращает false в случаи если скрипт не зарегестрирован
     */
    public function addStyle( $regStyleName, $disabled=false )
    {
        if(is_string($regStyleName)){
            foreach($this->_styles as $_styles){
                if($_styles['name'] == $regStyleName){
                    if( $disabled == 'disabled')
                        unset($this->styles[$regStyleName]);
                    else
                        $this->styles[$regStyleName] = $_styles;
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
     * @param string $showIn    Указать позицию вывода 'header' или 'footer'
     * @param string $scrName   Указать единственый скрипт для вывода, НЕ БЕЗОПАСНЫЙ
     * @return string
     */
    public function showScripts($showIn='header', $scrName=false)
    {
        $temp_scr = "";

        if($scrName) {
            foreach( $this->scripts as $scripts){
                if($scripts['name'] == $scrName ){
                    $regScriptUrl = substr($scripts['path'], strpos($scripts['path'], QmConf("baseUrl")) + strlen(QmConf("baseUrl")) + 1 ) ;
                    $regScriptUrl = URL.'/'.str_replace('\\','/',$regScriptUrl);
                    $temp_scr .= '<script type="text/javascript" src="'.$regScriptUrl.'"></script>'."\n";
                }
            }
            return $temp_scr;

        }elseif($showIn == 'header') {
            foreach( $this->scripts as $scripts){
                if($scripts['showIn'] == $showIn ){
                    $regScriptUrl = substr($scripts['path'], strpos($scripts['path'], QmConf("baseUrl")) + strlen(QmConf("baseUrl")) + 1 ) ;
                    $regScriptUrl = URL.'/'.str_replace('\\','/',$regScriptUrl);
                    $temp_scr .= '<script type="text/javascript" src="'.$regScriptUrl.'"></script>'."\n";
                }
            }
            return $temp_scr;

        } elseif($showIn == 'footer') {
            foreach( $this->scripts as $scripts){

                if($scripts['showIn'] == $showIn ){
                    $regScriptUrl = substr($scripts['path'], strpos($scripts['path'], QmConf("baseUrl")) + strlen(QmConf("baseUrl")) + 1 ) ;
                    $regScriptUrl = URL.'/'.str_replace('\\','/',$regScriptUrl);
                    $temp_scr .= '<script type="text/javascript" src="'.$regScriptUrl.'"></script>'."\n";
                }
            }
            return $temp_scr;

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
                $regStyleUrl = substr($styles['path'], strpos($styles['path'], QmConf("baseUrl")) + strlen(QmConf("baseUrl")) + 1 ) ;
                $regStyleUrl = URL.'/'.str_replace('\\','/',$regStyleUrl);
                $temp_scr .= '<link rel="stylesheet" type="text/css" href="'.$regStyleUrl.'" />'."\n";
            }
            return $temp_scr;
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
            return App::$requestFull[3];
        }elseif($param == 'allArray'){
            return App::$requestFull;
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
}
