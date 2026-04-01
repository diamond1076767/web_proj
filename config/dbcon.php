<?php

$config = parse_ini_file(__DIR__ . '/../.env', true);

if (!$config) {
    die(".env file not found. Please create one in project root.");
}

$db_host = $config['database']['DB_SERVER'] ?? 'localhost';
$db_user = $config['database']['USERNAME'] ?? 'root';
$db_pass = $config['database']['PASSWORD'] ?? '';
$db_name = $config['database']['DB_NAME'] ?? '';

$con = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

if (!$con) {
    die("Connection Failed: " . mysqli_connect_error());
}
