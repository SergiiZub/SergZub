<?php


namespace Components;


use Classes\Component;

class NewsComponent extends Component
{
    function init() {
        // TODO: Implement init() method.
    }

    public function getCategoriesList($db_component) {
        $connection = $db_component->connect();
        $sql = 'SELECT * FROM news_category';
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        return ($stmt->fetchAll(\PDO::FETCH_OBJ));
    }

    public function getArticlesListByCategory($db_component, $category_id) {
        $connection = $db_component->connect();
        $sql = "select * from article where category_id = '{$category_id}' order by date desc limit 5";
  //      $sql = "SELECT * FROM article WHERE category_id = '1' ORDER BY DESC LIMIT 5";
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        return ($stmt->fetchAll(\PDO::FETCH_OBJ));
    }

    public function getLastNews($db_component) {
        $connection = $db_component->connect();
        $sql = "select * from article order by date desc limit 5";
  //      $sql = "SELECT * FROM article WHERE category_id = '1' ORDER BY DESC LIMIT 5";
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        return ($stmt->fetchAll(\PDO::FETCH_OBJ));
    }

    public function getPageByCategory($db_component, $category_id, $page_number) {

        $items_per_page = (int) \App::getInstance()->getConfig('articles_per_page');
        $row_number = $page_number * $items_per_page - $items_per_page;

        $connection = $db_component->connect();
 //       $sql = 'SELECT * FROM article WHERE category_id = :category_id LIMIT :row_number , :items_per_page';
        $sql = "SELECT * FROM article WHERE category_id = {$category_id} LIMIT {$row_number}, {$items_per_page}";
        $stmt = $connection->prepare($sql);
//        $stmt->execute([':category_id' => $category_id, ':row_number' => $row_number, ':items_per_page' => $items_per_page]);
        $stmt->execute();
        return ($stmt->fetchAll(\PDO::FETCH_OBJ));
    }

    public function getArticle($db_component, $article_id) {
        $connection = $db_component->connect();
        $sql = 'SELECT * FROM article WHERE id = :article_id LIMIT 1';
        $stmt = $connection->prepare($sql);
        $stmt->execute([':article_id' => $article_id]);
        return ($stmt->fetchAll(\PDO::FETCH_OBJ));
    }


//    public function getLastNews($db_component, $count = 5, $category_id = null){
//        $count = (int) $count;
//        $connection = $db_component->connect();
//        $sql = 'SELECT * FROM article ';
//        if ($category_id != null){
//            $sql .= 'WHERE category_id = :category_id ';
//            $sql .= "ORDER BY date LIMIT {$count}";
//            $stmt = $connection->prepare($sql);
//            $stmt->execute([':category_id' => $category_id]);
//            return ($stmt->fetchAll(\PDO::FETCH_OBJ));
//        }
//        $sql .= "ORDER BY date DESC LIMIT {$count}";
//       // echo $sql;
//        $stmt = $connection->prepare($sql);
//        //echo $stmt;
//       // var_dump($stmt->execute([':count' => $count]));
//      //  print_r($stmt->fetchAll(\PDO::FETCH_OBJ));
//        //$stmt->execute([':count' => $count]);
//        $stmt->execute();
//        return ($stmt->fetchAll(\PDO::FETCH_OBJ));
//
//    }

}