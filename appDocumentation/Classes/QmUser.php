<?php
/**
 * Created by PhpStorm.
 * User: Werdffelynir
 * Date: 08.12.13
 * Time: 3:59
 */

class QmUser {

    public static function id()
    {
        if(isset($_COOKIE['qmuId'])){
            return $_COOKIE['qmuId'];
        }else{
            return false;
        }
    }

    public static function auth($qmuId, $time=null)
    {
        $time = (int) (!is_null($time))? $time : 3600*24*7;
        setcookie('qmuId', $qmuId, time() + $time, '/');
    }

    public static function unAuth()
    {
        setcookie('qmuId', '', time() -1, '/');
    }
} 