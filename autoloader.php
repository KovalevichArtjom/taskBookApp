<?php
//load class in classes
function loadClassInClasses($class_name){
    $file = $_SERVER['DOCUMENT_ROOT'].'/classes/class.'.$class_name. '.php';
    if ( file_exists($file) ){
        require_once ($file);
    }
}
spl_autoload_register('loadClassInClasses');