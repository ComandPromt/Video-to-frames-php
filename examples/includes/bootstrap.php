<?php

    ini_set('error_reporting', '1');
    ini_set('track_errors', '1');
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');

//  define the error callback
    function __errorHandler()
    {      
        if(error_reporting() === 0)
        {
            return;
        }
        $args = func_get_args();      
        $count = func_num_args(); 
print '<h1 name="salida">No se encuentra el video!</h1>';
    }
    set_error_handler('__errorHandler');
    set_exception_handler('__errorHandler');
    
    $basedir = dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR;
    define('BASE', $basedir);
 
    if(is_file(BASE.'vendor/autoload.php')) require_once BASE.'vendor/autoload.php';
    
    require_once BASE.'autoloader.php';
    require_once BASE.'examples/includes/config.php';
