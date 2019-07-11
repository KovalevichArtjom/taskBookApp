<?php
include $_SERVER['DOCUMENT_ROOT'] . '/model/MainModel.php';

class MainController
{
    public static function getPageIndex(){
        $message = '';
        //pagination settings
        $numberStr = 3;
        $pagination = 1;//Number Page Default
        $sortingTask = 0;//Sorting table tasks
        $arrSorying = array(
            'users.name',//имени пользователя
            'users.email',// е-mail
            'tasks.statusTasks desc'//статусу
        );
        $AddInTitleStatus = '(Пользователь)';
        $modalEditingTask = false;
        //setting for Admin
        if(isset($_SESSION)){
            if($_SESSION['right_admin']){
                $AddInTitleStatus = $_SESSION['right_admin'];
                $modalEditingTask = true;
            }
        }
        if(isset($_GET)){
            if($_GET['pagination']){
                $pagination = $_GET['pagination'];
            }
            if($_GET['sortingTask']){
                $sortingTask = $_GET['sortingTask'];
            }
        }

        //pagination cal
        $amountStr = $pagination * $numberStr;
        $startPoint = $amountStr - $numberStr;
        $indPoint = $amountStr;


        $AmountStrAll = MainModel::getAmountStr();
        $amountPage = ceil($AmountStrAll/$numberStr);
        if($amountPage != 0) {
            if ($pagination == $amountPage) {
                $nextPage = '#';
                $backPage = $pagination - 1;

            } elseif ($pagination < $amountPage) {
                $nextPage = $pagination + 1;
                $backPage = '#';
            }
        }

        $ListTasks = MainModel::getListTasksAll($startPoint,$indPoint,$arrSorying[$sortingTask]);
        if(!$ListTasks){
            $message = $ListTasks;
        }
        if(isset($_POST) and $_POST['dataType'] === 'json') {
            if(isset($_POST['action'])){
                if($_POST['action'] == 'getIdTasksByIdTasksAndIdUsers'){
                    $result = MainModel::getIdTasksByIdTasksAndIdUsers($_POST['s_name'],$_POST['s_email'],$_POST['s_textTasks']);
                }
                if ($_POST['action'] === 'insertTask') {
                    $result = MainModel::insertTask($_POST['s_name'], $_POST['s_email'], $_POST['s_textTasks'], $_POST['s_statusTasks']);
                }
                if ($_POST['action'] === 'editingTask') {
                    $result = MainModel::editingTask($_POST['s_idTask'],$_POST['s_textTasks'], $_POST['s_statusTasks']);
                }
                if($_POST['action'] == 'checkedOnAccessRights'){
                    $result = MainModel::checkedOnAccessRights($_POST['s_loginAdmin'], $_POST['s_passwordAdmin']);
                }
            }
            echo json_encode($result);
            exit;
        }
        include $_SERVER['DOCUMENT_ROOT'] . '/view/pages/MainTpl.php';
    }
}