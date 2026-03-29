<?php include('includes/header.php'); ?>

<!-- Products Hero -->
<section class="py-4" style="background: linear-gradient(135deg, #1a1f3a 0%, #2a3b6e 100%);">
    <div class="container py-3">
        <h1 class="text-white fw-bold mb-1">Our Products</h1>
        <p class="text-white-50 mb-0">Browse our full catalogue of manufacturing components and materials</p>
    </div>
</section>

<!-- Search & Filter Bar -->
<section class="py-3 bg-light border-bottom">
    <div class="container">
        <div class="row g-3 align-items-center">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted" aria-hidden="true"></i></span>
                    <input type="text" id="searchInput" class="form-control border-start-0" 
                           placeholder="Search products..." aria-label="Search products" />
                </div>
            </div>
            <div class="col-md-3">
                <select id="categoryFilter" class="form-select" aria-label="Filter by category">
                    <option value="">All Categories</option>
                    <?php
                    $categories = getAll('categories');
                    if ($categories && mysqli_num_rows($categories) > 0) {
                        foreach ($categories as $cat) {
                            if ($cat['status'] == 0) {
                                echo '<option value="' . htmlspecialchars($cat['categoryName'], ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($cat['categoryName'], ENT_QUOTES, 'UTF-8') . '</option>';
                            }
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-2">
                <select id="sortOrder" class="form-select" aria-label="Sort products">
                    <option value="default">Sort By</option>
                    <option value="price-asc">Price: Low to High</option>
                    <option value="price-desc">Price: High to Low</option>
                    <option value="name-asc">Name: A-Z</option>
                    <option value="name-desc">Name: Z-A</option>
                </select>
            </div>
            <div class="col-md-2 text-end">
                <span id="productCount" class="text-muted small"></span>
            </div>
        </div>
    </div>
</section>

<!-- Product Grid -->
<section class="py-5">
    <div class="container">
        <div class="row g-4" id="productGrid">
            <?php
            $products = getAllVisible('inventory');
            if ($products && mysqli_num_rows($products) > 0) {
                foreach ($products as $item) {
                    // Get category name
                    $catName = '';
                    $categoryID = intval($item['categoryID']);
                    $catStmt = mysqli_prepare($con, "SELECT categoryName FROM categories WHERE _id = ?");
                    if ($catStmt) {
                        mysqli_stmt_bind_param($catStmt, "i", $categoryID);
                        mysqli_stmt_execute($catStmt);
                        $catResult = mysqli_stmt_get_result($catStmt);
                        if ($catResult && $catRow = mysqli_fetch_assoc($catResult)) {
                            $catName = $catRow['categoryName'];
                        }
                        mysqli_stmt_close($catStmt);
                    }

                    // Get colour name
                    $colName = getColourName($item['colourID']);
            ?>
                <div class="col-lg-3 col-md-4 col-sm-6 product-card"
                     data-name="<?= htmlspecialchars(strtolower($item['title']), ENT_QUOTES, 'UTF-8') ?>"
                     data-category="<?= htmlspecialchars($catName, ENT_QUOTES, 'UTF-8') ?>"
                     data-price="<?= htmlspecialchars($item['cost'], ENT_QUOTES, 'UTF-8') ?>">
                    <div class="card h-100 border-0 shadow-sm">
                        <img src="<?= htmlspecialchars($item['image'] != '' ? $item['image'] : 'assets/images/no-img.png', ENT_QUOTES, 'UTF-8') ?>"
                             class="card-img-top" style="height: 200px; object-fit: cover;"
                             alt="<?= htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8') ?> product image" />
                        <div class="card-body d-flex flex-column">
                            <h6 class="fw-bold mb-2"><?= htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8') ?></h6>
                            <?php if (!empty($catName)): ?>
                                <span class="badge bg-light text-dark mb-2 align-self-start"><?= htmlspecialchars($catName, ENT_QUOTES, 'UTF-8') ?></span>
                            <?php endif; ?>
                            <?php if (!empty($colName) && $colName !== 'Unknown Color'): ?>
                                <small class="text-muted mb-2"><i class="fas fa-palette me-1" aria-hidden="true"></i><?= htmlspecialchars($colName, ENT_QUOTES, 'UTF-8') ?></small>
                            <?php endif; ?>
                            <p class="text-muted small flex-grow-1">
                                <?= !empty($item['description']) ? htmlspecialchars(substr($item['description'], 0, 100), ENT_QUOTES, 'UTF-8') : 'No description available.' ?>
                            </p>
                            <div class="d-flex justify-content-between align-items-center mt-auto">
                                <span class="fs-5 fw-bold text-primary">$<?= htmlspecialchars(number_format($item['cost'], 2), ENT_QUOTES, 'UTF-8') ?></span>
                                <?php if ($item['quantity'] > 0): ?>
                                    <span class="badge bg-success"><i class="fas fa-check me-1" aria-hidden="true"></i>In Stock</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Out of Stock</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
                }
            } else {
            ?>
                <div class="col-12 text-center py-5">
                    <i class="fas fa-box-open fa-4x text-muted mb-3" aria-hidden="true"></i>
                    <h4 class="text-muted">No products available</h4>
                    <p class="text-muted">Check back soon for new items in our catalogue.</p>
                </div>
            <?php } ?>
        </div>

        <!-- No results message -->
        <div id="noResults" class="text-center py-5" style="display: none;">
            <i class="fas fa-search fa-3x text-muted mb-3" aria-hidden="true"></i>
            <h5 class="text-muted">No products found</h5>
            <p class="text-muted">Try adjusting your search or filter criteria.</p>
        </div>
    </div>
</section>

<!-- Custom JavaScript for dynamic search, filter, and sort -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    var searchInput = document.getElementById('searchInput');
    var categoryFilter = document.getElementById('categoryFilter');
    var sortOrder = document.getElementById('sortOrder');
    var productGrid = document.getElementById('productGrid');
    var productCount = document.getElementById('productCount');
    var noResults = document.getElementById('noResults');
    var cards = Array.from(document.querySelectorAll('.product-card'));

    function filterAndSort() {
        var searchTerm = searchInput.value.toLowerCase().trim();
        var selectedCategory = categoryFilter.value;
        var selectedSort = sortOrder.value;
        var visibleCount = 0;

        // Filter cards
        cards.forEach(function(card) {
            var name = card.getAttribute('data-name');
            var category = card.getAttribute('data-category');
            var matchesSearch = name.indexOf(searchTerm) !== -1;
            var matchesCategory = selectedCategory === '' || category === selectedCategory;

            if (matchesSearch && matchesCategory) {
                card.style.display = '';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        // Sort visible cards
        var visibleCards = cards.filter(function(c) { return c.style.display !== 'none'; });
        
        visibleCards.sort(function(a, b) {
            switch (selectedSort) {
                case 'price-asc':
                    return parseFloat(a.getAttribute('data-price')) - parseFloat(b.getAttribute('data-price'));
                case 'price-desc':
                    return parseFloat(b.getAttribute('data-price')) - parseFloat(a.getAttribute('data-price'));
                case 'name-asc':
                    return a.getAttribute('data-name').localeCompare(b.getAttribute('data-name'));
                case 'name-desc':
                    return b.getAttribute('data-name').localeCompare(a.getAttribute('data-name'));
                default:
                    return 0;
            }
        });

        // Re-order in DOM
        visibleCards.forEach(function(card) {
            productGrid.appendChild(card);
        });

        // Update count and no-results message
        productCount.textContent = visibleCount + ' product' + (visibleCount !== 1 ? 's' : '') + ' found';
        noResults.style.display = visibleCount === 0 ? '' : 'none';
        productGrid.style.display = visibleCount === 0 ? 'none' : '';
    }

    // Event listeners
    searchInput.addEventListener('input', filterAndSort);
    categoryFilter.addEventListener('change', filterAndSort);
    sortOrder.addEventListener('change', filterAndSort);

    // Initial count
    filterAndSort();
});
</script>

<?php include('includes/footer.php'); ?>
