<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<link rel="stylesheet" href="../css/bootstrap.css">
<script type="text/javascript" src="../js/bootstrap.js"></script>
<style>
    .container{
        table-layout: fixed;
        padding-right: 5%;
        padding-left: 5%;
    }
</style>
<?php
session_start();
$_SESSION['searchType'] = empty($_SESSION['searchType'])? "title" : $_SESSION['searchType'];
$_SESSION['searchType'] = isset($_POST['searchType'])? $_POST['searchType'] : $_SESSION['searchType'];
$_SESSION['searchValue'] = empty($_SESSION['searchValue'])? "" : $_SESSION['searchValue'];
$_SESSION['searchValue'] = isset($_POST['searchValue'])? $_POST['searchValue'] : $_SESSION['searchValue'];
$_SESSION['page']=empty($_SESSION['page'])? 1 : $_SESSION['page'];
$_SESSION['page'] = isset($_POST['page'])?$_POST['page'] : $_SESSION['page'];
$_SESSION['look'] = empty($_SESSION['look'])? 5 : $_SESSION['look'];
$_SESSION['look' ] = isset($_POST['look'])?$_POST['look'] : $_SESSION['look'];
?>
<div class="text-center"><h1>준상이</h1></div><br><br>
<div class="container text-center">
    <form action='mainView.php' method='post' class="text-right">
        <input type="hidden" name="page" value="1">
            <select name ='look' onchange='this.form.submit()' class='form-control-static btn btn-success'>";
                <option >줄</option>";
                <option value ='5'>5</option>
                <option value ='10'>10</option>
                <option value ='25'>25</option>
            </select>
    </form>
        <table class="table table-bordered table-hover text-center">
            <thead>
                <td width="6%">글번호</td>
                <td width="50%">제목</td>
                <td width="10%">글쓴이</td>
                <td width="11%">날짜</td>
                <td width="6%">조회수</td>
            </thead>
            <?php
            include "DB.php";
            $db = new DB();
            $contentsCount = count($db->searchSelect($con,array($_SESSION['searchType'],$_SESSION['searchValue'])));
            $lastPage = (int)(($contentsCount+$_SESSION['look']-1)/$_SESSION['look']);
            switch ($_POST['arrow']){
                case ">":
                    $_SESSION['page'] +=5;
                    if($_SESSION['page'] >$lastPage){
                        $_SESSION['page'] = $lastPage;
                    }
                    break;
                case ">>":
                    $_SESSION['page'] =$lastPage;
                    break;
                case "<":
                    $_SESSION['page'] -=5;
                    if($_SESSION['page'] < 1){
                        $_SESSION['page'] =1;
                    }
                    break;
                case "<<":
                    $_SESSION['page'] =1;
                    break;
            }
            $selectValue = $db->searchSelect($con,array($_SESSION['searchType'],$_SESSION['searchValue'],$_SESSION['page'],$_SESSION['look']));

            for($i=0;$i<count($selectValue);$i++){?>
            <tr style="font-size: 12px">
                <td><?php echo $selectValue[$i]['board_id']?></td>
                <td style="cursor: pointer" onclick="contentsView(<?php echo $selectValue[$i]['board_id'];?>)">
                    <strong>
                        <?php echo $selectValue[$i]['title']."[".$db->replyCount($con,$selectValue[$i]['board_id'])."]";?>
                    </strong>
                </td>
                <td><?php echo $selectValue[$i]['user_name']."(".$selectValue[$i]['user_id'].")"?></td>
                <td><?php echo $selectValue[$i]['reg_date']?></td>
                <td><?php echo $selectValue[$i]['hits']?></td>
            </tr>
            <?php } ?>
        </table>
    <!--pagenation-->
    <form action="mainView.php" method="post">
    <?php
    echo "<input type='submit' class ='btn btn-warning' name='arrow' value='<<'>&nbsp";
    echo "<input type='submit' class ='btn btn-warning' name='arrow' value='<'>&nbsp";
        for($i = $_SESSION['page']-2; $i < $_SESSION['page']+3; $i++){
                if($i == $_SESSION['page']){
                    echo "<input type='submit' class='btn btn-muted' name='page' value='$i'>&nbsp";
                }
                else if($i>0 && $i<=$lastPage){
                    echo "<input type='submit' class='btn btn-success' name='page' value='$i'>&nbsp";
                }
        }
    echo "<input type='submit' class ='btn btn-warning' name='arrow' value='>'>&nbsp";
    echo "<input type='submit' class ='btn btn-warning' name='arrow' value='>>'>";
    ?>
    </form>
    <input type="button" class="btn btn-success" data-toggle='modal'
        <?php if($_SESSION['login']){
            echo " data-target='#logout' value='로그아웃'>";
        }else{
            echo " data-target='#login' value='로그인'>";
            echo "&nbsp<input type='button' class='btn btn-success' data-toggle='modal' data-target='#join' value ='회원가입'>";
        }
        ?>
    <input type="button" class="btn btn-success" data-toggle='modal' data-target='#search' value="검색">
    <input type="button" class="btn btn-success" data-toggle='modal'
        <?php if($_SESSION['login']) {
            echo " data-target='#write' value ='글쓰기'>";
        }else{
            echo " data-target='#loginError' value ='글쓰기'>";
        }
        ?>
</div>
<script>
    function contentsView(num){
        location.replace("contentsView.php?board_id="+num);
    }
    function idOk(event) {
        document.getElementById("idOk").innerText = "";
        var idReg = /^[A-za-z0-9]{5,15}/g;
        if(idReg.test(event.value)){
            document.getElementById('idOk').className = "text-success";
            document.getElementById("idOk").innerText = "사용할수 있는 아이디 입니다.";
            document.getElementsByName("idCheck")[0].value = true;
        }
        else{
            document.getElementById('idOk').className = "text-danger";
            document.getElementById("idOk").innerText = "사용하실수 없는 아이디 입니다.";
        }
    }
    function passwordOK(event) {
        document.getElementById("OK").innerText = "";
        if (document.getElementById('pw').value == event.value) {
            document.getElementById('OK').className = "text-success";
            document.getElementById('OK').innerText = "비밀번호가 일치합니다.";
        } else {
            document.getElementById('OK').className = "text-danger";
            document.getElementById('OK').innerText = "비밀번호가 일치하지 않습니다.";
        }
    }
    function overlap() {
        var idValue = document.getElementById("idValue").value;
        Ajax();
        function Ajax() {
            var url = "http://localhost/Keiziban/idCheck.php?idValue="+idValue;
            var xml = new XMLHttpRequest();
            xml.open("GET", url, true); // POST 방식
            xml.onreadystatechange = function () {
                if (xml.readyState == 4 && xml.status == 200) {
                    alert(xml.responseText);
                    if(xml.responseText == "사용 가능한 아이디 입니다.")
                        document.getElementsByName("idCheck2")[0].value = true;
                }
            };
            xml.send(url);
        }
    }

</script>

<!--글쓰기-->
<form action="write.php" method="post">
    <div class="modal fade" id="write" tabindex="1">
        <div class="modal-dialog" >
            <div class="modal-content">
                <div class="modal-header">
                    <strong>제목</strong><br>
                    <input type="text" class="form-control" name ="subject">
                </div>
                <div class="modal-body">
                    <strong>내용</strong><br>
                    <textarea class="form-control" rows="20" name ="contents"></textarea>
                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn btn-success" value ="확인">
                    <input type="button" class="btn btn-warning" data-dismiss="modal" value="취소">
                </div>
            </div>
        </div>
    </div>
</form>
<!--로그인-->
<form action="login.php" method="post">
    <div class="modal fade" id="login" tabindex="1">
        <div class="modal-dialog" >
            <div class="modal-content">
                <div class="modal-header">
                    <strong>아이디</strong><br>
                    <input type="text" class="form-control" name ="id">
                </div>
                <div class="modal-body">
                    <strong>비밀번호</strong><br>
                    <input type="password" class="form-control" name ="passwd">
                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn btn-success" value ="확인">
                    <input type="button" class="btn btn-warning" data-dismiss="modal" value="취소">
                </div>
            </div>
        </div>
    </div>
</form>
<!--로그아웃-->
<form action="logout.php" method="post">
    <div class="modal fade" id="logout" tabindex="1">
        <div class="modal-dialog" >
            <div class="modal-content">
                <div class="modal-body">
                    <strong>로그아웃 하시겠습니까?</strong><br>
                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn btn-success" value ="확인">
                    <input type="button" class="btn btn-warning" data-dismiss="modal" value="취소">
                </div>
            </div>
        </div>
    </div>
</form>
<!--로그인 후 이용-->
    <div class="modal fade" id="loginError" tabindex="1">
        <div class="modal-dialog" >
            <div class="modal-content">
                <div class="modal-body">
                    <strong>로그인 후 이용해 주세요</strong><br>
                </div>
                <div class="modal-footer">
                    <input type="button" class="btn btn-success" data-toggle='modal' data-target='#login' data-dismiss="modal" value='로그인'>
                    <input type="button" class="btn btn-warning" data-dismiss="modal" value="취소">
                </div>
            </div>
        </div>
    </div>
<!--검색-->
<form action="mainView.php" method="post">
    <div class="modal fade" id="search" tabindex="1">
        <div class="modal-dialog" >
            <div class="modal-content">
                <div class="modal-header">
                <select class="form-control" style="float: left;width:100px;" name="searchType">
                    <option value="title">제목</option>
                    <option value="contents">내용</option>
                    <option value="user_name">작성자</option>
                </select><br>
                </div>
                <div class="modal-body">
                    <strong>검색어</strong><br>
                    <input type="text" class="form-control" name ="searchValue">
                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn btn-success" value ="확인">
                    <input type="button" class="btn btn-warning" data-dismiss="modal" value="취소">
                </div>
            </div>
        </div>
    </div>
</form>
<!--회원가입-->
<form action="join.php" method="post">
    <div class="modal fade" id="join" tabindex="1">
        <div class="modal-dialog" >
            <div class="modal-content">
                <div class="modal-header" id ="joinHeader">
                    <p class="text-left"><strong>아이디 (5자리 이상 15자리 이하 문자,숫자)</strong></p>
                    <table>
                        <tr>
                            <td>
                                <input type="text" class="form-control" id="idValue" name ="id" onchange="idOk(this)" style="width: 200px;">
                            </td>
                            <td>
                                <input type="button" class="btn btn-success" value="중복확인" data-toggle="modal" onclick="overlap()">
                            </td>
                        </tr>
                    </table>
                    <strong id="idOk"></strong>
                    <br>
                    <input type="hidden" name ="idCheck" value="">
                    <input type="hidden" name ="idCheck2" value="">
                    <p class="text-left"><strong>비밀번호</strong></p>
                    <input type="password" class="form-control" name ="pw" id="pw">
                    <p class="text-left"><strong>비밀번호 확인</strong></p>
                    <input type="password" class="form-control" name ="pw2" onchange="passwordOK(this)">
                    <br>
                    <strong id ='OK'></strong>
                </div>
                <div class="modal-body">
                    <p class="text-left"><strong>닉네임</strong></p>
                    <input type="text" class="form-control" name ="name">
                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn btn-success" value ="가입하기">
                    <input type="button" class="btn btn-warning" data-dismiss="modal" value="취소">
                </div>
            </div>
        </div>
    </div>
</form>