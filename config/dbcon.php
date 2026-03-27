<?php
$config = parse_ini_file('dbcon.ini');

if (!$config) {
    die("Error loading database configuration file.");
} else {
    $con = mysqli_connect($config['servername'],$config['username'],$config['password'],$config['dbname']);
    if(!$con){
        die("Connection Failed: ".mysqli_connect_error());
    }   
}
?>
