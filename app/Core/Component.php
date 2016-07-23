<?php


namespace Core;


abstract class Component
{
    protected $config;

    public function __construct() {
        $this->config = \App::getInstance()->getConfig();
    }

    static public function register($name) {
        \App::getInstance()->addComponent($name, static::class);
    }

    abstract function init();
}