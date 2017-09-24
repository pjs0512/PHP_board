<?php
session_start();
include "DB.php";
$db = new DB();
$subject = isset($_POST['subject'])?nl2br(str_replace(" ","&nbsp",$_POST['subject'])) : null;
$contents = isset($_POST['contents'])?nl2br(str_replace(" ","&nbsp",$_POST['contents'])) : null;
$userid = $_SESSION['id'];
$username = $_SESSION['name'];
$date = date("y-m-d/H:i:s");

if($subject != null && $userid != null){
    if($db->insert($con,"board",array(0, $subject,$contents,0,$date,$userid,$username))){
        echo("<script>location.replace('tomainS.php');</script>");
    }
    else{
        echo("<script>location.replace('tomainF.php');</script>");
    }
}else{
    echo("<script>location.replace('tomainF.php');</script>");
}


?>