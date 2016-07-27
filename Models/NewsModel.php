<?php


namespace Models;


class NewsModel
{
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
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        return ($stmt->fetchAll(\PDO::FETCH_OBJ));
    }

    public function getLastNews($db_component) {
        $connection = $db_component->connect();
        $sql = "select * from article order by date desc limit 5";
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        return ($stmt->fetchAll(\PDO::FETCH_OBJ));
    }

    public function getArticlesListByAlias($db_component, $alias) {
        $connection = $db_component->connect();
        $sql = "SELECT  a.id, a.title, a.img, c.category_name
            FROM article a
            JOIN  news_category c ON a.category_id = c.id
            WHERE category_name = '{$alias}'
            ORDER BY `date` DESC
            LIMIT 5";
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        return ($stmt->fetchAll(\PDO::FETCH_OBJ));
    }


    public function getArticle($db_component, $id) {
        $connection = $db_component->connect();
        //$sql = "select * from article WHERE id = 3";
        $sql = "select a.id, a.title, a.content, a.author, a.date, a.tags, c.category_name as category  from article a 
          JOIN news_category c ON a.category_id = c.id
          WHERE a.id = '{$id}'";
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        return ($stmt->fetchAll(\PDO::FETCH_OBJ));
    }

    public function getPageByCategory($db_component, $alias, $page_number = null) {

        $items_per_page = (int)\App::getInstance()->getConfig('articles_per_page');
        $row_number = $page_number * $items_per_page - $items_per_page;

        $connection = $db_component->connect();
        $sql = "SELECT  a.id, a.title, a.img, c.category_name
            FROM article a
            JOIN  news_category c ON a.category_id = c.id
            WHERE category_name = '{$alias}'
            ORDER BY `date` DESC 
            LIMIT {$row_number}, {$items_per_page}";

        $stmt = $connection->prepare($sql);
        $stmt->execute();
        return ($stmt->fetchAll(\PDO::FETCH_OBJ));
    }

    public function getIDByAlias($db_component, $alias){
        $connection = $db_component->connect();
        $sql = "select id from news_category WHERE category_name = '{$alias}'";
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        return ($stmt->fetchAll(\PDO::FETCH_OBJ));
    }

    public function getArticlesListByTag($db_component, $tag) {
        $connection = $db_component->connect();
        $sql = "SELECT * FROM article WHERE tags LIKE '%%{$tag}%%' ";
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        return ($stmt->fetchAll(\PDO::FETCH_OBJ));
    }
}

