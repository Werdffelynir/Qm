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


    /** @var array $scripts - Хранит в массиве все зарегестрированые пути к скриптам */
    public $scripts = array();

    /** @var array $styles - Хранит в массиве все зарегестрированые пути к файлам стилей */
    public $styles = array();


    /**
     * $this->registerScript(_path_, name, showIn='header');
     *
     * @param  $scriptPath
     * @param  $name
     * @param  $showIn        отображения на странице 'header' - в хедере, 'footer' - в футере
     * @return bool
     */
    public function registerScript( $scriptPath, $name, $showIn='header' )
    {
        if(file_exists($scriptPath)){

            $this->scripts[] = array(
                'path' => $scriptPath,
                'name' => $name,
                'showIn' => $showIn,
                'position' => count($this->scripts),
            );
        }else{
            return false;
        }
    }


    /**
     * $this->registerStyle(_path_, name, showIn='header');
     *
     * @param  $scriptPath
     * @param  $name
     * @param  $showIn        отображения на странице 'top' - в хедере, 'footer' - в футере
     * @return bool
     */
    public function registerStyle( $scriptPath, $name, $showIn='header' )
    {
        if(file_exists($scriptPath)){

            $this->styles[] = array(
                'path' => $scriptPath,
                'name' => $name,
                'showIn' => $showIn,
                'position' => count($this->scripts),
            );

        }else{
            return false;
        }
    }


    /**
     * $this->addScript(name, show);
     *
     * @param  $scriptPath
     * @param  $showIn        отображения на странице 'header' - в хедере, 'footer' - в футере
     * @return bool
     */
    public function addScript( $scriptPath, $showIn='header' )
    {

    }


    /**
     * $this->addScript(name, position);
     *
     * @param  $scriptPath
     * @param  $showIn        отображения на странице 'header' - в хедере, 'footer' - в футере
     * @return bool
     */
    public function addStyle( $scriptPath, $showIn='header' )
    {

    }


    /**
     *
     * $this->showScripts();
     */
    public function showScripts()
    {

    }


    /**
     *
     * $this->showScripts();
     */
    public function showStyles()
    {

    }


    /**
     *
     * $this->showScripts();
     */
    public function showHeader()
    {
        $toHead = $this->showStyles();
        $toHead .= $this->showScripts();
        return $toHead;
    }

    /**
     *
     * $this->showScripts();
     */
    public function showFooter()
    {

    }










































    /*
        public function setWidget($widgetName)
        {
            return $widgetName;
        }


        public function widget($className, $properties=array(), $captureOutput=false)
        {
            if($captureOutput){
                ob_start();
                ob_implicit_flush(false);
                $widget = $this->createWidget($className, $properties);
                //$widget->run();
                return ob_get_clean();
            }else{
                //$widget = $this->createWidget($className,$properties);
                //$widget->run();
                return $widget;
            }
        }

        public function createWidget($className, $properties=array())
        {
            //$widget=Yii::app()->getWidgetFactory()->createWidget($this,$className,$properties);
            //$widget->init();
            //return $widget;
        }

        public function beginWidget($className,$properties=array())	{
            $widget=$this->createWidget($className,$properties);
            $this->_widgetStack[]=$widget;
            return $widget;
        }

        public function endWidget($id=''){
            if(($widget = array_pop($this->_widgetStack))!==null){
                $widget->run();
                return $widget;
            }
        }
    */


    /**
     * Begins recording a clip.
     * This method is a shortcut to beginning {@link CClipWidget}.
     * @param string $id the clip ID.
     * @param array $properties initial property values for {@link CClipWidget}.

    public function beginClip($id,$properties=array()){
    $properties['id']=$id;
    $this->beginWidget('CClipWidget',$properties);
    }

    public function endClip()	{
    $this->endWidget('CClipWidget');
    }

    public function beginWidget($className,$properties=array())	{
    $widget=$this->createWidget($className,$properties);
    $this->_widgetStack[]=$widget;
    return $widget;
    }

    public function endWidget($id=''){
    if(($widget = array_pop($this->_widgetStack))!==null){
    $widget->run();
    return $widget;
    }
    }

    public function widget($className,$properties=array(),$captureOutput=false)
    {
    if($captureOutput){
    ob_start();
    ob_implicit_flush(false);
    $widget=$this->createWidget($className,$properties);
    $widget->run();
    return ob_get_clean();
    }else{
    $widget=$this->createWidget($className,$properties);
    $widget->run();
    return $widget;
    }
    }

    public function createWidget($className,$properties=array())
    {
    $widget=Yii::app()->getWidgetFactory()->createWidget($this,$className,$properties);
    $widget->init();
    return $widget;
    }
     */

}
