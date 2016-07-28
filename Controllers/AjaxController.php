<?php


namespace Controllers;


use Core\Controller;

class AjaxController extends Controller
{

    public function index() {
        header('Access-Control-Allow-Origin: *');

        if (!isset($_GET['value']) or !$_GET['value']) {
            return json_encode([]);
        }

        $db = \App::getInstance()->getComponent('db');
        $connection = $db->connect();
        $stmt = $connection->prepare(
            'SELECT * FROM article'
            .' WHERE contant LIKE CONCAT(\'%\',:name,\'%\') LIMIT 10'
        );
        $stmt->execute([':name' => $_GET['value']]);
        $users = $stmt->fetchAll(\PDO::FETCH_OBJ);
        if ($users === false) {
            return json_encode([]);
        }

        return json_encode(
            array_map(function($user) {
                return ['value' => $user->name];
            }, $users)
        );
    }


}