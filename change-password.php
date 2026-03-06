<?php include('config/function.php');

// Check if the user is already logged in and can log in
if(isset($_SESSION['loggedIn'])){
    // Redirect the user based on their role
    $roleID = validate($_SESSION['loggedInUser']['roleID']);
    switch ($roleID) {
        case 1:
            header("Location: admin/index.php");
            exit();
        case 2:
            header("Location: manager/index.php");
            exit();
        case 3:
            header("Location: staff/index.php");
            exit();
    }
} else {
    // If not logged in or cannot log in, redirect to the login page
    header("Location: login.php");
    exit();
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TP AMC</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>
<body>

<div class="py-5 bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow rounded-4">
                    <?php alertMessage();?>
                    <div class="p-5">
                        <h4 class="text-dark mb-3">Reset Password</h4>
                        <form action="login-code.php" method="POST"> 
                            <div class="mb-3">
                                <label>Password</label>
                                <input type="password" name="pass" class="form-control" required/>
                            </div>

                            <div class="mb-3">
                                <label>Confirm Password</label>
                                <input type="password" name="conpass" class="form-control" required/>
                            </div>
                            <div class="mb-3">
                                <button type="submit" name="resetBtn" class="btn btn-primary w-100 mt-2">
                                    Update Now
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php');?>
