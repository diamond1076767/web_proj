<?php 
include('includes/header.php');

// Check if the user is already logged in and can log in
if(!isset($_SESSION['loggedIn'])){
    header("Location: login.php");
    exit();
}
?>


<div class="py-5 bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow rounded-4">
                    <?php alertMessage();?>
                    <div class="p-5">
                        <h4 class="text-dark mb-3">Update Your Password</h4>
                        <form action="admin-code.php" method="POST"> 
                            <div class="mb-3">
                                <label>Password</label>
                                <input type="password" name="pass" class="form-control" required/>
                            </div>

                            <div class="mb-3">
                                <label>Confirm Password</label>
                                <input type="password" name="conpass" class="form-control" required/>
                            </div>
                            <div class="mb-3">
                                <button type="submit" name="resetBtn" class="btn btn-primary w-100 mt-2">
                                    Update Now
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php');?>
