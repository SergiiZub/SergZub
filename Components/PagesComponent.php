<?php


namespace Components;


use Classes\Component;

class PagesComponent extends Component
{
//    public $page;
//    public $text;
//    public $isActive;
//    public $buttons = array();

    function init() {
        // TODO: Implement init() method.
    }

    public function getPage($db_component){
        $category = (int) isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $page = (int) isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $items_per_page = (int) \App::getInstance()->getConfig('articles_per_page');
        $row_number = $page * $items_per_page - $items_per_page;

        $connection = $db_component->connect();
        $sql = "SELECT * FROM article WHERE category_id = {$category} LIMIT {$row_number}, {$items_per_page}";
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        return ($stmt->fetchAll(\PDO::FETCH_OBJ));
    }

    public function createButtons(int $items_count = 1) {
        $items_per_page = \App::getInstance()->getConfig('articles_per_page');
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
       // extract($options);

        /** @var int $currentPage */
        if(!$page){
            return;
        }

        /**
         * @var  int $pagesCount
         * @var  int $itemsCount
         * @var  int $itemsPerPage
         */
        $pages_count = ceil($items_count / $items_per_page);
        if($pages_count == 1){
            return;
        }
        /** @var int $currentPage */
        if ($page > $pages_count){
            $page = $pages_count;
        }
        $buttons[] = $this->buttonParams($page -1, $page > 1, 'Previous');

        for ($i = 1; $i <= $pages_count; $i++){
            $active = $page != $i;
            $buttons[] = $this->buttonParams($i, $active);
        }

        $buttons[] = $this->buttonParams($page +1, $page < $pages_count, 'Next');
        return $buttons;
    }

    public function buttonParams($page, $isActive = true, $text = null){
        $button_params['page'] = $page;
        $button_params['text'] = is_null($text) ? $page : $text;
        $button_params['is_active'] = $isActive;
        return (object)$button_params;
    }

    public function getButtons($db_component, $table_name) {
        $connection = $db_component->connect();
        $sql = "SELECT count(*) AS items_count FROM {$table_name} ";
        $stmt = $connection->prepare($sql);
        $stmt->execute([':item' => $table_name]);
        $count = ($stmt->fetchAll(\PDO::FETCH_OBJ));

        return $this->createButtons($count[0]->items_count);
    }

}