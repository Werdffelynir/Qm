<?php
/**
 * MVC PHP Framework Quick Minimalism
 * Version: 0.3.0
 * File:    Controller.php
 * Author:  OL Werdffelynir [http://w-code.ru] [oleg@w-code.ru]
 * Date:    11.03.14
 * Docs:    http://qm.w-code.ru
 *
 * Обработчик сруктур приложения.
 */

namespace Core;


class Controller extends Base
{

    /**
     * Активный файл основного шаблона входящего потока
     * @var string $layout
     */
    public $layout = null;


    /**
     * Активный файл вида
     * @var string $view
     */
    public $view = null;


    /**
     * Свойство передачи в вид или шаблон части вида
     * Работет совместно с методом setChunk()
     */
    public $chunk;
    


    /**
     * Контролер устанавлевает конфиг-настройки и
     * определяет языковый файл та инициилизирует его.
     */
    public function __construct()
    {
        $this->before();

        if($this->layout == null)
            $this->layout = App::$config['defaultLayoutStartFile'];

        if($this->view == null)
            $this->view = App::$config['defaultViewStartFile'];

        /*
                if(App::getCookie('lang') != null){
                    self::$langCode = App::getCookie('lang');
                }elseif(isset(App::$config['language']) AND self::$langCode == null){
                    self::$langCode = App::$config['language'];
                }
        */

        $this->after();

        // Методы относящиеся к категории использующих настройки.
        //$this->initLang();
    }
    
    /**
     * Экземпляр клааса App
     */
    

    /**
     * Выполняеться до загрузки основного вида и конфигурация в контролер
     */
    public function before() {}

    /**
     * Выполняеться после загрузки основного вида и конфигурация в контролер
     */
    public function after() { }

    /**
     * Упрощения для AJAX запросов
     * <pre>
     * // Пример:
     * return array(
     * 		'gate' => 'Components/itemGet',				// go to Components/itemGet.php
     * 		'edit'=> 'Controllers/Post/UpdateAction',	// go to Controllers/Post/UpdateAction.php
     * 	);
     * </pre>
     * @return array
     */
    public function actions(){
        return array();
    }


    /**
     * Метод render() реализовывает подключение в основной шаблон (public/[template.php])
     * файла или фалов видов (app/Views/[main.php]) с контролера
     *
     *<pre>
     * Пример:
     * '__namePosition__' - имя позиции в шаблоне (public/[template.php])
     * '__fileView__'     - файл вида (app/Views/[main.php])
     * 'variable'         - переменная которая будет доступна в виде (app/Views/[main.php])
     *
     * // Одиночный вывод
     * $this->render('__namePosition__', '__fileView__', array('variable'=>'My Data'));
     *
     * // массовый вывод
     * $this->render(
     *      array(
     *          '__namePosition_1__' => array('__fileView_1__', array('variable'=>'My data')),
     *          '__namePosition_2__' => array('__fileView_2__', array('variable'=>'My data')),
     *          '__namePosition_3__' => array('__fileView_3__', array('variable'=>'My data')),
     *      )
     * );
     *
     * //В шаблоне позиции назначать следующим образом:
     * $this->renderTheme( __POSITION_NAME__ );
     * или
     * echo $this->blockName_Left;
     * </pre>
     *
     * @param array|string  $dataPos    имя позиции в шаблоне (default: public/template.php)
     * @param bool|string   $view       имя переменной в виде (default: app/Views/main.php)
     * @param array         $dataArr    данные в виде массива
     */
    public function render( $dataPos, $view=false, array $dataArr=array() )
    {
        if(is_array($dataPos))
        {
            foreach ($dataPos as $keyBlockName => $dataView) {
                if(isset($dataView[1]) AND is_array($dataView[1]))
                    extract($dataView[1]);
                $keyInclude = PATH_APP.'Views'.DS.$dataView[0].".php";
                ob_start();
                include $keyInclude;
                $this->$keyBlockName = ob_get_clean();
            }
        }
        elseif(is_string($dataPos))
        {
            if(!$view) $view = $this->view;
            if(!empty($dataArr)) extract($dataArr);
            $keyInclude = PATH_APP.'Views'.DS.$view.".php";
            ob_start();
            include $keyInclude;
            $this->$dataPos = ob_get_clean();
        }

        include PATH_PUBLIC.$this->layout.".php";

    }


    /**
     * Метод вывода в шаблон вид, совметсный с методом render()
     *
     *<pre>
     * Пример:
     * $this->renderTheme( __POSITION_NAME__ );
     *
     * // аналогично
     * echo $this->__POSITION_NAME__;
     *</pre>
     *
     * @param string $renderPosition названеи позиции указаной в контролере методом render()
     */
    public function renderTheme( $renderPosition )
    {
        if(isset($this->$renderPosition))
            echo $this->$renderPosition;
    }


    /**
     * Обработка в указном виде, переданных данных, результат возвращает.
     *
     *<pre>
     * Пример:
     * $content = $this->partial("blog/topSidebar", array( "var" => "value" ));
     *</pre>
     *
     * @param   string  $view   путь к виду, особености:
     *                              	"partial/myview" сгенерирует "app/Views/partial/myview.php"
     *                              	"//partial/myview" сгенерирует "app/partial/myview.php"
     * @param   array   $data       массив данных для передачи в вид
     * @param   bool    $e
     * @return null|string
     */
    public function partial( $view, array $data=null, $e=false )
    {

        if(empty($view)) {
            return null;
        }elseif(substr($view, 0, 2) == '//'){
            $view = substr($view, 2);
            $toInclude = PATH_APP.$view.'.php';
        }else{
            $toInclude = PATH_APP.'Views'.DS.$view.'.php';
        }

        if($data != null)
            extract($data);

        if(!is_file($toInclude)){
            if(App::$debug){
                App::ExceptionError('ERROR! File not exists!', $toInclude);
            } else {
                return null;
            }
        }

        ob_start();
        include $toInclude;
        $getContents = ob_get_contents();
        ob_clean();

        if($e)
            echo $getContents;
        else
            return $getContents;
    }

    /**
     * Метод работает как и partial(), но подключает файлы с директории Languages (по умолчанию),
     * и выберает файл взаимозависимости от активного языка на данный момент
     * Файлы должны иметь в конце имени приставку названия языка (textLogo_ru, textLogo_ua, textLogo_en)
     * параметр метода имя файла без приставки partialLang('textLogo');
     *
     * Достать перевод документ
     */
    public static function partialLang($file, array $data=null, $e=false)
    {
        if(substr($file, 0, 2) == '//'){
            $file = substr($file, 2);
            //$toInclude = ROOT.App::$config['appPath'].DS.$file.'.php';
            $toInclude = PATH_APP.$file.'_'.App::$langCode.'.php';
        }else{
            //$toInclude = App::$appPath.'Languages'.DS.$file.'_'.App::$langCode.'.php';
            $toInclude = PATH_APP.'Languages'.DS.$file.'_'.App::$langCode.'.php';
        }

        if(!is_file($toInclude))
            if(App::$debug)
                App::ExceptionError('ERROR! File not exists!', $toInclude);
            else return null;

        ob_start();

        if($data != null)
            extract($data);
        
        include $toInclude;
        $getContents = ob_get_contents();
        ob_clean();

        if($e)
            echo $getContents;
        else
            return $getContents;
    }


    /**
     *
     * Обработка в указаном виде, переданых данных, результат будет передан в основной вид или тему по указаному $chunkName,
     * также есть возможность вернуть результат в переменную указав четвертый параметр в true.
     *
     *<pre>
     * Пример:
     * $this->setChunk("topSidebar", "blog/topSidebar", array( "var" => "value" ));
     *
     * в вид blog/topSidebar.php передается  переменная $var с значением  "value".
     *
     * В необходимом месте основного вида или темы нужно обявить чанк
     * напрямую:
     * echo $this->chunk["topSidebar"];
     * или методом:
     * $this->chunk("topSidebar");
     *</pre>
     *
     * @param string    $chunkName  зарегестрированое имя
     * @param string    $chunkView  путь у виду чанка, установки путей к виду имеют следующие особености:
     *                              	"partial/myview" сгенерирует "app/Views/partial/myview.php"
     *                              	"//partial/myview" сгенерирует "app/partial/myview.php"
     * @param array     $dataChunk  передача данных в вид чанка
     * @param bool      $returned   по умочнию FALSE производится подключения в шаблон, если этот параметр TRUE возвращает контент
     * @return string
     */
    public function setChunk( $chunkName, $chunkView='', array $dataChunk=null, $returned=false )
    {
        // Если $chunkView передан как пустая строка, создается заглушка
        if(empty($chunkView)) {
            return $this->chunk[$chunkName] = '';
        }elseif(substr($chunkView, 0, 2) == '//'){
            $chunkView = substr($chunkView, 2);
            $viewInclude = PATH_APP.$chunkView.'.php';
        }else{
            $viewInclude = PATH_APP.'Views'.DS.$chunkView.'.php';
        }

        // Если вид чанка не существует отображается ошибка
        if(!file_exists($viewInclude)){
            if(App::$debug) {
                App::ExceptionError('ERROR! File not exists!', $viewInclude);
            } else {
                return null;
            }
        }

        if(!empty($dataChunk))
            extract($dataChunk);

        ob_start();
        ob_implicit_flush(false);
        include $viewInclude;

        if(!$returned)
            $this->chunk[$chunkName] = ob_get_clean();
        else
            return ob_get_clean();
    }


    /**
     * Вызов зарегистрированного чанка. Первый аргумент имя зарегестрированого чанка
     * второй тип возврата метода по умолчанию ECHO, если FALSE данные будет возвращены
     *
     * <pre>
     * Пример:
     *  $this->chunk("myChunk");
     * </pre>
     *
     * @param  string    $chunkName
     * @param  bool      $e
     * @return bool
     */
    public function chunk( $chunkName, $e=true )
    {
        if(isset($this->chunk[$chunkName])){
            if($e)
                echo $this->chunk[$chunkName];
            else
                return $this->chunk[$chunkName];
        }else{
            if(App::$debug){
                App::ExceptionError('ERROR Undefined chunk',$chunkName);
            }else
                return null;
        }
    }


    /**
     * Метод для Вывода ошибки 404
     *
     * @param string    $file
     * @param array     $textData
     * @param bool      $e
     * @return string
     */
    public static function error404($file=null, array $textData=null, $e=true)
    {
        $file=($file==null)?PATH_PUBLIC.'error404':$file;
        $c = new self();
        if($e)
            echo $c->partial($file, $textData);
        else
            return $c->partial($file, $textData);
    }


    /**
     * Метод для проверки являеться ли запрос AJAX
     * @return bool
     */
    public function isAjax()
    {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            return true;
        } else {
            return false;
        }
    }


    /**
     * Метод возвращает параметры переданные через строку запроса.
    основное предназначение это передача неких параметров, но все же
    можно найти множество других применений для этого метода.
     *
     * <pre>
     * Например: http://site.loc/edit/page/id/215/article/sun-light
    /edit/page/                 - это контролер и екшен, они пропускаются
    $this->urlArgs()            - id возвращает первый аргумент
    $this->urlArgs(1)           - id аналогично, но '1' != 1
    $this->urlArgs(3)           - возвращает третий аргумент article
    $this->urlArgs('id')        - возвращает следующий после указного вернет 215
    $this->urlArgs('article')   - sun-light
    $this->urlArgs('getArray')  - массив всех элементов "Array ( [1] => edit [2] => page [3] => id [4] => 215 [5]..."
    $this->urlArgs('getString') или 'slug' - строку всех элементов "edit/page/id/215/article/sun-light"
    $this->urlArgs('getController')
    $this->urlArgs('getMethod')
    $this->urlArgs('edit', 3)   - 215 (3 шага от 'edit')
     * </pre>
     *
     * @param bool $param
     * @param int $element
     * @return array|string
     */
    public static function urlArgs($param=false, $element=1)
    {
        $args=App::$router['args'];
        $argsReg=App::$router['argsReg'];

        if(empty($args)) return null;

        if($argsReg AND count($argsReg)>1){
            if(isset($argsReg[$param]))
                return $argsReg[$param];
        }

        // Вернет первый аргумент
        if(!$param) {
            return $args[0];

        // Вернет по номеру аргумент
        }elseif( is_int($param) ){
            $pNum = $param - 1;
            return (isset($args[$pNum])) ? $args[$pNum] : null;

        // Вернет все аргументы в массиве
        }elseif($param == 'getArray'){
            return $args;

        // Вернет все аргументы в строке
        }elseif($param == 'slug' || $param == 'getString' ){
            return $args['slug'];

        }elseif($param == 'getController'){
            return App::$router['controller'];

        }elseif($param == 'getMethod'){
            return App::$router['method'];

        // Вернет аргумент следующий после указаного
        }else{
            if(in_array($param, $args)){
                if($element > 0){
                    $keyElement = array_search($param, $args);
                    $key = $keyElement+$element;
                    return (isset($args[$key]))?$args[$key]:null;
                } else {
                    return $param;
                }
            }else{
                return null;
            }
        }
    }



    /**
     * Метод для подключение моделей, параметром берет созданный раньше класс Модели
    Возвращает объект модели с ресурсом подключенным к базе данных
     *
     * @param   string          $modelName      Имя класса модели
     * @return  bool|object
     */
    public function model($modelName)
    {
        if (class_exists($modelName)) {
            $model = new $modelName();
            return (object) $model;
        }else{
            if(App::$debug)
                App::ExceptionError('ERROR model class not exists!',$modelName);
        }
    }


/**
 * scriptRegister()
 * scriptFixed()
 * scriptTrigger()
 *
 * styleRegister()
 * styleFixed()
 * styleTrigger()
*/
    protected $_scripts=array();
    protected $_styles=array();
    protected $scripts=array();
    protected $styles=array();


    /**
     *
     * Регистрация скрипта javascript в области видимости контролера
     *
     * <pre>
     * $this->scriptRegister(array(
     *      'url'       => путь к скрипту
     *      'name'      => регистрационное имя
     *      'showIn'    => отображения на странице 'header' или 'footer' (по умолчанию 'header')
     *      'priority   => приоритет вывода или позиция (по умолчанию 10)
     * ));
     * <pre>
     *
     * @param array $scriptData Массив параметров для регистрации скрипта
     * @return bool
     */
    public function scriptRegister(array $scriptData)
    {

        if(substr($scriptData['url'], 0, 2) == '//'){
            $url = substr($scriptData['url'], 2);
            $url = App::$getURL['base'].'/'.$url.'.js';
        }else{
            $url = App::$getURL['public'].'/'.$scriptData['url'].'.js';
        }

        $this->_scripts[$scriptData['name']] = array(
            'url' => $url,
            'name' => $scriptData['name'],
            'priority' => (isset($scriptData['priority'])) ? $scriptData['priority'] : 10,
            'group' => (isset($scriptData['group'])) ? $scriptData['group'] : null,
        );

    }


    /**
     * Регистрация стиля CSS в области видимости контролера
     *
     * <pre>
     * $this->styleRegister(array(
     *      'url'       => url путь к скрипту 'css/my_style'
     *      'name'      => регистрационное имя
     *      'showIn'    => отображения на странице 'header' или 'footer' (по умолчанию 'header')
     *      'priority   => приоритет вывода или позиция (по умолчанию 10)
     * ));
     * <pre>
     * @param  array $styleData Массив параметров для регистрации скрипта
     * @return bool
     */
    public function styleRegister(array $styleData)
    {
        if(substr($styleData['url'], 0, 2) == '//'){
            $url = substr($styleData['url'], 2);
            $url = App::$getURL['base'].'/'.$url.'.css';
        }else{
            $url = App::$getURL['public'].'/'.$styleData['url'].'.css';
        }

        $this->_styles[$styleData['name']] = array(
            'url' => $url,
            'name' => $styleData['name'],
            'priority' => (isset($styleData['priority'])) ? $styleData['priority'] : 10,
            'group' => (isset($styleData['group'])) ? $styleData['group'] : null,
        );

    }

    /**
     * Фиксация зарегестрированых скриптов для дальнейшего вывода scriptTrigger()
     * Фиксация используеться для определения необходимости вывода скриптов в
     * различных частях приложения
     *
     * @param string|array|bool $regScriptName  имя или имена зарегистрированых скриптов
     * @param  bool             $isVisibly      возможность не отображать указаные
     * @return bool
     */
    public function scriptFixed($regScriptName=false, $isVisibly=true)
    {
        if(!$regScriptName){
            $this->scripts = $this->_scripts;
        }elseif (is_string($regScriptName)) {

            $scripts=$this->_scripts;
            foreach ($scripts as $s) {
                if ($s['name'] == $regScriptName) {
                    if (!$isVisibly) {
                        unset($this->scripts[$regScriptName]);
                    } else {
                        $this->scripts[$regScriptName] = $s;
                    }
                }
            }
        }elseif (is_array($regScriptName)) {
            $scripts=$this->_scripts;
            foreach ($scripts as $s) {
                if (in_array($s['name'],$regScriptName)) {
                    if (!$isVisibly) {
                        unset($this->scripts[$s['name']]);
                    } else {
                        $this->scripts[$s['name']] = $s;
                    }
                }
            }
        } else {
            return false;
        }

    }
    public function styleFixed($regStyleName=null, $isVisibly=true)
    {
        if($regStyleName==null){
            $this->styles = $this->_styles;
        }elseif (is_string($regStyleName)) {
            $styles=$this->_styles;
            foreach ($styles as $s) {
                if ($s['name'] == $regStyleName) {
                    if (!$isVisibly) {
                        unset($this->styles[$regStyleName]);
                    } else {
                        $this->styles[$regStyleName] = $s;
                    }
                }
            }
        }elseif (is_array($regStyleName)) {
            $styles=$this->_styles;
            foreach ($styles as $s) {
                if (in_array($s['name'],$regStyleName)) {
                    if (!$isVisibly) {
                        unset($this->styles[$s['name']]);
                    } else {
                        $this->styles[$s['name']] = $s;
                    }
                }
            }
        } else {
            return false;
        }
    }

    public function scriptTrigger($group=null)
    {
        $temp_scr = "";
        if($group==null){
            $scripts=$this->scripts;
            usort($scripts, function($a,$b)use($scripts){ if ($a == $b) return false; return ($b['priority'] > $a['priority']) ? -1 : 1;});
            foreach ($scripts as $script){
                if($script['group']==null)
                    $temp_scr .= '<script type="text/javascript" src="' . $script['url'] . '"></script>' . "\n";
            }
            echo $temp_scr;
        }else{
            $scripts=$this->scripts;
            usort($scripts, function($a,$b)use($scripts){ if ($a == $b) return false; return ($b['priority'] > $a['priority']) ? -1 : 1;});
            foreach ($scripts as $script){
                if($script['group']==$group)
                    $temp_scr .= '<script type="text/javascript" src="' . $script['url'] . '"></script>' . "\n";
            }
            echo $temp_scr;
        }
    }

    public function styleTrigger($group=null)
    {
        $temp_href = "";
        if($group==null){
            $styles=$this->styles;
            usort($styles, function($a,$b)use($styles){ if ($a == $b) return false; return ($b['priority'] > $a['priority']) ? -1 : 1;});
            foreach ($styles as $style)
                $temp_href .= '<link rel="stylesheet" type="text/css" href="' . $style['url'] . '" />' . "\n";
            echo $temp_href;
        }else{
            $styles=$this->styles;
            usort($styles, function($a,$b)use($styles){ if ($a == $b) return false; return ($b['priority'] > $a['priority']) ? -1 : 1;});
            foreach ($styles as $style){
                if($style['group']==$group)
                    $temp_href .= '<link rel="stylesheet" type="text/css" href="' . $style['url'] . '" />' . "\n";
            }
            echo $temp_href;
        }
    }


    /** 
     * Alises \Core\App methods
     ************************************************************************ */
    

    /**
     * Алис класса App{} initLang()
     * Инициализация языка.
     */
    public function initLang($langCode=false, $cookie=true)
    {
        App::initLang($langCode, $cookie);
    }

    /**
     * Алис класса App{} lang()
     * Доступк к переводу и атрибутам языка.
     */
    public function lang($key, $e=false)
    {
        return App::lang($key, $e);
    }
    public function __($key, $e=true)
    {
        return App::lang($key, $e);
    }



} // END CLASS 'Controller'