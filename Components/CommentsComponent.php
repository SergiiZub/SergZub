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

}