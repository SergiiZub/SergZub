<?php


namespace Core;


abstract class Controller
{
    protected $app;

    /**
     * Controller constructor.
     * @var \App $app
     */
    public function __construct() {
        $this->app = \App::getInstance();
    }


    abstract public function index();

  //  abstract public function admin_index();
}