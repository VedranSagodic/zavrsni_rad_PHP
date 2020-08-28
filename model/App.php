<?php

class App
{
    public static function start()
    {
        $route = Request::getRoute();

        $parts=explode('/',$route);

        $class='';
        if(!isset($parts[1]) || $parts[1]===''){
            $class='Index';
        }else{
            $class=ucfirst($parts[1]);
        }

        $class .= 'Controller';



        $function='';
        if(!isset($parts[2]) || $parts[2]===''){
            $function='index';
        }else{
            $function=$parts[2];
        }
        
        if(class_exists($class) && method_exists($class,$function)){
            $instance = new $class();
            $instance->$function();
        }else{
            echo 'Create function inside class ' . $class . '-&gt;' . $function;
        }
        
    }

    public static function config($key)
    {
        $file = BP . 'configuration.php';
        $configuration = include $file;

        if(array_key_exists($key,$configuration)){
            return $configuration[$key];
        }else if ($configuration['dev']){
            return 'Key ' . $key . ' does not exist ' . $file;
        }else{
            return 'Key ' . $key . ' does not exist';
        }

    }
}