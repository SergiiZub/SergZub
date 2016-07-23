<?php


namespace Controllers;

use Components\AuthComponent;
use Core\Controller;
use Models\UserModel;

final class AuthController extends Controller {
    public function registration() {

        if (isset($_POST['ensure_reg'])) {
print_r($_POST);
            /**
             * @var AuthComponent $auth_component
             */
            $auth_component = (\App::getInstance()->getComponent('auth'));
            $db = \App::getInstance()->getComponent('db');
            $status = $auth_component->registration(
                $db_component = $db, $name = $_POST['name'],
                $password = $_POST['password']);
            if ($status === false) {
                return $this->app->getView()->render('Errors'.DS.'500');
            }

            # Login after registration

            $auth_component->login(
                $db_component = $db, $name = $_POST['name'],
                $password = $_POST['password']);

            # Redirect to profile

            header('Cache-Control: no-cache');
            header('Location: /profile', false, 301);
            return true;
        }

        return $this->app->getView()->render('Auth'.DS.'registration');
    }

    public function index() {
        // TODO: Implement index() method.
    }

    public function login() {

        if (isset($_POST['ensure_login'])) {

            /**
             * @var AuthComponent $auth_component
             */
            $auth_component = (\App::getInstance()->getComponent('auth'));
            $db = \App::getInstance()->getComponent('db');
            $user = $auth_component->login(
                $db_component = $db, $name = $_POST['name'],
                $password = $_POST['password']);

            if (!$user) {
                return $this->app->getView()->render('Errors'.DS.'500');
            }

            header('Cache-Control: no-cache');
            header('Location: /profile', false, 301);
            return true;
        }

        return $this->app->getView()->render('Auth'.DS.'login');
    }

    public function logout() {
        \App::getInstance()->getComponent('Auth'.DS.'auth')->logout();
        header('Cache-Control: no-cache');
        header('Location: /', true, 301);
    }

}