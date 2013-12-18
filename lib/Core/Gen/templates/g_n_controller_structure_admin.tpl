<?php
/**
 * Файл был сгенерирован с помощю Gen Qm Framework
 *
 * Необходимо провести реорганизацию кода даного контролера
 */

class ControllerAdministrator extends Controller{

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

    /** Метод отрабатывает поле загрузки всех классов, в том числе и с каталога "Classes" */
    public function after()
    {
        #Code...
    }

    /** "actionIndex" запускаеться по умолчанию и являеться ответом на роут типа 
    имея_контролера или имея_контролера/метод. */
    public function actionIndex()
    {
        $this->data['title'] = ' Structure-Администратора';
        $this->data['content'] = ' Structure Structure StructureАдминистратора Content';

        echo 'Structure ControllerAdministrator.php actionIndex';

        #Code...
        //$this->show('main');
    }

    public function actionLogin()
    {
        $this->data['title'] = ' Structure Администратора Login';
        $this->data['content'] = ' Администратора ContentStructure';

        echo 'Structure ControllerAdministrator.php actionLogin';

        //$this->show('main');
    }


    /** Временно хранит имена видов */
    public function actionAjaxRequest()
    {
        if($this->isAjax()){
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