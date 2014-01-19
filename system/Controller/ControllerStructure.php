<?php

class ControllerStructure extends Controller{


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
                include PATH_APP_STRUCTURE.$viewName.".php";
            }
        } else {
            include PATH_APP_STRUCTURE.$viewName.".php";
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
                include PATH_APP_STRUCTURE.$output[1].".php";
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
                include PATH_APP_STRUCTURE.$view.".php";
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
            include PATH_APP_STRUCTURE.$OmData['view'].".php";
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
            $keyInclude = PATH_APP_STRUCTURE.$dataView[0].".php";
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
     * Метод для подключение моделей, параметром берет созданый раньше класс Метода
     * Возвращает обект модели с ресурсом подключеным к базе данных
     *
     * @param  string    $modelPath   Имя класса модели
     * @return bool|object
     */
    public function model( $modelPath )
    {
        if(file_exists(PATH_APP_STRUCTURE.$modelPath.".php")) {

            include PATH_APP_STRUCTURE.$modelPath.".php";

            if(strpos($modelPath, '/') === false){

                $newModel = new $modelPath();

                return (object) $newModel;

            }else{

                $modelArrayPath = explode('/',$modelPath);
                $modelName = $modelArrayPath[sizeof($modelArrayPath)-1];
                $model = new $modelName();

                return (object) $model;
            }

        }else{
            return false;
        }
    }

}