<?php


namespace Components;





use Core\Component;

class PaginationComponent extends Component
{

    function init() {
        // TODO: Implement init() method.
    }

    public function getPage($db_component, $table_name, $column, $column_param, $page_number = null){
        $page_number = (int) !is_null($page_number) ? (int) $page_number : 1;
        $items_per_page = (int) \App::getInstance()->getConfig('articles_per_page');
        $row_number = $page_number * $items_per_page - $items_per_page;

        $connection = $db_component->connect();
        $sql = "SELECT * FROM {$table_name} WHERE {$column} = {$column_param} ORDER BY `date` DESC LIMIT {$row_number}, {$items_per_page}";
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        $result['list'] = ($stmt->fetchAll(\PDO::FETCH_OBJ));
        $result['buttons'] = $this->getButtons($db_component, $table_name, $column, $column_param, $page_number);
        return $result;
    }

    public function getButtons($db_component, $table_name, $column, $column_param, $page_number) {
        $connection = $db_component->connect();
        $sql = "SELECT count(*) AS items_count FROM {$table_name} WHERE {$column} = {$column_param}";
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        $count = ($stmt->fetchAll(\PDO::FETCH_OBJ));

        return $this->createButtons($count[0]->items_count, $page_number);
    }

    public function createButtons(int $items_count = 1, $page_number = 1) {
        $items_per_page = \App::getInstance()->getConfig('articles_per_page');

        /** @var int $currentPage */
        if(!$page_number){
            return false;
        }

        /**
         * @var  int $pagesCount
         * @var  int $itemsCount
         * @var  int $itemsPerPage
         */
        $pages_count = ceil($items_count / $items_per_page);
        if($pages_count == 1){
            return false;
        }
        /** @var int $currentPage */
        if ($page_number > $pages_count){
            $page_number = $pages_count;
        }
        $buttons[] = $this->buttonParams($page_number -1, $page_number > 1, 'Previous');

        for ($i = 1; $i <= $pages_count; $i++){
            $active = $page_number != $i;
            $buttons[] = $this->buttonParams($i, $active);
        }

        $buttons[] = $this->buttonParams($page_number +1, $page_number < $pages_count, 'Next');
        return $buttons;
    }

    public function buttonParams($page, $isActive = true, $text = null){
        $button_params['page'] = $page;
        $button_params['text'] = is_null($text) ? $page : $text;
        $button_params['is_active'] = $isActive;
        return (object)$button_params;
    }



}