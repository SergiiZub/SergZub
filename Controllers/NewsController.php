<?php


namespace Controllers;


use Classes\Controller;
use Models\NewsModel;

class NewsController extends Controller
{
    public function index() {
        $category_list = NewsModel::getModel()->getCategoryList();
        return $this->app->getView()->render('news'.DS.'index', $category_list);
    }

    public function admin_index() {
        echo __METHOD__;
        // TODO: Implement admin_index() method.
    }

    public function getCategory(/*$category_id = null*/) {
        if (empty($_GET['id'])){
            return $this->index();
        }
        $category_id = $_GET['id'];
        $article_list = NewsModel::getModel()->getArticle($category_id);
        if (!$article_list){
            return $this->app->getView()->render('errors'.DS.'404');
        }
        return $this->app->getView()->render('news'.DS.'category', $article_list);
    }

    public function getArticle($article_id = null) {
//        echo 'article page';
        if (!empty($_GET['id'])){
            $r[] = 'I am article with id = ' . $_GET['id'] . '<br>';
            return $this->app->getView()->render('news'.DS.'category', $r);
        }
    }
}