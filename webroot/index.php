<?php


//$uri = $_SERVER['REQUEST_URI'];
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(dirname(__FILE__)) . DS);

require_once (ROOT . 'app' . DS . 'lib' . DS . 'App.php');
require_once (ROOT . 'app' . DS . 'lib' . DS . 'autoload.php');
try{
    $app = App::getInstance();
    $app->init(ROOT.'app'.DS.'lib'.DS.'config.php');


    \Components\DbComponent::register('db');
    \Components\AuthComponent::register('auth');

    function isAuthRequired(){
        return !!(App::getInstance()->getComponent('auth')->getCurrentUser());
    }

    $app->addRoute('/', \Controllers\NewsController::class);
    $app->addRoute('/category', \Controllers\NewsController::class, 'getCategory');
    $app->addRoute('/art', \Controllers\NewsController::class, 'getArticle');
    $app->addRoute('/registration', \Controllers\AuthController::class, 'registration');
    $app->addRoute('/login', \Controllers\AuthController::class, 'login');
    $app->addRoute('/profile', \Controllers\UserController::class, 'profile', 'isAuthRequired');

//echo $app->inspect();
// $content = $app->getView()->render('news'); //????
    $content= $app->inspect();
    echo $app->getView()->render('default',compact('content')); // ???
} catch (Exception $e) {
    echo $e->getMessage();
}
