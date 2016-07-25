<?php


namespace Classes;


abstract class Controller {
    /**
     * @var \App $app
     */
    protected $app;
    protected $params;

    public function __construct() {
        $this->app = \App::getInstance();
        $this->params = $this->app->getParams();
    }

    abstract function index();
}