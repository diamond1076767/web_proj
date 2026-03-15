<?php
include('includes/header.php');

// Redirect logged-in users based on role
if (isset($_SESSION['loggedIn'])) {
    $roleID = validate($_SESSION['loggedInUser']['roleID']);
    header("Location: templates/index.php");
    exit();
}
?>

<!-- Hero Section -->
<section class="landing-hero">
    <div class="container text-center">
        <?php alertMessage(); ?>

        <h1 class="display-4 fw-bold mb-4">SIT Advanced Manufacturing Centre</h1>
        <p class="lead mb-4">Empowering Innovation in Manufacturing & Engineering</p>

        <?php if (!isset($_SESSION['loggedIn'])) : ?>
            <a href="login.php" class="btn btn-primary btn-lg px-5 py-3 shadow">Login</a>
        <?php endif; ?>
    </div>
</section>

<!-- Optional Features / Info Section -->
<section class="landing-info py-5 bg-light">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-4 mb-4">
                <i class="fas fa-cogs fa-3x mb-3 text-primary"></i>
                <h5>Advanced Technology</h5>
                <p>State-of-the-art equipment for innovative manufacturing solutions.</p>
            </div>
            <div class="col-md-4 mb-4">
                <i class="fas fa-users fa-3x mb-3 text-primary"></i>
                <h5>Expert Team</h5>
                <p>Skilled engineers and staff driving innovation in every project.</p>
            </div>
            <div class="col-md-4 mb-4">
                <i class="fas fa-lightbulb fa-3x mb-3 text-primary"></i>
                <h5>Research & Development</h5>
                <p>Continuous improvement and R&D for next-generation manufacturing.</p>
            </div>
        </div>
    </div>
</section>

<?php include('includes/footer.php'); ?>