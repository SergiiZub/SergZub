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
        $sql = "select title, img, id from article order by date desc limit 5";
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
        $sql = "select a.id, a.title, a.content, a.author, a.date, a.tags, a.img, c.category_name as category  from article a 
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

    public function search($db_component, $param){
        $connection = $db_component->connect();
        $sql = "SELECT * FROM article WHERE tags LIKE CONCAT('%',:param,'%') ";
        $stmt = $connection->prepare($sql);
        $stmt->execute([':param' => $param]);
        return ($stmt->fetchAll(\PDO::FETCH_OBJ));
    }

    public function saveCategory($db_component, $data, $id = null){
        $connection = $db_component->connect();

        if(!isset($data['alias']) || !isset($data['category_name'])) {
            return false;
        }

        $id = (int)$id;
        $alias = $data['alias'];
        $title = $data['category_name'];
        $is_published = isset($data['is_published']) ? 1 : 0;

        if (!$id){
            //add new record
            $sql = "INSERT INTO categories (`alias`, `category_name`, `is_published`) 
                    VALUES (':alias', ':category_name', ':is_published')";
        } else {
            //update existing record
            $sql = "UPDATE pages
                    SET `alias` = ':alias',
                    `title` = ':category_name',
                    `is_published` = ':is_published'
                    WHERE id = ':id'
              ";
        }

        $stmt = $connection->prepare($sql);
        $stmt->execute([':alias' => $param, ':category_name' => $title, ':is_published' => $is_published]);
        return ($stmt->fetchAll(\PDO::FETCH_OBJ));

        return $this->db->query($sql);
    }
}

