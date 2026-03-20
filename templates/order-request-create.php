<?php
// Include header file with common elements
include("includes/header.php");
allowedRole([3]);
// Add Customer Modal
?>
<script>
    var roleID = <?= $_SESSION['loggedInUser']['roleID'] ?? 0 ?>;
</script>

<div class="modal fade" id="addCustomerModal" data-bs-backdrop='static' data-bs-keyboard='false' tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <!-- Modal Dialog -->
    <div class="modal-dialog">
        <!-- Modal Content -->
        <div class="modal-content">
            <div class="modal-header">
                <!-- Modal Title and Close Button -->
                <h1 class="modal-title fs-5" id="exampleModalLabel">Add Customer</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Customer input fields -->
                <div class="mb-3">
                    <label>Enter Customer Name*</label>
                    <input type="text" class="form-control" id="c_name" />
                </div>

                <div class="mb-3">
                    <label>Enter Company (optional)</label>
                    <input type="text" class="form-control" id="c_company" />
                </div>

                <div class="mb-3">
                    <label>Enter Phone No.*</label>
                    <input type="text" class="form-control" id="c_phone" />
                </div>

                <div class="mb-3">
                    <label>Enter Email Address*</label>
                    <input type="text" class="form-control" id="c_email" />
                </div>
            </div>
            <div class="modal-footer">
                <!-- Close and Save buttons -->
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary saveCustomer">Save</button>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid px-4">
    <!-- Create Order Request Card -->
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <!-- Card Title -->
            <h4 class="mb-0">
                Create Order Request
                <a href="order-request.php" class="btn btn-primary float-end">Back</a>
            </h4>
        </div>
        <div class="card-body">
            <?php alertMessage(); ?>

            <!-- Order Request Form -->
            <form action="order-code.php" method="POST">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <!-- Select Product Dropdown -->
                        <label for="">Select Product</label>
                        <select name="product_id" class="form-select mySelect2">
                            <option value="">-- Select Product --</option>
                            <?php
                            $products = getAll('inventory');
                            if ($products && mysqli_num_rows($products) > 0) {
                                foreach ($products as $prodItem) {
                                    $colourID = $prodItem['colourID'];
                                    $colourName = getColourName($colourID);
                            ?>
                                    <option value="<?= $prodItem['_id']; ?>">
                                        <?= $prodItem['title'] . ' - ' . $colourName; ?>
                                    </option>
                            <?php
                                }
                            } else {
                                echo '<option value="">No Product Found</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-12 mb-3">
                        <!-- Quantity Input -->
                        <label for="">Quantity</label>
                        <input type="number" name="quantity" value="1" class="form-control" />
                    </div>

                    <div class="col-md-3 mb-3 text-start">
                        <!-- Add Item Button -->
                        <br />
                        <button type="submit" name="addItem" class="btn btn-primary">Add Item</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Products Card -->
    <div class="card mt-3">
        <div class="card-header">
            <!-- Products Card Title -->
            <h4 class="mb-0">Products</h4>
        </div>
        <div class="card-body" id="productArea">
            <?php
            if (isset($_SESSION['productItems'])) {
                $sessionProducts = $_SESSION['productItems'];
                if (empty($sessionProducts)) {
                    unset($_SESSION['productItemId']);
                    unset($_SESSION['productItems']);
                }
            ?>
                <!-- Products Table -->
                <div class="table-responsive mb-3" id='productContent'>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Product Name</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total Price</th>
                                <th>Remove</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            foreach ($sessionProducts as $key => $item) :
                            ?>
                                <tr>
                                    <td><?= $i++; ?></td>
                                    <td><?= $item['title']; ?></td>
                                    <td><?= $item['price']; ?></td>
                                    <td>
                                        <!-- Quantity Input Group -->
                                        <div class="input-group qtyBox">
                                            <input type="hidden" value="<?= $item['_id']; ?>" class="prodId" />
                                            <input type="hidden" value="<?php
                                                                        $query = "SELECT quantity FROM inventory WHERE _id='{$item['_id']}'";
                                                                        $result = mysqli_query($con, $query);
                                                                        if ($result) {
                                                                            $row = mysqli_fetch_assoc($result);
                                                                            echo $row['quantity'];
                                                                        }
                                                                        ?>" class="maxQty" />
                                            <button class="input-group-text decrement">-</button>
                                            <input type="text" value="<?= $item['quantity']; ?>" class="qty quantityInput" />
                                            <button class="input-group-text increment">+</button>
                                        </div>
                                    </td>
                                    <td class="totalPrice"><?= number_format($item['price'] * $item['quantity'], 2); ?></td>
                                    <td>
                                        <!-- Remove Item Button -->
                                        <a href="order-item-delete.php?index=<?= $key; ?>" class="btn btn-danger">Remove</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>


                <!-- Customer Information Section -->
                <div class="mt-2" id="customerContent">
                    <hr>

                    <form id="placeOrderForm" action="order-code.php" method="POST">
                        <input type="hidden" name="proceedToPlaceBtn" value="1">

                        <div class="row">
                            <div class="col-md-4">
                                <label>Select Payment Mode</label>
                                <select name="payment_mode" id="payment_mode" class="form-select" required>
                                    <option value="">-- Select Payment --</option>
                                    <option value="Cash Payment">Cash Payment</option>
                                    <option value="Online Payment">Online Payment</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label>Enter Customer Phone Number</label>
                                <input type="number" name="cphone" id="cphone" class="form-control" required />
                            </div>

                            <div class="col-md-4">
                                <br />
                                <button type="button" class="btn btn-warning w-100 proceedToPlace">
                                    Proceed to place order
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            <?php
            } else {
                echo '<h5>No Items Added</h5>';
            }
            ?>
        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>