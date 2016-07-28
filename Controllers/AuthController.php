<?php


namespace Controllers;

use Classes\Session;
use Components\AuthComponent;
use Core\Controller;
use Core\Router;
use Models\UserModel;

final class AuthController extends Controller {

    function index() {
        $user = \App::getInstance()->getComponent('auth')->getCurrentUser();
        if ($user) {
            Router::redirect('users/profile');
        } else {
            Router::redirect('auth/login');
        }
    }


    public function registration() {

        if (isset($_POST['ensure_reg'])) {

            /**
             * @var AuthComponent $auth_component
             */
            $auth_component = (\App::getInstance()->getComponent('auth'));
            $db = \App::getInstance()->getComponent('db');
            $status = $auth_component->registration(
                $db_component = $db, $name = $_POST['name'],
                $login = $_POST['login'], $email = $_POST['email'],
                $password = $_POST['password']);

            if ($status === false) {
                Session::setFlash('Registration filed!');
                Router::redirect('/auth/registration/');
            }

            # Login after registration

            $auth_component->login(
                $db_component = $db, $login = $_POST['login'],
                $password = $_POST['password']);

            if(!$auth_component){
                Session::setFlash('Login filed!');
                return false;
            }
            # Redirect to profile
           return Router::redirect('/users/profile/');
        }

    }

    public function login() {

        $user = \App::getInstance()->getComponent('auth')->getCurrentUser();
        if ($user){
           return Router::redirect('/users/profile/');

        }

        if (isset($_POST['ensure_login'])) {

            /**
             * @var AuthComponent $auth_component
             */
            $auth_component = (\App::getInstance()->getComponent('auth'));
            $db = \App::getInstance()->getComponent('db');

            $user = $auth_component->login(
                $db_component = $db, $login = $_POST['login'],
                $password = $_POST['password']);

            if (!$user) {
                Session::setFlash('Auth filed!');

               // return Router::redirect('/auth/login/');
            }
                Session::setFlash('You logged in as ');
                Router::redirect('/users/profile/');
            //return true;
        }
    }

    public function logout() {
        \App::getInstance()->getComponent('auth')->logout();
        Session::setFlash('You logged out! ');
        Router::redirect('/auth/login/');
    }

}
