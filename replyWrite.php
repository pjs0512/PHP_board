<?php
include "DB.php";
session_start();
$user_id = $_SESSION['id'];
$user_name = $_SESSION['name'];
$board_id = $_POST['board_id'];
$contents = isset($_POST['contents'])?nl2br(str_replace(" ","&nbsp",$_POST['contents'])) : null;
$reply_id = isset($_POST['reply_id'])? $_POST['reply_id'] : 0;
$db = new DB();

$db->insert($con,"reply",array(0,$reply_id,$contents,$user_id,$user_name,$board_id));
?>
<script>
    location.replace("contentsView.php?board_id="+<?php echo $board_id;?>);
</script>
