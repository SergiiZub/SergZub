<?php

namespace Classes;

class Config
{
    protected static $settings = array();
    
    public static function get($key = null){
        return isset(self::$settings[$key]) ? self::$settings[$key] : self::$settings;
    }
    
    public static function set($key, $value){
        self::$settings[$key] = $value;
    }
}