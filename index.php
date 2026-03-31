<?php
include('includes/header.php');

if (isset($_SESSION['loggedIn'])) {
    $roleID = validate($_SESSION['loggedInUser']['roleID']);

    header("Location: templates/index.php");
    exit();
}
?>

<!-- Hero Section -->
<section class="py-5 text-white" style="background: linear-gradient(135deg, #1a1f3a 0%, #2a3b6e 50%, #1a4f80 100%); min-height: 70vh; display: flex; align-items: center;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7 py-5">
                <?php alertMessage(); ?>
                <h1 class="display-4 fw-bold mb-3">SG Advanced Manufacturing Centre</h1>
                <p class="lead mb-4" style="opacity: 0.9;">
                    Your trusted partner in precision manufacturing. We specialise in high-quality custom parts, 
                    advanced prototyping, and industrial-grade components for businesses of all sizes.
                </p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="products.php" class="btn btn-light btn-lg px-4">
                        <i class="fas fa-box-open me-2" aria-hidden="true"></i>Browse Products
                    </a>
                    <a href="about.php" class="btn btn-outline-light btn-lg px-4">
                        <i class="fas fa-info-circle me-2" aria-hidden="true"></i>Learn More
                    </a>
                </div>
            </div>
            <div class="col-lg-5 text-center py-4">
                <i class="fas fa-industry" style="font-size: 12rem; opacity: 0.15;" aria-hidden="true"></i>
            </div>
        </div>
    </div>
</section>

<!-- Why Choose Us -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center fw-bold mb-2">Why Choose SG AMC?</h2>
        <p class="text-center text-muted mb-5">Delivering excellence in every component we manufacture</p>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm text-center p-4">
                    <div class="card-body">
                        <i class="fas fa-cogs fa-3x text-primary mb-3" aria-hidden="true"></i>
                        <h5 class="fw-bold">Precision Engineering</h5>
                        <p class="text-muted">State-of-the-art CNC machines and quality control ensure every part meets exact specifications.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm text-center p-4">
                    <div class="card-body">
                        <i class="fas fa-truck-fast fa-3x text-primary mb-3" aria-hidden="true"></i>
                        <h5 class="fw-bold">Fast Turnaround</h5>
                        <p class="text-muted">Rapid prototyping and efficient production pipelines to meet your tightest deadlines.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm text-center p-4">
                    <div class="card-body">
                        <i class="fas fa-shield-halved fa-3x text-primary mb-3" aria-hidden="true"></i>
                        <h5 class="fw-bold">Quality Assured</h5>
                        <p class="text-muted">ISO 9001 certified processes with rigorous testing at every production stage.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center fw-bold mb-2">Our Products</h2>
        <p class="text-center text-muted mb-5">Browse our catalogue of manufacturing components and services</p>
        <div class="row g-4">
            <?php
            $products = getAllVisible('inventory');
            $count = 0;
            if ($products && mysqli_num_rows($products) > 0) {
                foreach ($products as $item) {
                    if ($count >= 4) break;
                    $count++;
            ?>
                <div class="col-md-3 col-sm-6">
                    <div class="card h-100 shadow-sm border-0">
                        <img src="<?= htmlspecialchars($item['image'] != '' ? $item['image'] : 'assets/images/no-img.png', ENT_QUOTES, 'UTF-8') ?>"
                            class="card-img-top" style="height: 180px; object-fit: cover;"
                            alt="<?= htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8') ?> product image" />
                        <div class="card-body">
                            <h6 class="fw-bold"><?= htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8') ?></h6>
                            <p class="text-muted small mb-2">
                                <?= !empty($item['description']) ? htmlspecialchars(substr($item['description'], 0, 80), ENT_QUOTES, 'UTF-8') . '...' : 'No description available' ?>
                            </p>
                            <span class="fw-bold text-primary">$<?= htmlspecialchars($item['cost'], ENT_QUOTES, 'UTF-8') ?></span>
                        </div>
                    </div>
                </div>
            <?php
                }
            } else {
            ?>
                <div class="col-12 text-center">
                    <p class="text-muted">No products available at the moment. Check back soon!</p>
                </div>
            <?php } ?>
        </div>
        <?php if ($count > 0): ?>
            <div class="text-center mt-4">
                <a href="products.php" class="btn btn-primary px-4">View All Products</a>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 text-white" style="background: linear-gradient(135deg, #1a1f3a 0%, #2a3b6e 100%);">
    <div class="container text-center">
        <h2 class="fw-bold mb-3">Ready to Get Started?</h2>
        <p class="lead mb-4" style="opacity: 0.85;">Contact our team for custom quotes and bulk order inquiries.</p>
        <p class="lead mb-4" style="opacity: 0.85;">This is a copyright of the Group P1-7 INF1005 Module. Every Item Listed here IS NOT REAL and a simulated inventory</p>
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="about.php" class="btn btn-light btn-lg px-4">
                <i class="fas fa-envelope me-2" aria-hidden="true"></i>Contact Us
            </a>
            <?php if (!isset($_SESSION['loggedIn'])) : ?>
                <a href="login.php" class="btn btn-outline-light btn-lg px-4">
                    <i class="fas fa-sign-in-alt me-2" aria-hidden="true"></i>Staff Login
                </a>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php include('includes/footer.php'); ?>