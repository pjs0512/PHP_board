<?php
include "DB.php";
$id = isset($_GET['idValue'])? $_GET['idValue'] : "뚜띠";

$db = new DB();

$value = mysqli_num_rows($db->select($con,array("user_info","id",$id)));

if($value){
    echo "중복된 아이디가 있습니다.";
}
else{
    echo "사용 가능한 아이디 입니다.";
}
?>