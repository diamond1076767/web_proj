<?php include("includes/header.php");
allowedRole([1,2,3]);?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">
                Add New Customer <a href="customer.php" class="btn btn-primary float-end">Back</a>
            </h4>
        </div>
        <div class="card-body">
            <?php alertMessage();?>

            <!-- Customer Form -->
            <form action="admin-code.php" method="POST">

                <div class="row">
                    <!-- Customer Name Input -->
                    <div class="col-md-12 mb-3">
                        <label for="name">Customer Name *</label>
                        <input type="text" id="name" name="name" required class="form-control">
                    </div>

                    <!-- Company Name Input -->
                    <div class="col-md-12 mb-3">
                        <label for="companyName">Company *</label> <br />
                        <input type="text" id="companyName" name="companyName" required class="form-control">
                    </div>

                    <!-- Email Input -->
                    <div class="col-md-12 mb-3">
                        <label for="email">Email *</label> <br />
                        <input type="email" id="email" name="email" required class="form-control">
                    </div>

                    <!-- Telephone Input -->
                    <div class="col-md-12 mb-3">
                        <label for="phone">Telephone *</label> <br />
                        <input type="text" id="phone" name="phone" required class="form-control">
                    </div>

                    <!-- Submit Button -->
                    <div class="col-md-12 mb-3 text-start">
                        <br />
                        <button type="submit" name="saveCustomer" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </form>
            <!-- End Customer Form -->
        </div>
    </div>
</div>

<?php include("includes/footer.php");?>
