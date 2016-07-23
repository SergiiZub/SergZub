<?php
use Classes\View;
use Classes\Route;
use Classes\Controller;
use Models\UserModel;
use Controllers\AuthController;
use \Controllers\UserController;

//require_once
//function autoload($class) {
//    require_once strtr($class, "\\", "//") . '.php';
//}
//
//set_include_path('Interfaces');
//set_include_path('Classes');
//set_include_path('Components');
//set_include_path('Models');
//set_include_path('Controllers');
//set_include_path('lib');
//spl_autoload_register('autoload');

//final class App {
//    static private $instance;
//
//    /**
//     * @var array $config
//     */
//    private $config;
//
//    /**
//     * @var array $components
//     */
//    private $components;
//
//    private $view;
//
//    /**
//     * @var array $route_list
//     */
//    private $route_list = [];
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
//    public function getConfig($key = null) {
//        return ($key ? $this->config[$key] : $this->config);
//    }
//
//    public function init($path2config) {
//        $this->config = require_once $path2config;
//
//        $this->view = new View($this->config['templates_path'], $this->config['templates_ext']);
//
//        /**
//         * @var \Classes\Component $component
//         */
//        foreach ($this->components as $component) {
//            $component->init();
//        }
//    }
//
//    /**
//     * @return View
//     */
//    public function getView() {
//        return $this->view;
//    }
//
//    public function addRoute($uri, $controller_cls, $action_name = 'index', $action_access = null) {
//        $controller = new $controller_cls;
//        $this->route_list[] = new Route($uri, $controller, $action_name, $action_access);
//    }
//
//    public function addComponent($name, $component_cls) {
//        $this->components[$name] = new $component_cls;
//    }
//
//    public function getComponent($name) {
//        return $this->components[$name];
//    }
//
//    public function inspect() {
//        $uri_params = explode('?', $_SERVER['REQUEST_URI']);
//        $route_path = $uri_params[0];
//
//        $this->getComponent('auth')->middleware($this->getComponent('db'));
//
//        /**
//         * @var Route $route
//         */
//        foreach ($this->route_list as $route) {
//            if ($route->inspect($route_path)) {
//                return $route->callAction();
//            }
//        }
//        return false;
//    }
//}

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__FILE__) . DS);

require_once (ROOT.'lib'.DS.'App.php');
require_once (ROOT.'lib'.DS.'autoload.php');

# Registration components

Components\DbComponent::register('db');
Components\AuthComponent::register('auth');

# App init

$app = App::getInstance();
$app->init(ROOT.'lib'.DS.'config.php');

# Routing

$app->addRoute('/', \Controllers\NewsController::class);
$app->addRoute('/category', \Controllers\NewsController::class, 'getCategory');
$app->addRoute('/art', \Controllers\NewsController::class, 'getArticle');
$app->addRoute('/registration', AuthController::class, 'registration');
$app->addRoute('/login', AuthController::class, 'login');
$app->addRoute('/profile', UserController::class, 'profile', 'isAuthRequired');
$app->addRoute('/users', UserController::class, 'userList');
$app->addRoute('/delete_profile', UserController::class, 'deleteProfile', 'isAuthRequired');

function isAuthRequired() {
    return !!(App::getInstance()->getComponent('auth')->getCurrentUser());
}

$app->addRoute('/logout', AuthController::class, 'logout', 'isAuthRequired');

# Routing process
try {
    $page = $app->inspect();
    if ($page === false) {
        http_response_code(404);
        echo $app->getView()->render('404');
    } else {
        echo $app->getView()->render('default',compact('page'));;
    }
} catch (\Classes\AuthError $e) {
    http_response_code(403);
    echo $app->getView()->render('403');
}

//$content= $app->inspect();
//echo $app->getView()->render('default',compact('content'));
//print_r(UserModel::getModel()->getUser());