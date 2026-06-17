<?php

$db = mysql_connect("localhost","aheevaccs","aheevaccs");

if(!$db){
    die("DB Connection Failed");
}

mysql_select_db("aheevaccs",$db);

?>
