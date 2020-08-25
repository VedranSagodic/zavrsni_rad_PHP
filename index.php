<?php

session_start();
define('BP', __DIR__ . DIRECTORY_SEPARATOR);

$a = implode(PATH_SEPARATOR,
        [
            BP . 'model',
            BP. 'controller'
        ]);

set_include_path($a);

spl_autoload_register(function($class)
{
    $paths = explode(PATH_SEPARATOR,get_include_path());
    foreach($paths as $p){
        if(file_exists($p . DIRECTORY_SEPARATOR . $class . '.php')){
            include $p . DIRECTORY_SEPARATOR . $class . '.php';
            break;
        }
    }
});

App::start();