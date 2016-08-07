<?php
use Core\View;
use Core\Router;
use Classes\DB;
use Classes\Config;
use Classes\Lang;
use Classes\Session;
use Core\Controller;
use Controllers\PagesController;
use Controllers\AuthController;

final class App
{
    static private $instance;
    private $components;
    protected static $router;
    public static $db;
 //   private $view;
    private $config;

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

    public function addComponent($name, $component_cls) {
        $this->components[$name] = new $component_cls;
    }

    public function getComponent($name) {
        return $this->components[$name];
    }

    public function getConfig($key = null) {
        return ($key ? Config::get($key) : Config::get());
    }

    public static function getRouter()
    {
        return self::$router;
    }

    public function init() {
        $this->config = Config::get();

        foreach ($this->components as $component) {
            $component->init();
        }
    }

    public function run($uri) {
        $this->getComponent('auth')->middleware($this->getComponent('db'));
        self::$router = new Router($uri);
        self::$db = new DB(Config::get('db.host'), Config::get('db.user'), Config::get('db.password'), Config::get('db.db_name'));

        Lang::load(self::$router->getLanguage());

        $controller_class = 'Controllers\\'.ucfirst(self::$router->getController()) . 'Controller';
        $controller_method = strtolower(self::$router->getMethodPrefix() . self::$router->getAction());

        $layout = self::$router->getRoute();
        if ($layout == 'admin' && Session::get('role') != 'admin'){
            if($controller_method != 'admin_login'){
                Router::redirect('/admin/users/login');
            }
        }

        //Calling controller's method

        $controller_path = str_replace('\\', DS, ROOT.$controller_class) . '.php';
        if (!file_exists($controller_path)){
            Router::redirect('');
        }

        $controller_object = new $controller_class();

        if (method_exists($controller_object, $controller_method)){
            //Controller's action may return a view path
            $view_path = $controller_object->$controller_method();
            $view_object = new View($controller_object->getData(), $view_path);
            $content = $view_object->render();


        } else {
            throw new Exception('Method ' . $controller_method . ' of class ' . $controller_class . ' does not exist.');
        }

        $layout_path = VIEWS_PATH . DS . $layout . '.html';
        $layout_view_object = new View(compact('content'), $layout_path);
        echo $layout_view_object->render();
    }
}