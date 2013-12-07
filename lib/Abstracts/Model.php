<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Comp-2
 * Date: 29.11.13
 * Time: 19:12
 * To change this template use File | Settings | File Templates.
 */

abstract class Model {

    public function __construct()
    {
        $this->beforeModel();
        $this->db = new SimplePDO();
        $this->afterModel();
    }
    public function beforeModel(){}
    public function afterModel(){}

    //public function model(){}

}
