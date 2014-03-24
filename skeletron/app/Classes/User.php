<?php

namespace App;
 
class User
{

    public static function id()
    {
        if (isset($_COOKIE['qmuId'])) {
            return $_COOKIE['qmuId'];
        } else {
            return false;
        }
    }

    public static function auth($qmuId, $time = null)
    {
        $time = (int)(!is_null($time)) ? $time : 3600 * 24 * 7;
        addCookie('qmuId', $qmuId, time() + $time, '/');
    }

    public static function unAuth()
    {
        addCookie('qmuId', '', time() - 1, '/');
    }
} 