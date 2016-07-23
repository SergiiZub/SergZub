<?php
//
//
use Core\Route;
//
//final class App
//{
//    static private $instance;
//
//    private $route_list = [];
//
//    private $components = [];
//
//    private $view;
//
//    private $config;
//
//    /**
//     * @return self
//     */
//    public static function getInstance() {
//        if (!self::$instance) {
//            self::$instance = new self();
//        }
//        return self::$instance;
//    }
//
//    private function __construct() {}
//
//    private function __wakeup() {}
//
//    private function __clone() {}
//
//    public function init($config_path) {
//        $this->config = require_once $config_path;
//        $this->view = new \Core\View();
//
//        /**
//         * @var \Core\Component $component
//         */
//        foreach ($this->components as $component) {
//            $component->init();
//        }
//    }
//
//    public function addRoute($uri, $controller_cls, $action_name = 'index', $action_access = null) {
//        $controller = new $controller_cls;
//        $this->route_list[] = new Route($uri, $controller, $action_name, $action_access);
//    }
//
//    /**
//     * @return mixed
//     */
//    public function getConfig($key = null) {
//        return ($key ? $this->config[$key] : $this->config);
//    }
//
//    public function getComponent($name) {
//        return $this->components[$name];
//    }
//
//
//
//    public function addComponent($name, $component_cls) {
//        $this->components[$name] = $component_cls;
//    }
//
//    public function getView() {
//        return $this->view;
//    }
//
//    public function inspect() {
//        $uri_params = explode('?', $_SERVER['REQUEST_URI']);
//        $route_path = $uri_params[0];
//
//        $this->getComponent('auth')->middleware($this->getComponent('db'));
//
//        foreach ($this->route_list as $route) {
//            if ($route->inspect($route_path)) {
//                return $route->callAction();
//            }
//        }
//        return false;
//    }
//}

final class App {
    static private $instance;

    /**
     * @var array $config
     */
    private $config;

    /**
     * @var array $components
     */
    private $components = [];

    private $view;

    /**
     * @var array $route_list
     */
    private $route_list = [];

    /**
     * @return self
     */
    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {}

    private function __wakeup() {}

    private function __clone() {}

    public function getConfig($key = null) {
        return ($key ? $this->config[$key] : $this->config);
    }

    public function init($path2config) {
        $this->config = require_once $path2config;

        $this->view = new \Core\View();

        /**
         * @var \Core\Component $component
         */
        foreach ($this->components as $component) {
            $component->init();
        }
    }

    /**
     * @return View
     */
    public function getView() {
        return $this->view;
    }

    public function addRoute($uri, $controller_cls, $action_name = 'index', $action_access = null) {
        $controller = new $controller_cls;
        $this->route_list[] = new Route($uri, $controller, $action_name, $action_access);
    }

    public function addComponent($name, $component_cls) {
        $this->components[$name] = new $component_cls;
    }

    public function getComponent($name) {
        return $this->components[$name];
    }

    public function inspect() {
        $uri_params = explode('?', $_SERVER['REQUEST_URI']);
        $route_path = $uri_params[0];

        $this->getComponent('auth')->middleware($this->getComponent('db'));

        /**
         * @var Route $route
         */
        foreach ($this->route_list as $route) {
            if ($route->inspect($route_path)) {
                return $route->callAction();
            }
        }
        return false;
    }
}