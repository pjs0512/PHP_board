<?php
include "DB.php";
$id = $_POST['id'];
$passwd = $_POST['passwd'];

$db = new DB();

$selectValue = $db->select($con,array("user_info","id", $id));

if($selectValue[0]['id'] == $id){
    if($selectValue[0]['passwd'] == $passwd){
        session_start();
        $_SESSION['login'] = true;
        $_SESSION['id'] = $selectValue[0]['id'];
        $_SESSION['name'] = $selectValue[0]['name'];
        echo("<script>location.replace('tomainS.php');</script>");
    }
}else{
    echo("<script>location.replace('tomainF.php');</script>");
}



?>

