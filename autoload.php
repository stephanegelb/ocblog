<?php

function autoloaddb($class) {
    $arrayPaths = array('db', 'admin', 'models');
    
    $dir = dirname(__FILE__);
    
    foreach($arrayPaths as $path) {
        $filename = $dir.'/'.$path.'/'.$class.'.php';
        if(file_exists($filename)) {
            require $dir.'/'.$path.'/'.$class.'.php';
        }
    }
}

spl_autoload_register('autoloaddb');


