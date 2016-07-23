<?php


namespace Core;


final class Route {
    private $uri;
    private $controller;
    private $action_name;
    private $action_access;

    /**
     * Router constructor. Создаем экземпляр зарегесртированного роута
     * @param $uri
     * @param $controller
     * @param $action
     * @param $action_access
     */
    public function __construct($uri, $controller, $action, $action_access) {
        $this->uri = $uri;
        $this->controller = $controller;
        $this->action_name = $action;
        $this->action_access = $action_access;
    }

    /**
     * проверяем соответствие пользовательского uri и uri екземпляра роута
     * @param $current_uri
     * @return bool
     */
    public function inspect($current_uri) {
        return strpos($this->uri, $current_uri) !== false;
    }

    public function callAction(){
        # проверяем существование метода в контроллере
        if (method_exists($this->controller, $this->action_name)) {
            # проверяем доступен ли метод текущему юзеру
            if ($this->action_access) {
                $r = call_user_func_array($this->action_access, []);
                if (!$r) {
                    throw new \Exception('Auth error, please log in');
                }
            }
            return $this->controller->{$this->action_name}();
        }
        return false;
    }
}