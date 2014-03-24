<?php
/**
 * MVC PHP Framework Quick Minimalism
 * Version: 0.3.0
 * File:    Base.php
 * Author:  OL Werdffelynir [http://w-code.ru] [oleg@w-code.ru]
 * Date:    11.03.14
 * Docs:    http://qm.w-code.ru
 *
 * Базовые методы, наследуються конструктивными обаботчиками:
 * Controller
 * Component
 */

namespace Core;


class Base
{

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
    public $_chunk;
    

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
	        $toInclude = $view.'.php';
        }else{
	        $toInclude = 'Views'.DS.$view.'.php';
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
     * Устанавлевает передачу данных в вид или шаблон напрямкю.
     * Первый аргумет регестрация имени, второй массив с данными
     * Работает подобно data, аналог но с иным подходом
     *
     *<pre>
     * Пример:
     * $this->setVar("myVar", array( "var" => "value" ));
     *
     * myVar регестрация имени, при обращении:
     * $this->var['__val__'];
     * или многомерный
     * $this->var['__my_data__']['__val__'];
     * возвращает переданые данные.
     *</pre>
     *
     * @param string $varName зарегестрированое имя
     * @param array $dataVar передача данных
     */
    public function setVar($varName, array $dataVar = null)
    {
        foreach ($dataVar as $keyVar => $valueVar) {
            if (!is_numeric($keyVar)) {
                $this->var[$varName][$keyVar] = $valueVar;
            } else {
                $this->var[$varName] = $valueVar;
            }
        }
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
            $viewInclude = $chunkView.'.php';
        }else{
            $viewInclude = 'Views'.DS.$chunkView.'.php';
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
     * Вызов зарегестрированого чанка. Первый аргумент имя зарегестрированого чанка
     * второй тип возврата метода по умолчанию ECHO, если FALSE данные будет возвращены
     *
     * <pre>
     * Пример:
     *  $this->chunk("myChunk");
     * </pre>
     *
     * @param  string $chunkName
     * @param  bool $e
     * @return bool
     */
    public function chunk($chunkName, $e = true)
    {
        if (isset($this->_chunk[$chunkName])) {
            if ($e)
                echo $this->_chunk[$chunkName];
            else
                return $this->_chunk[$chunkName];
        } else {
            if(App::$debug){
                App::ExceptionError('ERROR Undefined chunk',$chunkName);
            }else
                return null;
        }
    }

    
    /**
     * Alias. Массив с сгенерироваными видами URL к приложегнию
     *
     * App::$getURL['base'] полный url "http://my-site.loc/qmfarmework"
     * App::$getURL['public'] полный url "http://my-site.loc/qmfarmework/public"
     * App::$getURL['str'] полный url без приставки запроса http "my-site.loc/qmfarmework"
     * App::$getURL['nude'] часть url что после домена "qmfarmework/public"
     * App::$getURL['safe'] полный url безопасный
     * App::$getURL['host'] домен "my-site.loc"
     *
     * @param string $param
     * @param bool $e
     * @return null
     */
    public function getUrl($param='base', $e = true)
    {
        if (isset(App::$getURL[$param])) {
            if ($e)
                echo App::$getURL[$param];
            else
                return App::$getURL[$param];
        } else {
            return null;
        }
    }
    
    /**
     * Alias. Содержит елементы url строки и роутов на приложение,
     *
     * @var array App::$router
     * App::$router['request']
     * App::$router['slug']
     * App::$router['controllerFolder']
     * App::$router['controller']
     * App::$router['method']
     * App::$router['args']
     * App::$router['argsReg']
     *
     * @param $param
     * @param bool $e
     * @return null
     */
    public function getRoute($param, $e = false)
    {
        if (isset(App::$router[$param])) {
            if ($e)
                echo App::$router[$param];
            else
                return App::$router[$param];
        } else {
            return null;
        }
    }
    
    
    /** 
     * Вытягивает параметры конфигурации переработаных Ядром системы
     *
     * @param   $param  
     * @return  mixed
     */
    public static function getConfig($param)
    {
        if (isset(App::$config[$param]))
            return App::$config[$param];
        else
            App::ExceptionError('Config param not found!');
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
} // END class Base