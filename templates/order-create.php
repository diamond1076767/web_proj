<?php 
include("includes/header.php");
allowedRole([1,2]);
?>

<div class="modal fade" id="addCustomerModal" data-bs-backdrop='static' data-bs-keyboard='false' tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Add Customer</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3"><label for="c_name">Enter Customer Name*</label><input type="text" id="c_name" class="form-control" /></div>
        <div class="mb-3"><label for="c_company">Enter Company (optional)</label><input type="text" id="c_company" class="form-control" /></div>
        <div class="mb-3"><label for="c_phone">Enter Phone No.*</label><input type="text" id="c_phone" class="form-control" /></div>
        <div class="mb-3"><label for="c_email">Enter Email Address*</label><input type="text" id="c_email" class="form-control" /></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary saveCustomer">Save</button>
      </div>
    </div>
  </div>
</div>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Create New Order <a href="orders.php" class="btn btn-primary float-end">Back</a></h4>
        </div>
        <div class="card-body">
            <?php alertMessage(); ?>
            <form action="order-code.php" method="POST">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="product_id">Select Product</label> 
                        <select id="product_id" name="product_id" class="form-select mySelect2" aria-label="Select product">
                            <option value="" selected disabled>-- Select Product --</option>
                            <?php
                            $products = getAll('inventory');
                            if ($products && mysqli_num_rows($products) > 0) {
                                foreach ($products as $prodItem) {
                                    $colourName = getColourName($prodItem['colourID']);
                                    echo '<option value="'.$prodItem['_id'].'">'.$prodItem['title'].' - '.$colourName.'</option>';
                                }
                            } else {
                                echo '<option value="">No Product Found</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="quantity">Quantity</label> 
                        <input type="number" id="quantity" name="quantity" value="1" min="1" class="form-control" aria-label="Enter quantity" required />
                    </div>
                    <div class="col-md-3 mb-3 text-start">
                        <br/><button type="submit" name="addItem" class="btn btn-primary">Add Item</button>
                    </div>
                </div>
            </form>
        </div>
    </div>  

    <div class="card mt-3">
        <div class="card-header"><h4 class="mb-0">Products</h4></div>
        <div class="card-body" id="productArea">
            <?php if(isset($_SESSION['productItems']) && !empty($_SESSION['productItems'])): ?>
                <div class="table-responsive mb-3">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Id</th><th>Product Name</th><th>Price</th><th>Quantity</th><th>Total Price</th><th>Remove</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $i = 1; 
                            foreach($_SESSION['productItems'] as $key => $item) : 
                            ?>
                            <tr>
                                <td><?= $i++; ?></td>
                                <td><?= $item['title'];?></td>
                                <td><?= $item['price'];?></td>
                                <td>
                                    <div class="input-group qtyBox">
                                        <input type="hidden" value="<?= $item['_id'];?>" class="prodId"/>
                                        
                                        <input type="hidden" value="<?php
                                            $productId = $item['_id'];
                                            $inventoryQuery = "SELECT quantity FROM inventory WHERE _id='$productId' LIMIT 1";
                                            $inventoryResult = mysqli_query($con, $inventoryQuery);
                                            if($inventoryResult && mysqli_num_rows($inventoryResult) > 0){
                                                $inventoryRow = mysqli_fetch_assoc($inventoryResult);
                                                echo $inventoryRow['quantity'];
                                            } else {
                                                echo '0';
                                            }
                                        ?>" class="maxQty" />
                                        
                                        <button class="input-group-text decrement" aria-label="Decrease quantity">-</button>
                                        <input type="text" value="<?= $item['quantity']; ?>" class="qty quantityInput" aria-label="Item quantity" />
                                        <button class="input-group-text increment" aria-label="Increase quantity">+</button>
                                    </div>
                                </td>
                                <td><?= number_format($item['price'] * $item['quantity'], 2);?></td>
                                <td><a href="order-item-delete.php?index=<?= $key; ?>" class="btn btn-danger">Remove</a></td>
                            </tr>
                            <?php endforeach;?> 
                        </tbody>
                    </table>
                </div> 
                
                <div class="mt-2">
                    <hr>
                    <form id="placeOrderForm" action="order-code.php" method="POST">
                        <input type="hidden" name="proceedToPlaceBtn" value="1">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="payment_mode">Select Payment Mode</label>
                                <select name="payment_mode" id="payment_mode" class="form-select" aria-label="Select payment mode" required>
                                    <option value="" selected disabled>-- Select Payment --</option>
                                    <option value="Cash Payment">Cash Payment</option>
                                    <option value="Online Payment">Online Payment</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="cphone">Enter Customer Phone Number</label>
                                <input type="text" name="cphone" id="cphone" class="form-control" aria-label="Customer phone number" required />
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
            <?php else: echo '<h5>No Items Added</h5>'; endif; ?>
        </div>
    </div>
</div>

<?php include("includes/footer.php");?>
