<?php


namespace Classes;



class Pagination
{
    public $buttons = array();
    /**
     * Pagination constructor.
     * @param array $options
     */
    public function __construct(array $options = array('itemsCount' => 257, 'itemsPerPage' => 10, 'currentPage' => 1))
    {
        extract($options);

        /** @var int $currentPage */
        if(!$currentPage){
            return;
        }

        /**
         * @var  int $pagesCount
         * @var  int $itemsCount
         * @var  int $itemsPerPage
         */
        $pagesCount = ceil($itemsCount / $itemsPerPage);
        if($pagesCount == 1){
            return;
        }
        /** @var int $currentPage */
        if ($currentPage > $pagesCount){
            $currentPage = $pagesCount;
        }
        $this->buttons[] = new Button($currentPage -1, $currentPage > 1, 'Previous');

        for ($i = 1; $i <= $pagesCount; $i++){
            $active = $currentPage != $i;
            $this->buttons[] = new Button($i, $active);
        }

        $this->buttons[] = new Button($currentPage +1, $currentPage < $pagesCount, 'Next');

    }
}

class Button
{
    public $page;
    public $text;
    public $isActive;
    /**
     * Button constructor.
     * @param $page
     * @param $text
     * @param $isActive
     */
    public function __construct($page, $isActive = true, $text = null)
    {
        $this->page = $page;
        $this->text = is_null($text) ? $page : $text;
        $this->isActive = $isActive;
    }
}