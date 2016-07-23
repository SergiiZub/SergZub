<?php


namespace Controllers;


use Core\Controller;

class NewsController extends Controller
{
    public function index() {
        $r[] = 'List all categories with 5 news';
        return $this->app->getView()->render('News'.DS.'index', $r);
    }

    public function admin_index() {
        echo __METHOD__;
        // TODO: Implement admin_index() method.
    }

    public function getCategory($category_id = null) {
        $r[] = 'Category page, 5 news on page with paggination';
        return $this->app->getView()->render('News'.DS.'category', $r);
    }

    public function getArticle($article_id = null) {
//        echo 'article page';
        if (!empty($_GET['id'])){
            $r[] = 'I am article with id = ' . $_GET['id'] . '<br>';
            return $this->app->getView()->render('News'.DS.'category', $r);
        }
    }
}