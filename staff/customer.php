<?php include("includes/header.php");?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Customers
                <a href="customer-create.php" class="btn btn-primary float-end">Add New Customer</a>
            </h4>
        </div>
        <div class="card-body">
            <?php alertMessage();?>

            <?php 
            // Retrieve customer data using getAll function
            $customers = getAll('customer');
            if(!$customers){
                echo '<h4>Something Went Wrong!</h4>';
                return false;
            }
            
            if(mysqli_num_rows($customers) > 0) {
            ?>  
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Company</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone No.</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($customers as $item) : ?>
                            <tr>
                                <!-- Output customer data -->
                                <td><?= validate($item['_id']) ?></td>
                                <td><?= validate($item['companyName']) ?></td>
                                <td><?= validate($item['customerName']) ?></td>
                                <td><?= validate(decryption($item['email'])) ?></td>
                                <td><?= validate(decryption($item['telephone'])) ?></td>

                                <td>
                                    <!-- Edit and Delete buttons side by side -->
                                    <form action="customer-edit.php" method="post" style="display: inline-block; margin-right: 1px;">
                                        <input type="hidden" name="customerId" value="<?= validate($item['_id']) ?>">
                                        <button type="submit" class="btn btn-success btn-sm">Edit</button>
                                    </form>
                                    
                                    <form action="customer-delete.php" method="post" style="display: inline-block;">
                                        <input type="hidden" name="customerId" value="<?= validate($item['_id']) ?>">
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this customer?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <?php 
            } else {
            ?>
                <h4 class="mb-0">No Record found</h4>
            <?php
            }
            ?>
        </div>
    </div>
</div>

<?php include("includes/footer.php");?>
