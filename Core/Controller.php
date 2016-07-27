<?php

namespace Core;


class Controller
{
    protected $app;
    protected $data;
    protected $model;
    protected $params;

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return mixed
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @return mixed
     */
    public function getParams()
    {
        return $this->params;
    }

    public function __construct($data = array())
    {
        $this->data = $data;
 //       $this->data['user'] = \App::getInstance()->getComponent('auth')->getCurrentUser();
        $this->app = \App::getInstance();
        $this->params = \App::getRouter()->getParams();
    }


}