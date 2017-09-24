<?php
include "DB.php";
$board_id = $_POST['id'];
$hits = $_POST['hits'];
$subject = isset($_POST['subject'])?nl2br(str_replace(" ","&nbsp",$_POST['subject'])) : null;
$contents = isset($_POST['contents'])?nl2br(str_replace(" ","&nbsp",$_POST['contents'])) : null;
$userid = $_SESSION['id'];
$username = $_SESSION['name'];
$date = date("y-m-d/H:i:s");

$db = new DB();
$db->tableUpdate($con,"board",$board_id, $subject,$contents,$hits,$date,$userid,$username);

echo("<script>location.replace('tomainS.php');</script>");