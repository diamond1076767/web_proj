<?php include('config/function.php');?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SG Advanced Manufacturing Centre</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

    <?php
    $current_page = basename($_SERVER['SCRIPT_NAME']);
    if ($current_page == "login.php") {
        echo "<link rel='stylesheet' href='assets/css/login.css'>";
    }
    
    ?>

  </head>
<body>

	<?php include('navbar.php')?>
	<main id="main-content">
