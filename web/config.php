<?php

$DB_HOST="localhost";
$DB_USER="your_db_user";
$DB_PASS="your_db_password";
$DB_NAME="your_database";

$db = mysql_connect(
    $DB_HOST,
    $DB_USER,
    $DB_PASS
);

if(!$db){
    die("DB Connection Failed");
}

mysql_select_db($DB_NAME,$db);

?>