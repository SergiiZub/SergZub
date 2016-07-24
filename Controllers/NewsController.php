<?php


namespace Controllers;


use Classes\Controller;
use Models\NewsModel;

class NewsController extends Controller
{
    public $data = [];

    /**
     * create index page
     * @return string
     */
    public function index(){
        $this->data['last_news'] = $this->getLastNews(4);
        $this->data['category_list'] = $this->categoryList();

        return $this->app->getView()->render('news'.DS.'index', $this->data);
    }

    /**
     * create category page
     * @return string
     */
    public function categoryPage(){
       // $this->data['article_list'] = $this->getCategory();
        $this->data['article_list'] = $this->getPage();
        $this->data['buttons'] = $this->getButtons();
        return $this->app->getView()->render('news'.DS.'category_page', $this->data);
    }

    public function categoryList() {
        $category_list = NewsModel::getModel()->getCategoryList();

        /**
         * create articles list for all category
         */
        foreach ($category_list as $category){
            $category->{'articles'} = $this->getLastNews(5, $category->id);
        }

        return $this->app->getView()->render('news'.DS.'category_list', $category_list);
    }

    public function admin_index() {
        echo __METHOD__;
        // TODO: Implement admin_index() method.
    }

    public function getCategory() {
        if (empty($_GET['id'])){
            return $this->index();
        }
        $category_id = $_GET['id'];
        $article_list = NewsModel::getModel()->getArticleList($category_id);
        if (!$article_list){
            return $this->app->getView()->render('errors'.DS.'404');
        }

        return $this->app->getView()->render('news'.DS.'category', $article_list);
    }

    public function getArticle($article_id = null) {

        if (empty($_GET['id'])){
            return $this->getCategory();
        }
        $article_id = $_GET['id'];
        $article = NewsModel::getModel()->getArticle($article_id);
        if (!$article){
            return $this->app->getView()->render('errors'.DS.'404');
        }
        return $this->app->getView()->render('news'.DS.'article', $article);
    }

    public function getLastNews($count = 5, $category_id = null) {

        $news_component = (\App::getInstance()->getComponent('news'));
        $db = (\App::getInstance()->getComponent('db'));
        $last_news = $news_component->getLastNews($db, $count, $category_id);
        return $this->app->getView()->render('news'.DS.'last_news', $last_news);
    }

//    /**
//     * @return int
//     */
//    public function itemsCount($table_name){
//        $pages_component = (\App::getInstance()->getComponent('page'));
//        $db = (\App::getInstance()->getComponent('db'));
//        $count = $pages_component->itemsCount($db, $table_name);
//        return $count[0]->items_count;
//    }

    public function getButtons(){
     //   $items_count = $this->itemsCount('article');
        $pages_component = (\App::getInstance()->getComponent('page'));
        $db = (\App::getInstance()->getComponent('db'));
        $buttons = $pages_component->getButtons($db, 'article');
        return $this->app->getView()->render('news'.DS.'buttons', $buttons);

    }

    public function getPage(){
        $pages_component = (\App::getInstance()->getComponent('page'));
        $db = (\App::getInstance()->getComponent('db'));
        $page = $pages_component->getPage($db, 'article');
        return $this->app->getView()->render('news'.DS.'category_articles', $page);
    }
}