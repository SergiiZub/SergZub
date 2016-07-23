<?php

spl_autoload_extensions('.php');

set_include_path(ROOT.'app'.DS.'Core');
set_include_path(ROOT.'app'.DS.'Controllers');
set_include_path(ROOT.'app'.DS.'Models');
set_include_path(ROOT.'app'.DS.'Components');


spl_autoload_register(function ($class){
    $class = ltrim($class, '\\');
    $file = str_replace('\\', DS, $class) . '.php';

    require_once ROOT.'app'.DS.$file;
});