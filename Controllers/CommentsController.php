<?php
/**
 * Created by PhpStorm.
 * User: serg
 * Date: 27.07.16
 * Time: 18:02
 */

namespace Controllers;


use Core\Controller;
use Core\Router;

class CommentsController extends Controller
{
    public function addComment(){
        $user = \App::getInstance()->getComponent('auth')->getCurrentUser();
        if (!$user){
            return Router::redirect('/auth/login/');
        }

        if (isset($this->params[0])){
            $article_id = $this->params[0];
            $data = $_POST;
            $comments_component = \App::getInstance()->getComponent('comments');
            $db = \App::getInstance()->getComponent('db');
            $this->data['status'] = $comments_component->addComment($db, $article_id, $data);
            //Router::redirect('/news/article/'.$article_id.'/');
        } else {
           // \Router::redirect('');
        }
    }

    public function topCommentators(){
        $comments_component = \App::getInstance()->getComponent('comments');
        $db = \App::getInstance()->getComponent('db');
        $this->data['top_commentators'] = $comments_component->getTopCommentators($db);
    }

    public function commentsList() {
        if (!$this->params[0]){
            Router::redirect('/');
        }
//        $comments_component = \App::getInstance()->getComponent('comments');
//        $db = \App::getInstance()->getComponent('db');
//        $this->data['comments_by_user'] = $comments_component->getCommentsListByUser($db, $this->params[0]);


        $this->params[1] = isset($this->params[1]) ? $this->params[1] : 1;
        $component = (\App::getInstance()->getComponent('pagination'));
        $db = \App::getInstance()->getComponent('db');
        $user_id = $this->params[0];

        $this->data['comments_by_user'] = $component->getPage($db, 'comments', 'user_id', $user_id, $this->params[1]);
    }
}