<?php

$config = parse_ini_file('dbcon.ini');

$db_hostname=$config['servername'];
$db_username=$config['username'];
$db_password=$config['password'];
$db_database=$config['dbname'];

$con = mysqli_connect($db_hostname,$db_username,$db_password,$db_database);
if(!$con){
    die("Connection Failed: ".mysqli_connect_error());
}

?>
