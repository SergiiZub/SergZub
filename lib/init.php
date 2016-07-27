<?php
use Classes\Lang;

//function __autoload($class_name){
//    $lib_path = ROOT.DS.'lib'.DS.strtolower($class_name).'.class.php';
//    $controllers_path = ROOT.DS.'Controllers'.DS.str_replace('controller', '', strtolower($class_name)).'.controller.php';
//    $model_path = ROOT.DS.'Models'.DS.strtolower($class_name).'.php';
//
//    if (file_exists($lib_path)){
//        require_once ($lib_path);
//    } elseif (file_exists($controllers_path)){
//        require_once ($controllers_path);
//    } elseif (file_exists($model_path)){
//        require_once ($model_path);
//    } else {
//        throw new Exception('Failed to include class' . $class_name);
//    }
//}
//
function __($key, $default_value = ''){
    return Lang::get($key, $default_value);
}



spl_autoload_extensions('.php');

set_include_path(ROOT.'Classes'.DS);
set_include_path(ROOT.'Controllers'.DS);
set_include_path(ROOT.'Models'.DS);
set_include_path(ROOT.'Components'.DS);
set_include_path(ROOT.'Core'.DS);

spl_autoload_register(function ($class){
    $class = ltrim($class, '\\');
    $file = str_replace('\\', DS, $class) . '.php';
    if (!file_exists(ROOT.$file)){
        throw new Exception('File '.$class.'.php not found!');
    }
    require_once ROOT.$file;
});
require_once (ROOT.'config'.DS.'config.php');