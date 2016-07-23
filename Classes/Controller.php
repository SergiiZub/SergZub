<?php


namespace Classes;


abstract class Controller {
    /**
     * @var \App $app
     */
    protected $app;

    public function __construct() {
        $this->app = \App::getInstance();
    }


}