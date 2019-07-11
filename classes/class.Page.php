<?php


class Page
{
    public static function getPage(){
        $controller = 'main';
        if(isset($_GET['page'])){
            $controller = $_GET['page'];
        }
        $controller = ucfirst($controller);
        $pageClass = $controller.'Controller';
        $path = $_SERVER['DOCUMENT_ROOT'].'/controller/pages/'.$controller.'Controller.php';
        if (file_exists($path)) {
            include $path;
            $pageClass::getPageIndex();
        }else{
            include $_SERVER['DOCUMENT_ROOT'].'/view/pages/404.php';
        }
    }
}