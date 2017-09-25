<script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
<link rel="stylesheet" href="../css/bootstrap.css">
<script type="text/javascript" src="../js/bootstrap.js"></script>
<style>
    .replyHead{
        font-size: 15px;
        font-weight: bold;
    }
    .replyBody{
        font-size: 15px;
    }
    .replyFoot{
        font-size: 18px;
        float: left;

    }
</style>
<?php
session_start();
include "DB.php";
$db = new DB();
$selectValue = $db->select($con,array("board","board_id",$_GET['board_id']));
$db->update($con,"board",$selectValue[0]['hits'],$_GET['board_id']);
$selectValue = $db->select($con,array("board","board_id",$_GET['board_id']));
?>
<br><br>
<div class="container">
    <table class="table">
        <tr>
            <td width="100" >제목 :</td>
            <td><strong><?php echo $selectValue[0]['title']; ?></strong></td>
        </tr>
        <tr>
            <td width="100" height="500" >내용 :</td>
            <td><small><?php echo $selectValue[0]['contents']; ?></small></td>
        </tr>
        <tr>
            <td  width="100">작성자 :</td>
            <td><strong><?php echo $selectValue[0]['user_id']."(".$selectValue[0]['user_name'].")"; ?></strong></td>
        </tr>
        <tr>
            <td  width="100">작성일 :</td>
            <td><strong><?php echo $selectValue[0]['reg_date']; ?></strong></td>
        </tr>
        <tr>
            <td  width="100">조회수 :</td>
            <td><strong><?php echo $selectValue[0]['hits']; ?></strong></td>
        </tr>
        <tr>
            <td  width="100">댓글 :</td>
            <td id="reply">
                    <?php
                        $selectReply=$db->replySelect($con,array($selectValue[0]['board_id']));
                        for($i=0;$i<count($selectReply);$i++){
                            if($selectReply[$i]['reply_subid']==0){
                                ?>
                                <table id=<?php echo $selectReply[$i]['reply_id']?> class='table table-border' style='table-layout: fixed'>
                                    <tr>
                                        <td>
                                <span class="replyHead">
                                <?php echo $selectReply[$i]['user_name']."(".$selectReply[$i]['user_id'].")"?>
                                </span><br><br>
                                <span class="replyBody">
                                <?php echo $selectReply[$i]['contents']?>
                                </span><br><br>
                                <span class="replyFoot">
                                    <?php echo $selectReply[$i]['reg_date']?>
                                    <input type='button' class = 'btn btn-success btn-xs' data-toggle='modal'
                                    <?php if($_SESSION['login']){
                                    echo "data-target='#replyChild' onclick='transfer(".$selectReply[$i]['reply_id'].")' value ='답글작성'>";
                                     } else {
                                    echo "data-target='#error'value ='답글작성'>";
                                     } ?>
                                    <input type='button' class = 'btn btn-success btn-xs' data-toggle='modal'
                                    <?php
                                    if($_SESSION['id'] == $selectReply[$i]['user_id']){?>
                                     data-target='#replyUpdate' onclick="replyContents('<?php echo $selectReply[$i]['contents']?>',<?php echo $selectReply[$i]['reply_id']?>)" value ='수정'>
                                    <?php }else{
                                        echo "data-target='#error' value ='수정'>";
                                    }?>
                                    <input type='button' class = 'btn btn-success btn-xs' data-toggle='modal'
                                    <?php
                                    if($_SESSION['id'] == $selectReply[$i]['user_id']){
                                    echo "data-target='#replyDelete' onclick='replyTran(".$selectReply[$i]['reply_id'].")' value ='삭제'>";
                                    }else{
                                    echo "data-target='#error' value ='삭제'>";
                                    }
                                        ?>
                                           </span>
                                        </td>
                                    </tr>
                                </table>
                                    <?php
                            }else{
                                        $selectName=$db->replySelect($con,array($selectValue[0]['board_id'],$selectReply[$i]['reply_subid']));
                                        $name = $selectName[0]['user_name'];
                                ?>
                                <script type="text/javascript">
                                    var reply_subid = "<?echo($selectReply[$i]['reply_subid'])?>";
                                    document.getElementById(reply_subid);
                                    $("#"+reply_subid).after(
                                        "<table class='table' id=<?php echo $selectReply[$i]['reply_id']?>><?php
                                        echo "<tr><td>";
                                        echo "<span class='replyHead'>";
                                        echo "<span style='color:#5bc0de'>";
                                        echo "@";
                                        echo $name."&nbsp";
                                        echo "</span>";
                                        echo $selectReply[$i]['user_name'];
                                        echo "(".$selectReply[$i]['user_id'].")";
                                        echo "</span><br><br>";
                                        echo "<span class='replyBody'>";
                                        echo $selectReply[$i]['contents'];
                                        echo "</span><br><br>";
                                        echo "<span class='replyFoot'>";
                                        echo $selectReply[$i]['reg_date']."&nbsp";
                                        echo "<input type='button' class = 'btn btn-success btn-xs' data-toggle='modal'";
                                        if($_SESSION['login']){
                                            echo "data-target='#replyChild' onclick='transfer(".$selectReply[$i]['reply_id'].")' value ='답글작성'> ";
                                        }else{
                                            echo "data-target='#error' value ='답글작성'> ";
                                        }
                                        echo "<input type='button' class = 'btn btn-success btn-xs' data-toggle='modal'";
                                        if($_SESSION['id'] == $selectReply[$i]['user_id']){
                                            echo "data-target='#replyUpdate'";
                                            echo "onclick='replyContents(`";
                                            echo $selectReply[$i]["contents"];
                                            echo "`,";
                                            echo $selectReply[$i]['reply_id'];
                                            echo ")'";
                                            echo "value ='수정'> ";
                                        }else{
                                            echo "data-target='#error' value ='수정'> ";
                                        }
                                        echo "<input type='button' class = 'btn btn-success btn-xs' data-toggle='modal'";
                                        if($_SESSION['id'] == $selectReply[$i]['user_id']){
                                            echo "data-target='#replyDelete' onclick='replyTran(".$selectReply[$i]['reply_id'].")' value ='삭제'>";
                                        }else{
                                            echo "data-target='#error' value ='삭제'>";
                                        }
                                        echo "</span>";
                                        echo "</td></tr>"?>
                                        </table>");
                                </script>
                                <?php

                            }
                        }
                    ?>
            </td>
        </tr>
    </table>
    <table class="table">
        <tr>
            <td width="40">
                <input type="button" class = "btn btn-success" data-toggle='modal'
                <?php if($_SESSION['login']) {
                    echo " data-target='#replyWrite' value ='댓글작성'>";
                }else{
                    echo " data-target='#error' value ='댓글작성'>";
                }
                ?>
            </td>
            <td width="40">
                <input type="button" class="btn btn-success" data-toggle='modal'
                <?php if($_SESSION['id'] == $selectValue[0]['user_id']) {
                    echo " data-target='#update' value ='수정'>";
                }else{
                    echo " data-target='#error' value ='수정'>";
                }
                ?>
            </td>
            <td align="left">
            <input type="button" class="btn btn-success" data-toggle='modal'
            <?php if($_SESSION['id'] == $selectValue[0]['user_id']) {
                echo " data-target='#delete' value ='삭제'>";
            }else{
                echo " data-target='#error' value ='삭제'>";
            }
            ?>
            </td>
            <td align="right"><input type="button" class="btn btn-success" value="메인으로" onclick="toMain()"></td>
        </tr>
    </table>
</div>
<script>
    function toMain() {
        location.replace("mainView.php");
    }
    function transfer(arg) {
        document.getElementById('tran').value = arg;
    }
    function replyTran(arg) {
        document.getElementById('DeleteReply').value = arg;
    }
    function replyContents(arg1,arg2) {
        document.getElementById('replyContents').innerText = arg1;
        document.getElementById("rid").value = arg2;
    }

</script>
<!--삭제-->
<form action="delete.php" method="post">
    <div class="modal fade" id="delete" tabindex="1">
        <div class="modal-dialog" >
            <div class="modal-content">
                <div class="modal-body">
                    <strong>정말 삭제 하시겠습니까?</strong><br>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name ="board_id" value=<?php echo $selectValue[0]['board_id']; ?> >
                    <input type="submit" class="btn btn-success" value ="확인">
                    <input type="button" class="btn btn-warning" data-dismiss="modal" value="취소">
                </div>
            </div>
        </div>
    </div>
</form>

<!--에러-->
    <div class="modal fade" id="error" tabindex="1">
        <div class="modal-dialog" >
            <div class="modal-content">
                <div class="modal-body">
                    <strong>권한이 없습니다.</strong><br>
                </div>
                <div class="modal-footer">
                    <input type="button" class="btn btn-success" data-dismiss="modal" value ="확인">
                </div>
            </div>
        </div>
    </div>

<!-- 수정 -->
<form action="update.php" method="post">
    <div class="modal fade" id="update" tabindex="1">
        <div class="modal-dialog" >
            <div class="modal-content">
                <div class="modal-header">
                    <strong>제목</strong><br>
                    <input type="text" class="form-control" name ="subject" value=<?php echo $selectValue[0]['title'];?>>
                </div>
                <div class="modal-body">
                    <strong>내용</strong><br>
                    <textarea class="form-control" rows="20" name ="contents"><?php echo str_replace("<br />","",$selectValue[0]['contents']);?></textarea>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="hits" value=<?php echo $selectValue[0]['hits']?>>
                    <input type="hidden" name='id' value=<?php echo $selectValue[0]['board_id']?>>
                    <input type="hidden" name='user_name' value=<?php echo $selectValue[0]['user_name']?>>
                    <input type="submit" class="btn btn-success" value ="확인">
                    <input type="button" class="btn btn-warning" data-dismiss="modal" value="취소">
                </div>
            </div>
        </div>
    </div>
</form>
<!--댓글-->
<form action="replyWrite.php" method="post">
    <div class="modal fade" id="replyWrite" tabindex="1">
        <div class="modal-dialog" >
            <div class="modal-content">
                <div class="modal-body">
                    <strong>내용</strong><br>
                    <textarea class="form-control" rows="10" name ="contents"></textarea>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name='board_id' value=<?php echo $selectValue[0]['board_id']?>>
                    <input type="submit" class="btn btn-success" value ="확인">
                    <input type="button" class="btn btn-warning" data-dismiss="modal" value="취소">
                </div>
            </div>
        </div>
    </div>
</form>
<!--답글-->
<form action="replyWrite.php" method="post">
    <div class="modal fade" id="replyChild" tabindex="1">
        <div class="modal-dialog" >
            <div class="modal-content">
                <div class="modal-body">
                    <strong>내용</strong><br>
                    <textarea class="form-control" rows="10" name ="contents"></textarea>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="reply_id" id='tran' value="">
                    <input type="hidden" name='board_id' value=<?php echo $selectValue[0]['board_id']?>>
                    <input type="submit" class="btn btn-success" value ="확인">
                    <input type="button" class="btn btn-warning" data-dismiss="modal" value="취소">
                </div>
            </div>
        </div>
    </div>
</form>
<!--댓글삭제-->
<form action="replyDelete.php" method="post">
    <div class="modal fade" id="replyDelete" tabindex="1">
        <div class="modal-dialog" >
            <div class="modal-content">
                <div class="modal-body">
                    <strong>정말 삭제 하시겠습니까?</strong><br>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name ="board_id" value=<?php echo $_GET['board_id'];?>>
                    <input type="hidden" name ="reply_id" id ="DeleteReply" value="" >
                    <input type="submit" class="btn btn-success" value ="확인">
                    <input type="button" class="btn btn-warning" data-dismiss="modal" value="취소">
                </div>
            </div>
        </div>
    </div>
</form>
<!--댓글수정-->
<form action="replyUpdate.php" method="post">
    <div class="modal fade" id="replyUpdate" tabindex="1">
        <div class="modal-dialog" >
            <div class="modal-content">
                <div class="modal-body">
                    <strong>내용</strong><br>
                    <textarea class="form-control" rows="20" id ='replyContents' name ="contents"></textarea>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name='reply_id' id="rid" value="">
                    <input type="hidden" name ="board_id" value=<?php echo $_GET['board_id'];?>>
                    <input type="submit" class="btn btn-success" value ="확인">
                    <input type="button" class="btn btn-warning" data-dismiss="modal" value="취소">
                </div>
            </div>
        </div>
    </div>
</form>



