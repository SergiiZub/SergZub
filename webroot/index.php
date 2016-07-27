<?php
use Components\DbComponent;
use Components\AuthComponent;
use Components\PaginationComponent;
use Classes\Session;


define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(dirname(__FILE__)).DS);
define('VIEWS_PATH', ROOT . 'Views');

try{
require_once (ROOT.DS.'lib'.DS.'init.php');
require_once (ROOT.DS.'lib'.DS.'App.php');

Components\DbComponent::register('db');
Components\AuthComponent::register('auth');
Components\PaginationComponent::register('pagination');
Components\CommentsComponent::register('comments');


$uri = $_SERVER['REQUEST_URI'];

session_start();
    $app = \App::getInstance();
    $app->init();
$app->run($uri);


} catch (Exception $e){
    Session::setFlash("<b style='color: red'>" . $e->getMessage() . PHP_EOL . "</b>" . "<br>");
    $app = \App::getInstance();
    $app->init();
    $app->run('');
}