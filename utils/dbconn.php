<?php
    $connect = mysql_connect('localhost', 'root', '��й�ȣ');
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
