<?php

namespace Components;


use Classes\Component;

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
    public function registration($db_component, $name, $password) {
        $connection = $db_component->connect();
        $stmt = $connection->prepare('INSERT INTO `user` (name, password) VALUES (:name, :password)');
        $hash_password = $this->createHashPassword($this->config['secret_key'], $password);
        return $stmt->execute([':name' => $name, ':password' => $hash_password]);
    }

    /**
     * @param DbComponent $db_component
     * @param $name
     * @param $password
     * @return bool
     */
    public function login($db_component, $name, $password) {
        $connection = $db_component->connect();
        $stmt = $connection->prepare('SELECT * FROM `user` WHERE name = :name AND password = :password LIMIT 1');
        $hash_password = $this->createHashPassword($this->config['secret_key'], $password);
        $stmt->execute([':name' => $name, ':password' => $hash_password]);
        $user = $stmt->fetch(\PDO::FETCH_OBJ);

        if ($user) {
            setcookie('USER_SESSION', $this->encryptSessionToken($this->config['secret_key'], $user->id));
        }
        return $user;
    }

    public function logout() {
        setcookie('USER_SESSION', null, -1);
    }

    /**
     * @param DbComponent $db_component
     */
    public function middleware($db_component) {
        if (!isset($_COOKIE['USER_SESSION'])) {
            return false;
        }
        $user_id = $this->decryptSessionToken($this->config['secret_key'], $_COOKIE['USER_SESSION']);

        if (!$user_id) {
            $this->current_user = null;
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
        if (!hash_equals($sign, md5($secret_key . $user_id))) {
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