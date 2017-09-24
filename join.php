<?php

include "DB.php";
$idCheck = isset($_POST['idCheck'])? $_POST['idCheck'] : false;
$idCheck2 = isset($_POST['idCheck2'])? $_POST['idCheck2'] : false;
$id = $_POST['id'];
$pw = $_POST['pw'];
$pw2 = $_POST['pw2'];
$name = $_POST['name'];
$db = new DB();

if($idCheck && $idCheck2 && $pw == $pw2 && $id && $pw && $pw2 && $name){
    if($db->insert($con,"user_info",array("",$id,$pw,$name))){
        echo("<script>location.replace('tomainS.php');</script>");
    }
    else{
        echo("<script>location.replace('tomainF.php');</script>");
    }
}
else{
    echo("<script>location.replace('tomainF.php');</script>");
}

echo "</div>";

?>