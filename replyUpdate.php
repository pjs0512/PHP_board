<?php
include "DB.php";
$db = new DB();
$board_id = $_POST['board_id'];
$reply_id = $_POST['reply_id'];
$contents = $_POST['contents'];

$db->replyUpdate($con, $reply_id,$contents);
?>
<script>
    location.replace("contentsView.php?board_id="+<?php echo $board_id;?>);
</script>

