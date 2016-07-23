<?php


namespace Controllers;


use Classes\Controller;
use Models\NewsModel;

class NewsController extends Controller
{
    public function index() {
        $category_list = NewsModel::getModel()->getCategoryList();

        /**
         * create articles list for all category
         */
        foreach ($category_list as $category){
            $category->{'articles'} = $this->getLastNews(5, $category->id);
        }

//        $data = $this->getLastNews(4);

        return $this->app->getView()->render('news'.DS.'index', $category_list);
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
//        echo 'article page';
//        if (!empty($_GET['id'])){
//            $r[] = 'I am article with id = ' . $_GET['id'] . '<br>';
//            return $this->app->getView()->render('news'.DS.'category', $r);
//        }
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
        //var_dump($last_news);
        return $this->app->getView()->render('news'.DS.'last_news', $last_news);

//        $news_list = \App::getInstance()->getComponent('news')->getLastNews();
//        $user = \App::getInstance()->getComponent('auth')->getCurrentUser();
//        return $this->app->getView()->render('user'.DS.'user_profile', ['user' => $user]);
//
//        $auth_component = (\App::getInstance()->getComponent('auth'));
//        $db = \App::getInstance()->getComponent('db');
//        $status = $auth_component->registration(
//            $db_component = $db, $name = $_POST['name'],
//            $password = $_POST['password']);
    }
}