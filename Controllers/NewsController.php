<?php


namespace Controllers;


use Classes\Session;
use Core\Controller;
use Components\NewsComponent;
use Core\Router;
use Models\NewsModel;

class NewsController extends Controller
{
    private $db;

    public function __construct($data = array()) {

        parent::__construct($data);
        $this->model = new NewsModel();
        $this->db = \App::getInstance()->getComponent('db');

    }

    /**
     * index page
     */
    public function index() {
        $categories_list = $this->model->getCategoriesList($this->db);

        foreach ($categories_list as $category) {
            $category->{'article_list'} = $this->model->getArticlesListByCategory($this->db, $category->id);
        }

        $this->data['categories_list'] = $categories_list;
        $this->data['last_news'] = $this->model->getLastNews($this->db);

    }

    /**
     * category page
     */
    public function category() {
        $this->params[1] = isset($this->params[1]) ? $this->params[1] : 1;
        $component = (\App::getInstance()->getComponent('pagination'));
        $db = \App::getInstance()->getComponent('db');
        $category_id = $this->model->getIDByAlias($this->db, $this->params[0])[0]->id;

        $this->data['article_list_by_category'] = $component->getPage($db, 'article', 'category_id', $category_id, $this->params[1]);
    }

    /**
     * Article page
     */
    public function article() {
        if (isset($this->params[0])){
            $article = $this->model->getArticle($this->db, $this->params[0]);
            if (!$article){
                throw new \Exception('Page not found');
            }
        }

        if (!isset($article)){
            Router::redirect('/news/last_news/');
            return;
        }

        $comments_component = \App::getInstance()->getComponent('comments');
        $db = \App::getInstance()->getComponent('db');
//        echo '<pre>';
//        var_dump($article);die;
        $this->data['comments'] = $comments_component->getCommentsByPageId($db, $article[0]->id);

        $this->data['article'] = $article;
    }


    public function articles_list(){
        $this->data['articles_by_tag'] = $this->model->getArticlesListByTag($this->db, $this->params[0]);
    }

    public function last_news(){
        $this->data['last_news'] = $this->model->getLastNews($this->db);
    }
}