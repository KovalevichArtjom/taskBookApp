<?php
require_once('autoloader.php');
//connection DB
session_start();
$DB = new DB();
//for json requests
if(isset($_POST)){
    if($_POST['dataType'] === 'json'){
        Page::getPage();
        exit;
    }
}

include $_SERVER['DOCUMENT_ROOT'].'/view/main/header.php';
?>
    <div class="container">
        <?php Page::getPage(); ?>
    </div>
<?php include $_SERVER['DOCUMENT_ROOT'].'/view/main/footer.php'; ?>
