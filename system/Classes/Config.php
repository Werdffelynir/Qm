<?php
/**
 * Created by PhpStorm.
 * User: Comp-2
 * Date: 09.01.14
 * Time: 14:01
 */

class Config {

    static public $sys=array();
    static public $app=array();
    static public $all=array();

    public function __construct()
    {
        $configApplication = include PATH_APP.'configApplication.php';
        $configAutoload    = include PATH_APP.'configAutoload.php';
        self::$sys         = include PATH_SYS.'configSystem.php';
        self::$app = array_merge($configApplication, $configAutoload);
        self::$all = array_merge(self::$sys, self::$app);
    }

    static public function param($key)
    {
        if(array_key_exists($key, self::$sys)) {
            return self::$sys[$key];
        }elseif(array_key_exists($key, self::$app)) {
            return self::$app[$key];
        }else{
            return false;
        }
    }

    static public function all()
    {
        return self::$all;
    }

} 