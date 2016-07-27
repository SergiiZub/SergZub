<?php


namespace Models;


use Core\Model;

class UserModel extends Model {
    public function getUser() {
        $connect = $this->db->connect();
        $stmt = $connect->prepare(
            'SELECT * FROM user'
        );
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    public function delete($user_id) {
        $connect = $this->db->connect();
        $stmt = $connect->prepare(
            'DELETE FROM user WHERE id = :user_id'
        );
        return $stmt->execute([':user_id' => $user_id]);
    }
}