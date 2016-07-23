<?php
/**
 * Created by PhpStorm.
 * User: serg
 * Date: 18.07.16
 * Time: 13:12
 */

namespace Classes;


use Components\DbComponent;

abstract class Model {
    static private $instance;
    /**
     * @var DbComponent $db
     */
    protected $db;

    public function __construct() {
        $this->db = \App::getInstance()->getComponent('db');
    }

    /**
     * @return static
     */
    public static function getModel() {
        if (!self::$instance) {
            self::$instance = new static();
        }
        return self::$instance;
    }


}