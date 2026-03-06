<?php
// Include header file with common elements
include("includes/header.php");
?>

<div class="container" style="margin-top:60px">
    <?php alertMessage();?>

    <!-- Form for updating staff member's profile -->
    <form action="admin-code.php" method="POST" enctype="multipart/form-data">
        <div class="row gutters">
            <!-- Section for user profile details -->
            <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12">
                <div class="card h-100">
                    <div class="card-body">
                        <?php 
                            // Fetch user details from session
                            $username = validate($_SESSION['loggedInUser']['username']);
                            $email = validate($_SESSION['loggedInUser']['email']);
                            $telephone = validate($_SESSION['loggedInUser']['phone']);
                            $dob = validate($_SESSION['loggedInUser']['dob']);
                            $fullname = validate($_SESSION['loggedInUser']['fullname']);
                            $avatar = validate($_SESSION['loggedInUser']['image']);
                            $roleID = validate($_SESSION['loggedInUser']['roleID']);
                        ?>
                        <div class="account-settings">
                            <div class="user-profile">
                                <div class="user-avatar">
                                    <img src="<?= $avatar;?>" style="width:100px;height:100px;" alt="Img" />
                                </div>
                                <label>Username</label>
                                <h5><?= $username?></h5>
                                <label>Role</label>
                                <h6>
                                    <?php 
                                    // Fetch and display user's role
                                    $result = mysqli_query($con, "SELECT roleName FROM role WHERE _id='$roleID'");
                                    if ($result) {
                                        $roleName = mysqli_fetch_assoc($result);
                                        echo $roleName['roleName'];
                                    }
                                    ?>
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section for updating personal details -->
            <div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 col-12">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="row gutters">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <h6 class="mb-2 text-primary">Personal Details</h6>
                            </div>
                            <!-- Form inputs for updating personal details -->
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Full Name</label>
                                    <input type="text" class="form-control" name="fullname" placeholder="Enter Full Name" value="<?= $fullname;?>" required/>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" class="form-control" name="email" placeholder="Enter Email Address" value="<?= decryption($email)?>" required/>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 mt-3">
                                <div class="form-group">
                                    <label>Phone</label>
                                    <input type="text" class="form-control" name="telephone" placeholder="Enter Phone Number" value="<?= decryption($telephone)?>" required />
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 mt-3">
                                <div class="form-group">
                                    <label>Date of Birth</label>
                                    <input type="date" class="form-control" name="dob" value="<?= $dob?>"/>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 mt-3">
                                <label>Avatar</label>
                                <input type="file" name="image" class="form-control"/>
                            </div>
                        </div>
                        </br>
                        <div class="row gutters">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <!-- Buttons for updating and logging out -->
                                <div class="text-right">
                                    <button type="submit" name="updateProfile" class="btn btn-warning">
                                        Update
                                    </button>
                                    <a href="../logout.php" class="btn btn-primary">Logout</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<?php
// Include footer file with common elements
include("includes/footer.php");
?>
