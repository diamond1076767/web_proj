<?php

$env = parse_ini_file('../.env');

$db_hostname=$env['DB_SERVER'];
$db_username=$env['USERNAME'];
$db_password=$env['PASSWORD'];
$db_database=$env['DB_NAME'];

$con = mysqli_connect($db_hostname,$db_username,$db_password,$db_database);
if(!$con){
    die("Connection Failed: ".mysqli_connect_error());
}

?>
