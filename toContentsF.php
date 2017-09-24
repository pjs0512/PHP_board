<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<link rel="stylesheet" href="../css/bootstrap.css">
<script type="text/javascript" src="../js/bootstrap.js"></script>
<style>
    .layer{
        position:absolute;
        top:20%;
        left:50%;
        width:200px;
        color: #555555;
        height:100px;
        margin:-50px 0 0 -50px;
    }
</style>
<div class="container layer text-center" >
    <h1>실패</h1><br><br>
    <input type="button" class="btn btn-warning" value="이전으로" onclick="toMain();">
</div>
<script>
    function toMain(){
        location.replace("contentsView.php/")
    }
</script>