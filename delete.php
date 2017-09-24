<?php
include "DB.php";
$va = $_POST['board_id'];
$db = new DB();

if($db->delete($con,"board",$va)){
    echo("<script>location.replace('tomainS.php');</script>");
}else{
    echo("<script>location.replace('tomainF.php');</script>");
}

?>
