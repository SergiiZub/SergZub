<?php


namespace Models;


use Classes\Model;

class NewsModel extends Model {

    public function getCategoryList() {
        $connect = $this->db->connect();
        $stmt = $connect->prepare(
            'SELECT * FROM news_category'
        );
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    public function getArticle($category_id) {
        $connect = $this->db->connect();
        $stmt = $connect->prepare(
            'SELECT * FROM article WHERE category_id = :category_id'
        );
        $stmt->execute([':category_id' => $category_id]);

        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    public function deleteArticle($article_id) {
        $connect = $this->db->connect();
        $stmt = $connect->prepare(
            'DELETE FROM article WHERE id = :article_id'
        );
        return $stmt->execute([':user_id' => $article_id]);
    }
}