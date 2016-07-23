<?php

namespace Classes;

class AuthError extends \Exception {

}

final class Route
{
    private $uri;
    private $controller;
    private $action_name;
    private $action_access;

    /**
     * Route constructor.
     * @param $uri
     * @param $controller
     * @param $action_name
     */
    public function __construct($uri, $controller, $action_name, $action_access) {
        $this->uri = $uri;
        $this->controller = $controller;
        $this->action_name = $action_name;
        $this->action_access = $action_access;
    }

    public function inspect($current_uri) {
        return strpos($this->uri, $current_uri) !== false;
    }

    public function callAction() {
        if (method_exists($this->controller, $this->action_name)) {
            if ($this->action_access) {
                $r = call_user_func_array($this->action_access, []);
                if (!$r) {
                    throw new AuthError();
                }
            }
            return $this->controller->{$this->action_name}();
        }
        return false;
    }
}