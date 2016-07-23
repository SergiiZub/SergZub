<?php

spl_autoload_extensions('.php');

set_include_path(ROOT.'Classes');
set_include_path(ROOT.'Controllers');
set_include_path(ROOT.'Models');
set_include_path(ROOT.'Components');


spl_autoload_register(function ($class){
    $class = ltrim($class, '\\');
    $file = str_replace('\\', DS, $class) . '.php';
    require_once $file;
});