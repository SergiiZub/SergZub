<?php

namespace Components;


use Classes\Session;
use Core\Component;
use Core\Router;

final class AuthComponent extends Component
{
    private $current_user;

    public function init() {
        // TODO: Implement init() method.
    }

    /**
     * @param DbComponent $db_component
     * @param $name
     * @param $password
     * @return bool
     */
    public function registration($db_component, $name, $login, $email, $password) {
        $connection = $db_component->connect();
        $stmt = $connection->prepare('INSERT INTO `user` (name, login, email, password) VALUES (:name, :login, :email, :password)');
        $hash_password = $this->createHashPassword($this->config['secret_key'], $password);
        return $stmt->execute([':name' => $name, ':login' => $login, ':email' => $email, ':password' => $hash_password]);
    }

    /**
     * @param DbComponent $db_component
     * @param $name
     * @param $password
     * @return bool
     */
    public function login($db_component, $login, $password) {
        $connection = $db_component->connect();
        $stmt = $connection->prepare('SELECT * FROM `user` WHERE login = :login AND password = :password LIMIT 1');
        $hash_password = $this->createHashPassword($this->config['secret_key'], $password);
        $stmt->execute([':login' => $login, ':password' => $hash_password]);
        $user = $stmt->fetch(\PDO::FETCH_OBJ);


        if ($user) {
            Session::set('USER_SESSION', $this->encryptSessionToken($this->config['secret_key'], $user->id));
            Session::set('USER_NAME', $user->name);
            $this->current_user = $user;
        }
        return $user;
    }

    public function logout() {
        $this->current_user = null;
        Session::delete('USER_SESSION');
        Session::delete('USER_NAME');
    }

    /**
     * @param DbComponent $db_component
     * @return bool
     */
    public function middleware($db_component) {
        if (empty($_SESSION['USER_SESSION'])) {
            return false;
        }

        $user_id = $this->decryptSessionToken($this->config['secret_key'], $_SESSION['USER_SESSION']);

        if (!$user_id) {
            $this->logout();
            return false;
        }

        $connection = $db_component->connect();
        $stmt = $connection->prepare('SELECT * FROM `user` WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $user_id]);
        $this->current_user = ($stmt->fetch(\PDO::FETCH_OBJ));

    }

    private function createHashPassword($secret_key, $password) {
        return md5($secret_key . $password);
    }

    private function encryptSessionToken($secret_key, $user_id) {
        return $user_id . ':' . md5($secret_key . $user_id);
    }

    private function decryptSessionToken($secret_key, $session_id) {
        list($user_id, $sign) = explode(':' , $session_id);

//        if(!function_exists('hash_equals'))
//        {
//            function hash_equals($str1, $str2)
//            {
//                if(strlen($str1) != strlen($str2))
//                {
//                    return false;
//                }
//                else
//                {
//                    $res = $str1 ^ $str2;
//                    $ret = 0;
//                    for($i = strlen($res) - 1; $i >= 0; $i--)
//                    {
//                        $ret |= ord($res[$i]);
//                    }
//                    return !$ret;
//                }
//            }
//        }
        if (!hash_equals($sign, md5($secret_key . $user_id))) {

            Router::redirect('/auth/');
            return false;
        }
        return $user_id;
    }

    /**
     * @return mixed
     */
    public function getCurrentUser() {
        return $this->current_user;
    }


}