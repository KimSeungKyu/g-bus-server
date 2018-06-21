<?php
    $connect = mysql_connect('localhost', 'root', '비밀번호');
    if(!$connect){
        echo "connect fail! ".mysqli_connect_error();
    }else{
        mysql_select_db('subway', $connect);
        mysql_query("set session character_set_connection=utf8;");
        mysql_query("set session character_set_results=utf8;");
        mysql_query("set session character_set_client=utf8;");
        //echo "connect!";
    }
?>
