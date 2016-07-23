<?php


namespace Components;


use Classes\Component;

class NewsComponent extends Component
{
    function init() {
        // TODO: Implement init() method.
    }

    public function getLastNews($db_component, $count = 5, $category_id = null){
        $count = (int) $count;
        $connection = $db_component->connect();
        $sql = 'SELECT * FROM article ';
        if ($category_id != null){
            $sql .= 'WHERE category_id = :category_id ';
            $sql .= "ORDER BY date LIMIT {$count}";
            $stmt = $connection->prepare($sql);
            $stmt->execute([':category_id' => $category_id]);
            return ($stmt->fetchAll(\PDO::FETCH_OBJ));
        }
        $sql .= "ORDER BY date DESC LIMIT {$count}";
       // echo $sql;
        $stmt = $connection->prepare($sql);
        //echo $stmt;
       // var_dump($stmt->execute([':count' => $count]));
      //  print_r($stmt->fetchAll(\PDO::FETCH_OBJ));
        //$stmt->execute([':count' => $count]);
        $stmt->execute();
        return ($stmt->fetchAll(\PDO::FETCH_OBJ));

    }

}