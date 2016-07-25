<?php


namespace Controllers;


use Classes\Controller;
use Components\NewsComponent;
use Models\NewsModel;

class NewsController extends Controller
{
//    private $param;
    public $data = [];


    /**
     * index page
     */
    public function index() {
        $param = \App::getInstance()->getParams();

        if (empty($param[2])) {
            $this->data['last_news'] = $this->lastNews();
            $this->data['categories_list'] = $this->categoriesList();
            return $this->app->getView()->render('news' . DS . 'index'.DS.'index', $this->data);
        }

        return $this->category();
    }

    /**
     * category page
     */
    public function category() {
        $param = \App::getInstance()->getParams();
        $category_list = NewsModel::getModel()->getCategoryList();

        foreach ($category_list as $category) {
            if (strtolower(urldecode(trim($category->category_name))) == strtolower(urldecode(trim($param[2])))) {
                $category_id = $category->id;
            }
        }

        if (empty($category_id)) {
            return $this->index();
        }

        if (!isset($_GET['page'])){
            $page_number = 1;
        } else {
            $page_number = $_GET['page'];
        }

 //       $this->data['articles_list_by_category'] = $this->articlesListByCategory($category_id);
        $this->data['page_by_category'] = $this->pageByCategory($category_id, $page_number);
        $this->data['pag_buttons'] = $this->paginationButtons($category_id, $page_number);
        return $this->app->getView()->render('news' . DS . 'category'.DS.'index', $this->data);

    }
//
//            if (!empty($category_id)){
//                $page_number = $param['3'] ? $param['3'] : 1;
//                //$data['articles_list_by_category'] = $this->articlesListByCategory($category_id);
//                $this->data['page_by_category'] = $this->pageByCategory($category_id, $page_number);
//                $this->data['pag_buttons'] = $this->paginationButtons($category_id, $page_number);
//            }
//
//        } else {
//            $this->data['categories_list'] = $this->categoriesList();
//        }



 //      $this->data['categories_list'] = $this->categoriesList();
 //+       $this->data['articles_list_by_category'] = $this->articlesListByCategory(1);//$category_id
  //+      $this->data['page_by_category'] = $this->pageByCategory(1, 1);//$category_id, $page_number
      //+  $this->data['pag_buttons'] = $this->paginationButtons(1,1);//$category_id, $page_number
//        $data['article'] = $this->article($article_id);
       // $data['comments'] = 'comments';
         //           echo '<pre>';
     //   print_r($data['page_by_category']);
//            var_dump($data['page_by_category']);
  //      return $this->app->getView()->render('news'.DS.'news_content', $this->data);
    //}

    /**
     * list all categories
     * @return string
     */
        public function categoriesList() {
            $news_component = (\App::getInstance()->getComponent('news'));
            $db = (\App::getInstance()->getComponent('db'));
            $categories_list = $news_component->getCategoriesList($db);

            foreach ($categories_list as $category){
                $category->{'articles'} = $this->articlesListByCategory($category->id);
            }

            return $this->app->getView()->render('news'.DS.'categories_list', $categories_list);
        }

        public function articlesListByCategory($category_id) {
            $news_component = (\App::getInstance()->getComponent('news'));
            $db = (\App::getInstance()->getComponent('db'));
            $articles_list_by_category = $news_component->getArticlesListByCategory($db, $category_id);
//            echo '<pre>';
//            var_dump($articles_list_by_category);
            return $this->app->getView()->render('news'.DS.'articles_list_by_category', $articles_list_by_category);
        }


        public function lastNews() {
            $news_component = (\App::getInstance()->getComponent('news'));
            $db = (\App::getInstance()->getComponent('db'));
            $last_news = $news_component->getLastNews($db);
//            echo '<pre>';
//            var_dump($articles_list_by_category);
            return $this->app->getView()->render('news'.DS.'last_news', $last_news);
        }

        public function pageByCategory($category_id, $page_number) {
            $news_component = (\App::getInstance()->getComponent('news'));
            $db = (\App::getInstance()->getComponent('db'));
            $page_by_category = $news_component->getPageByCategory($db, $category_id, $page_number);
//            $page_by_category['articles'] = $this->articlesListByCategory($category_id);
//            $page_by_category['pagination'] = $this->paginationButtons($category_id, $page_number);
//            echo '<pre>';
//            var_dump($page_by_category);
            return $this->app->getView()->render('news'.DS.'page_by_category', $page_by_category);
        }

        public function paginationButtons($category_id, $page_number) {
            $pages_component = (\App::getInstance()->getComponent('page'));
            $db = (\App::getInstance()->getComponent('db'));
            $buttons = $pages_component->getButtons($db, 'article', $category_id, $page_number);
      //      $buttons->{'link'} = $this->params;
      //      return $this->app->getView()->render('news'.DS.'page_by_category', $buttons);
            return $this->app->getView()->render('news'.DS.'buttons', $buttons);
        }

        public function article() {
            $param = \App::getInstance()->getParams();
            $article_id = $param[2];
            $news_component = (\App::getInstance()->getComponent('news'));
            $db = (\App::getInstance()->getComponent('db'));
            $article = $news_component->getArticle($db, $article_id);
            return $this->app->getView()->render('news'.DS.'article', $article);
        }
}


//    /**
//     * create index page
//     * @return string
//     */
//    public function index(){
////        echo __METHOD__;
//        $param = \App::getInstance()->getParams();
////        print_r($param);
//        if (isset($param[2])){
//            $category_list = NewsModel::getModel()->getCategoryList();
////            echo '<pre>';
////            print_r($category_list);
//            foreach ($category_list as $category){
//                if (strtolower(urldecode(trim($category->category_name))) == strtolower(urldecode(trim($param[2])))){
////                    echo $category->category_name.'<br>';
////                    break;
//                    return $this->categoryPage();
//                } else {
//                    echo 'вы написали билиберду' . $param[2].'<br>';
//                }
//
//            }
//        }
//
//        $this->data['last_news'] = $this->getLastNews(4);
//        $this->data['category_list'] = $this->categoryList();
//
//        return $this->app->getView()->render('news'.DS.'index', $this->data);
//    }
//
//    /**
//     * create category page
//     * @return string
//     */
//    public function categoryPage(){
//
//       // $this->data['article_list'] = $this->getCategory();
//        $this->data['article_list'] = $this->getPage();
//        $this->data['buttons'] = $this->getButtons();
//        return $this->app->getView()->render('news'.DS.'category_page', $this->data);
//    }
//
//    public function categoryList() {
//        $category_list = NewsModel::getModel()->getCategoryList();
//
//        /**
//         * create articles list for all category
//         */
//        foreach ($category_list as $category){
//            $category->{'articles'} = $this->getLastNews(5, $category->id);
//        }
//
//        return $this->app->getView()->render('news'.DS.'category_list', $category_list);
//    }
//
//    public function admin_index() {
//        echo __METHOD__;
//        // TODO: Implement admin_index() method.
//    }
//
//    public function getCategory() {
//        $param = \App::getInstance()->getParams();
//        if (isset($param[3])){
//            $category_id = $param[3];
//            $news_component = (\App::getInstance()->getComponent('news'));
//            $db = (\App::getInstance()->getComponent('db'));
//            $last_news = $news_component->getLastNews($db, $count, $category_id);
//           // $article_list = NewsModel::getModel()->getArticleList($category_id);
//        }
////        if (empty($_GET['id'])){
////            return $this->index();
////        }
//        $category_id = $_GET['id'];
//        $article_list = NewsModel::getModel()->getArticleList($category_id);
//        if (!$article_list){
//            return $this->app->getView()->render('errors'.DS.'404');
//        }
//
//        return $this->app->getView()->render('news'.DS.'category', $article_list);
//    }
//
//    public function getArticle($article_id = null) {
//
//        if (empty($_GET['id'])){
//            return $this->getCategory();
//        }
//        $article_id = $_GET['id'];
//        $article = NewsModel::getModel()->getArticle($article_id);
//        if (!$article){
//            return $this->app->getView()->render('errors'.DS.'404');
//        }
//        return $this->app->getView()->render('news'.DS.'article', $article);
//    }
//
//    public function getLastNews($count = 5, $category_id = null) {
//
//        $news_component = (\App::getInstance()->getComponent('news'));
//        $db = (\App::getInstance()->getComponent('db'));
//        $last_news = $news_component->getLastNews($db, $count, $category_id);
//        return $this->app->getView()->render('news'.DS.'last_news', $last_news);
//    }
//
////    /**
////     * @return int
////     */
////    public function itemsCount($table_name){
////        $pages_component = (\App::getInstance()->getComponent('page'));
////        $db = (\App::getInstance()->getComponent('db'));
////        $count = $pages_component->itemsCount($db, $table_name);
////        return $count[0]->items_count;
////    }
//
//    public function getButtons(){
//     //   $items_count = $this->itemsCount('article');
//        $pages_component = (\App::getInstance()->getComponent('page'));
//        $db = (\App::getInstance()->getComponent('db'));
//        $buttons = $pages_component->getButtons($db, 'article');
//        return $this->app->getView()->render('news'.DS.'buttons', $buttons);
//
//    }
//
//    public function getPage(){
//        $pages_component = (\App::getInstance()->getComponent('page'));
//        $db = (\App::getInstance()->getComponent('db'));
//        $page = $pages_component->getPage($db, 'article');
//        return $this->app->getView()->render('news'.DS.'category_articles', $page);
//    }
//}