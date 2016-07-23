<?php

namespace Components;


use Core\Component;
final class DbComponent extends Component
{
    private $connection;
    private $db_config;

    public function __destruct() {
        $this->disconnect();
    }

    public function init() {
        $this->db_config = \App::getInstance()->getConfig('db');
    }

    public function connect() {
        $dsn = ('mysql:'
            . 'host=' . $this->db_config['host'] . ';'
            . 'port=' . $this->db_config['port'] . ';'
            . 'dbname=' . $this->db_config['db_name'] . ';'
        );
        $this->connection = new \PDO($dsn, $this->db_config['user'], $this->db_config['password']);
        return $this->connection;
    }
    public function disconnect() {
        $this->connection = null;
    }
}