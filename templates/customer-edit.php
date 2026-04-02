<?php 
include("includes/header.php");
allowedRole([1,2,3]);
?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Edit Customer
                <a href="customer.php" class="btn btn-primary float-end">Back</a>
            </h4>
        </div>
        <div class="card-body">
            <?php alertMessage(); ?>
            <form action="admin-code.php" method="POST">
            
                <?php 
                // 1. Capture the ID from URL (GET) or Session (POST fallback)
                $cusID = null;

                if (isset($_GET['id'])) {
                    $cusID = validate($_GET['id']);
                    $_SESSION['cusID'] = $cusID; // Keep ID for redirect reloads
                } elseif (isset($_POST['cusId'])) {
                    $cusID = validate($_POST['cusId']);
                    $_SESSION['cusID'] = $cusID; // Sync to session for consistency
                } elseif (isset($_SESSION['cusID'])) {
                    $cusID = validate($_SESSION['cusID']);
                }

                // 2. Fetch data if ID exists
                if ($cusID) {
                    $customer = getById('customer', $cusID);

                    if($customer['status'] == 200) {
                        $customerData = $customer['data'];
                ?>     
    
                <input type="hidden" name="customerId" value="<?= validate($customerData['_id']); ?>">
    
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="customerName">Customer Name *</label>
                        <input id="customerName" type="text" name="name" value="<?= validate($customerData['customerName']); ?>" required class="form-control" />
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="companyName">Company *</label>
                        <input id="companyName" type="text" name="companyName" value="<?= validate($customerData['companyName']); ?>" required class="form-control">
                    </div>                  

                    <div class="col-md-12 mb-3">
                        <label for="customerEmail">Email *</label> 
                        <input id="customerEmail" type="email" name="email" value="<?= validate(decryption($customerData['email'])); ?>" required class="form-control">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="customerPhone">Telephone *</label> 
                        <input id="customerPhone" type="text" name="phone" value="<?= validate(decryption($customerData['telephone'])); ?>" required class="form-control">
                    </div>

                    <div class="col-md-12 mb-3 text-start">
                        <br/>
                        <button type="submit" name="updateCustomer" class="btn btn-primary">Update Customer</button>
                    </div>
                </div>

                <?php 
                    } else {
                        echo '<h5>'.$customer['message'].'</h5>';
                    }
                } else {
                    echo '<h5>No Customer ID Found in Request.</h5>';
                }
                ?>
            </form>
        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>
