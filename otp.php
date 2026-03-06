<?php
// Include necessary files and perform authentication checks
require "config/function.php";
require "authenticator.php";

// Check if the user is already logged in
if (isset($_SESSION['loggedIn'])) {
    $roleID = validate($_SESSION['loggedInUser']['roleID']);
    
    // Redirect based on user role
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
}

// Instantiate Authenticator class
$Authenticator = new Authenticator();

// Set up authentication secret for two-factor authentication
    if (isset($_SESSION['user_id'])) {
        
        if ($_SESSION['first_log'] == 1){
            // Generate a random secret for the first login
            $secret = $Authenticator->generateRandomSecret();
            saveSecretToDatabase($_SESSION['user_id'], $secret);
            $_SESSION['auth_secret'] = $secret;
        }else{
            // Use the existing token as the authentication secret
            $_SESSION['auth_secret'] = $_SESSION['token'];
        }
    }else{
        // Redirect to logout if the user ID is not set
        header("location: logout.php");
    }

// Generate QR code URL for Google Authenticator
$qrCodeUrl = $Authenticator->getQR('TPamc', $_SESSION['auth_secret']);

// Initialize session variable for failed authentication attempts
if (!isset($_SESSION['failed'])) {
    $_SESSION['failed'] = false;
}

// Redirect if authentication is already successful
if(isset($_SESSION['authenticate']) && $_SESSION['authenticate'] == "canlogin"){
    header("location: otp.php");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta tags and stylesheets -->
    <meta charset="UTF-8">
    <title>Time-Based Authentication like Google Authenticator</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>

</head>
<body class="bg">
    <!-- HTML body content -->
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3"  style="background: white; padding: 20px; margin-top: 20px;">
                <h1>Time-Based Authentication</h1>
                <p style="font-style: italic;">Please contact administrator if unable to access Google Authenticator.</p>
                <hr>
                <!-- Form for entering authentication code -->
                <form action="check.php" method="post">
                    <div style="text-align: center;">
                        <?php if ($_SESSION['failed']): ?>
                            <!-- Display error message for failed authentication -->
                            <div class="alert alert-danger" role="alert">
                                <strong>Oh snap!</strong> Invalid Code.
                            </div>
                            <?php   
                                $_SESSION['failed'] = false;
                            ?>
                        <?php endif ?>
                            
                        <?php if(isset($_SESSION['auth_secret']) && $_SESSION['first_log'] == 1): ?>
                            <!-- Display QR code for first-time login -->
                            <img style="text-align: center;;" class="img-fluid" src="<?php echo $qrCodeUrl ?>" alt="Verify this Google Authenticator"><br><br>
                        <?php endif; ?>

                        <!-- Input field for entering authentication code -->
                        <input type="text" class="form-control" name="code" placeholder="******" style="font-size: xx-large;width: 200px;border-radius: 0px;text-align: center;display: inline;color: #0275d8;"><br> <br>    
                        
                        <!-- Button to submit the authentication code -->
                        <button type="submit" class="btn btn-md btn-primary" style="width: 200px;border-radius: 0px;">Verify</button><br><br>
                        
                        <!-- Link to go back to the logout page -->
                        <a href="logout.php">Go back</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
