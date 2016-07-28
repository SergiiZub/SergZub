<?php
/**
 * Created by PhpStorm.
 * User: serg
 * Date: 27.07.16
 * Time: 15:17
 */

namespace Components;


use Core\Component;

class CommentsComponent extends Component
{
    function init() {
        // TODO: Implement init() method.
    }

    public function getCommentsByPageId($db_component, $page_id){
        $connection = $db_component->connect();
        $sql = "SELECT * FROM comments WHERE article_id = {$page_id}";
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        return ($stmt->fetchAll(\PDO::FETCH_OBJ));
    }

    public function addComment($db_component, $article_id, $data){

        if (!isset($data['msg']) || !isset($data['msg'])){
            return null;
        }
       // var_dump($data);die();
        $connection = $db_component->connect();

        $article_id = (int)$article_id;
        $name = $data['name'];
        $email = $data['email'];
        $msg = $data['msg'];
        $user_id = $data['id'];
        $reply_to = (isset($data['reply_to']) ?? null);

        $sql = "INSERT INTO comments (reply_to, email, `name`, msg, article_id, user_id) 
                  VALUES (
                    '{$reply_to}', '{$email}', '$name', '{$msg}', '{$article_id}', '{$user_id}'
                    )";
        //var_dump($sql);die();
        $stmt = $connection->prepare($sql);
        $stmt->execute();
    }

    public function getTopCommentators($db_component) {
        $connection = $db_component->connect();
        $sql = "select u.id, u.name, count(c.user_id) as count
                from user u
                join comments c on c.user_id = u.id
                group by c.user_id
                order by count(c.user_id) desc
                limit 5";
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        return ($stmt->fetchAll(\PDO::FETCH_OBJ));
    }

    public function getCommentsListByUser($db_component, $user_id){
        $connection = $db_component->connect();
        $sql = "select * from comments where user_id = '{$user_id}'";
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        return ($stmt->fetchAll(\PDO::FETCH_OBJ));
    }

}