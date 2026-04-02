<?php
// Include header file with common elements
include("includes/header.php");
allowedRole([1,2,3]);?>

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
                                    <img src="<?= $avatar;?>" style="width:100px;height:100px;" alt="<?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8') ?> profile avatar" />
                                </div>
                                <label for="usernameDisplay">Username</label>
                                <h5 id="usernameDisplay"><?= $username?></h5>
                                <label for="roleDisplay">Role</label>
                                <h6 id="roleDisplay">
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
                                    <label for="fullname">Full Name</label>
                                    <input type="text" id="fullname" class="form-control" name="fullname" placeholder="Enter Full Name" value="<?= decryption($fullname)?>" required/>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" id="email" class="form-control" name="email" placeholder="Enter Email Address" value="<?= decryption($email)?>" required/>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 mt-3">
                                <div class="form-group">
                                    <label for="telephone">Phone</label>
                                    <input type="text" id="telephone" class="form-control" name="telephone" placeholder="Enter Phone Number" value="<?= decryption($telephone)?>" required />
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 mt-3">
                                <div class="form-group">
                                    <label for="dob">Date of Birth</label>
                                    <input type="date" id="dob" class="form-control" name="dob" value="<?= $dob?>"/>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 mt-3">
                                <label for="image">Avatar</label>
                                <input type="file" id="image" name="image" class="form-control"/>
                            </div>
                        </div>
                        <br>
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
