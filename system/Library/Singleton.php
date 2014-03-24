<?php

namespace Classes;

class Singleton {
    protected static $instance;
    private function __construct(){ }
    private function __clone(){ }
    private function __wakeup(){ }
    public static function getInstance() {
        if ( !isset(self::$instance) ) {
            self::$instance = new self();
            return self::$instance;
        }
        return self::$instance;
    }
}