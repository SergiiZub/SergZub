<?php
use Classes\View;
use Classes\Route;
use Classes\Controller;
use Models\UserModel;
use Controllers\AuthController;
use \Controllers\UserController;


define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__FILE__) . DS);

require_once (ROOT.'lib'.DS.'App.php');
require_once (ROOT.'lib'.DS.'autoload.php');

# Registration components

Components\DbComponent::register('db');
Components\AuthComponent::register('auth');
\Components\NewsComponent::register('news');

# App init

$app = App::getInstance();
$app->init(ROOT.'lib'.DS.'config.php');

# Routing

$app->addRoute('/', \Controllers\NewsController::class);
$app->addRoute('/category', \Controllers\NewsController::class, 'getCategory');
$app->addRoute('/art', \Controllers\NewsController::class, 'getArticle');
$app->addRoute('/registration', AuthController::class, 'registration');
$app->addRoute('/login', AuthController::class, 'login');
$app->addRoute('/profile', UserController::class, 'profile', 'isAuthRequired');
$app->addRoute('/users', UserController::class, 'userList');
$app->addRoute('/delete_profile', UserController::class, 'deleteProfile', 'isAuthRequired');
$app->addRoute('/last_news', \Controllers\NewsController::class, 'getLastNews');

function isAuthRequired() {
    return !!(App::getInstance()->getComponent('auth')->getCurrentUser());
}

$app->addRoute('/logout', AuthController::class, 'logout', 'isAuthRequired');

# Routing process
try {
    $page = $app->inspect();
    if ($page === false) {
        http_response_code(404);
        echo $app->getView()->render('404');
    } else {
        echo $app->getView()->render('default',compact('page'));;
    }
} catch (\Classes\AuthError $e) {
    http_response_code(403);
    echo $app->getView()->render('403');
}

//$content= $app->inspect();
//echo $app->getView()->render('default',compact('content'));
//print_r(UserModel::getModel()->getUser());