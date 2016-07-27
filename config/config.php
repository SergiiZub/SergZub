<?php
use Classes\Config;


Config::set('site_name', 'News site');

Config::set('languages', array('en', 'fr'));

    //Routes. Route name => method prefix
Config::set('routes', array(
    'default' => '',
    'admin' => 'admin_',
));

Config::set('default_route', 'default');
Config::set('default_language', 'en');
Config::set('default_controller', 'news');
Config::set('default_action', 'index');

Config::set('db.host', 'localhost');
Config::set('db.user', 'root');
Config::set('db.password', '');
Config::set('db.db_name', 'mvc');

Config::set('db', [
    'host' => 'localhost',
    'port' => '3306',
    'user' => 'root',
    'password' => '',
    'db_name' => 'mvc_practice',
]);

Config::set('salt', 'sdj45ahf5uia545rgy7jka');
Config::set('secret_key', 'jkh4gkl5sdf9jbg7hk');
Config::set('articles_per_page', '5');
