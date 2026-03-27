<?php include('includes/header.php');

if(isset($_SESSION['loggedIn'])){
    $roleID = validate($_SESSION['loggedInUser']['roleID']);
    header("Location: templates/index.php");
}
?>

<link rel="stylesheet" href="assets/css/login.css" />

<div class="login-wrapper">
    <div class="login-card">

        <div class="brand-icon">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
            </svg>
        </div>

        <h4>Welcome back</h4>
        <p class="subtitle">Sign in to your account to continue</p>

        <?php alertMessage(); ?>

        <form action="login-code.php" method="POST">

            <div class="mb-3">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" class="form-control" placeholder="Enter your username" required />
            </div>

            <div class="mb-3">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required />
            </div>

            <button type="submit" name="loginBtn" class="btn-login">
                Sign In
            </button>

        </form>
    </div>
</div>

<?php include('includes/footer.php'); ?>