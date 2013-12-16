<?php
/**
 * Файл был сгенерирован с помощю Geany Qm Framework
 *
 * Необходимо провести реорганизацию кода даного контролера
 */

class ControllerIndex extends Controller{

    /** Временно хранит имена видов */
    public $view;

    /** Временно хранит имена видов */
    protected $tpl;

    /** Розпаковывать массивы в виде */
    protected $extracted = false;

    /** Конструктор задает последовательность загрузки методов */
    public function __construct()
    {
        parent::__construct();
    }


    /** Временно хранит имена видов */
    public function before()
    {
        #Code...
    }

    /** Временно хранит имена видов */
    public function after()
    {
        #Code...
    }

    /** "actionIndex" запускаеться по умолчанию и являеться ответом на роут типа 
    имея_контролера/метод. Все методы с приставкой "action" доступны через роуты
    и используються в основном для ответов как с видами так и без или для вывода страниц, 
    ajax ответов. */
    public function actionIndex()
    {
        #Code...
    }

    /** Временно хранит имена видов */
    public function actionAjaxRequest()
    {
        if($this->isAjax){
            $someData = $_POST['someData'];
            #Code...
        }
    }

    /** Методы без приставки "action" не имебт доступу с вне, и используються как 
    обычные методы. */
    public function myMethod()
    {
        #Code...
    }

}