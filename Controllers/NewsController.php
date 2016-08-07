<?php


namespace Controllers;


use Classes\Session;
use Core\Controller;
use Components\NewsComponent;
use Core\Router;
use Core\View;
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
        $this->data['last_news'] = json_encode($this->model->getLastNews($this->db));

        # Top commentators
        $comments_component = \App::getInstance()->getComponent('comments');
        $db = \App::getInstance()->getComponent('db');
        $this->data['top_commentators'] = $comments_component->getTopCommentators($db);
      //  $this->data['top_menu'] = $this->topMenu();
    }

    /**
     * category page
     */
    public function category() {
        if (!$this->params){
            $this->data['categories_list'] = $this->model->getCategoriesList($this->db);

        } else {
           // print_r($this->params);
            $this->params[1] = isset($this->params[1]) ? $this->params[1] : 1;
            $component = (\App::getInstance()->getComponent('pagination'));
            $db = \App::getInstance()->getComponent('db');
            $category_id = $this->model->getIDByAlias($this->db, $this->params[0])[0]->id;

            $this->data['article_list_by_category'] = $component->getPage($db, 'article', 'category_id', $category_id, $this->params[1]);
        }

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
        $this->data['comments'] = $comments_component->getCommentsByPageId($db, $article[0]->id);

        $this->data['article'] = $article;
        $this->data['user'] = \App::getInstance()->getComponent('auth')->getCurrentUser();

    }


    public function articles_list(){
        $this->data['articles_by_tag'] = $this->model->getArticlesListByTag($this->db, $this->params[0]);
    }

    public function last_news(){
        $this->data['last_news'] = $this->model->getLastNews($this->db);
    }

    public function search(){
        if (!isset($_POST['search'])){
            Router::redirect('/');
            return false;
        }

        $param = $_POST['param'];
        $result = $this->model->search($this->db, $param);
        if (!$result){
            Session::setFlash('Find nothing');
            Router::redirect('/');
            return false;
        }
        $this->data['articles_list'] = $result;
    }

    public function topMenu(){
        $top_menu = $this->model->getCategoriesList($this->db);
        return  new View($top_menu, ROOT.'Views'.DS.'news'.DS.'top_menu.html');
    }

    public function admin_index(){
        $categories_list = $this->model->getCategoriesList($this->db);

        foreach ($categories_list as $category) {
            $category->{'article_list'} = $this->model->getArticlesListByCategory($this->db, $category->id);
        }

        $this->data['categories_list'] = $categories_list;
        $this->data['last_news'] = json_encode($this->model->getLastNews($this->db));

        # Top commentators
        $comments_component = \App::getInstance()->getComponent('comments');
        $db = \App::getInstance()->getComponent('db');
        $this->data['top_commentators'] = $comments_component->getTopCommentators($db);
    }

    /**
     * category page
     */
    public function admin_category() {
        if (!$this->params){
            $this->data['categories_list'] = $this->model->getCategoriesList($this->db);

        } else {
            // print_r($this->params);
            $this->params[1] = isset($this->params[1]) ? $this->params[1] : 1;
            $component = (\App::getInstance()->getComponent('pagination'));
            $db = \App::getInstance()->getComponent('db');
            $category_id = $this->model->getIDByAlias($this->db, $this->params[0])[0]->id;

            $this->data['article_list_by_category'] = $component->getPage($db, 'article', 'category_id', $category_id, $this->params[1]);
        }
    }

    public function admin_article() {
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
        $this->data['comments'] = $comments_component->getCommentsByPageId($db, $article[0]->id);

        $this->data['article'] = $article;
        $this->data['user'] = \App::getInstance()->getComponent('auth')->getCurrentUser();

    }

    public function admin_last_news(){
        $this->data['last_news'] = $this->model->getLastNews($this->db);
    }

    public function admin_addCategory(){
        $this->data['categories_list'] = $this->model->getCategoriesList($this->db);
    }

    public function admin_add(){
        if ($_POST){
            $result = $this->model->saveCategory($this->db, $_POST);
            if ($result){
                Session::setFlash('Category was saved.');
            } else {
                Session::setFlash('Error.');
            }
            Router::redirect('/admin/category/');
        }
    }
}