<?php
include('includes/header.php');

if (isset($_SESSION['loggedIn'])) {
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
}

?>

<div class="py-5" style="background-image: url('assets/images/tpamc-bg.png'); background-size: cover;">
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12 py-5 text-center">

                <?php alertMessage(); ?>

                <h1 class="mt-3">TP Advanced Manufacturing Centre</h1>

                <?php if (!isset($_SESSION['loggedIn'])) : ?>
                    <a href="login.php" class="btn btn-primary mt-4">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
