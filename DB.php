<?php
session_start();

$con = mysqli_connect("localhost","root","autoset", "board");

class DB
{
    public function select($con, $length)
    {
        //$length[0] = table;
        //$length[1] = row;
        //$length[2] = value;
        switch (count($length)) {
            case 1:
                $query = "select * from $length[0]";
                $result = mysqli_query($con, $query);
                $value = array();
                for ($i = 0; $i < mysqli_num_rows($result); $i++)
                    array_push($value, mysqli_fetch_assoc($result));
                return $value;
            case 2:
                $pageTo = ($length[0] - 1) * $length[1];
                $query = "select * from board ORDER by reg_date desc limit $pageTo,$length[1]";
                $result = mysqli_query($con, $query);
                $value = array();
                for ($i = 0; $i < mysqli_num_rows($result); $i++)
                    array_push($value, mysqli_fetch_assoc($result));
                return $value;
            case 3:
                $query = "select * from $length[0] where $length[1] = '$length[2]'";
                $result = mysqli_query($con, $query);
                $value = array();
                for ($i = 0; $i < mysqli_num_rows($result); $i++)
                    array_push($value, mysqli_fetch_assoc($result));
                return $value;
        }
    }

    public function insert($con, $table, $length)
    {
        if ($table == "board") {
            $query = "insert into $table values($length[0],'$length[1]','$length[2]',$length[3],'$length[4]','$length[5]','$length[6]')";
        } else if ($table == "reply") {
            $date = date("y-m-d/H:i:s");
            $query = "insert into $table values($length[0],$length[1],'$length[2]','$length[3]','$length[4]',$length[5],'$date')";
        } else {
            $query = "insert into $table values('$length[0]', '$length[1]', '$length[2]', '$length[3]')";
        }

        if (mysqli_query($con, $query))
            return $query;
        else
            return $query;
    }

    public function delete($con, $table, $value)
    {
        $query1 = "delete from $table where board_id = $value";
        $query2 = "delete from reply where board_id = $value";
        if (mysqli_query($con, $query1) && mysqli_query($con, $query2)) {
            return true;
        } else {
            return false;
        }

    }
    public function tableDelete($con, $table, $value){
        $query = "delete from $table where board_id = $value";
        mysqli_query($con, $query);
    }
    public function tableUpdate($con,$table,$board_id, $subject,$contents,$hits,$date,$userid,$username){
        $this->tableDelete($con,$table,$board_id);
        $this->insert($con,"board",array(0, $subject,$contents,$hits,$date,$userid,$username));
        $query = "select * from board ORDER by reg_date desc";
        $result = mysqli_query($con,$query);
        $value = mysqli_fetch_assoc($result)['board_id'];
        $query = "update reply set board_id = $value where board_id = $board_id";
        mysqli_query($con,$query);
    }

    public function update($con, $table, $hits, $board_id)
    {
        $hits += 1;
        $query = "update $table set hits=$hits where board_id = $board_id";
        mysqli_query($con, $query);
    }

    public function searchSelect($con, $length)
    {
        $pageTo = ($length[2] - 1) * $length[3];
        if (count($length) == 2)
            $query = "select * from board WHERE $length[0] like'%$length[1]%'";
        if (count($length) == 4)
            $query = "select * from board where $length[0] LIKE '%$length[1]%' ORDER by reg_date desc limit $pageTo,$length[3]";

        $result = mysqli_query($con, $query);
        $value = array();
        for ($i = 0; $i < mysqli_num_rows($result); $i++)
            array_push($value, mysqli_fetch_assoc($result));
        return $value;
    }

    public function replyCount($con, $board_id)
    {
        $query = "select * from reply WHERE board_id = $board_id";
        $result = mysqli_query($con, $query);
        return mysqli_num_rows($result);
    }

    public function replySelect($con, $length)
    {
        //$length 0 = board_id
        //$length 1 = reply_sub
        if (count($length) == 1) {
            $query = "select * from reply where board_id = $length[0] ORDER by reg_date ";
        }
        if (count($length) == 2) {
            $query = "select * from reply where board_id = $length[0] and reply_id = $length[1] ORDER by reg_date";
        }
        $result = mysqli_query($con,$query);
        $value = array();
        for ($i = 0; $i < mysqli_num_rows($result); $i++)
            array_push($value, mysqli_fetch_assoc($result));
        return $value;
    }

    public function replyDelete($con, $value)
    {
        $deleteValue = array();
        array_push($deleteValue, $value);
        $arrValue = array();
        array_push($arrValue, $value);
            for($j=0;$j<count($arrValue);$j++) {
                $query = "select * from reply where reply_subid = $arrValue[$j]";
                $result = mysqli_query($con, $query);
                for($i = 0 ; $i< mysqli_num_rows($result);$i++){
                $reply_id = mysqli_fetch_assoc($result)['reply_id'];
                array_push($deleteValue, $reply_id);
                array_push($arrValue, $reply_id);
                }
            }

            foreach ($deleteValue as $item) {
                $query = "delete from reply where reply_id = $item";
                mysqli_query($con, $query);
            }
            return true;
        }
    public function replyUpdate($con,$reply_id,$contents){
        $contents = nl2br(str_replace(" ","&nbsp",$contents));
        $query = "update reply set contents = '$contents' WHERE reply_id = $reply_id";
        if(mysqli_query($con, $query)){
            return true;
        }else{
            return false;
        }

    }
}
?>