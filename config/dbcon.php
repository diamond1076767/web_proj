<?php
$db_hostname="127.0.0.1";
$db_username="root";
$db_password="";

$db_database="tpamc";

$con = mysqli_connect($db_hostname,$db_username,$db_password,$db_database);

if(!$con){
    die("Connection Failed: ".mysqli_connect_error());
}

?>
