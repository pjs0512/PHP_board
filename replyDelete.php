<?php
include "DB.php";
$board_id = $_POST['board_id'];
$reply_id = $_POST['reply_id'];
$db = new DB();

$db->replyDelete($con,$reply_id);
?>
<script>
    location.replace("contentsView.php?board_id="+<?php echo $board_id;?>);
</script>

