<?php
include_once ("../config/function.php");

if (isset($_POST['saveUser'])) {
    $roleID = validate($_POST['role_id']);
    $username = validate($_POST['username']);
    $fullname = validate($_POST['fullname']);
    $email = validate($_POST['email']);
    $password = validate($_POST['password']);
    $conpassword = validate($_POST['confirmpassword']);
    $phone = validate($_POST['phone']);
    $lock_acc = validate($_POST['lock_acc']) == true ? 1:0;

    
    if ($roleID != '' && $username != '' && $fullname != '' && $email != '' && $password != '' && $conpassword!= '' && $phone != '') {

        // Check if password match
        if ($password != $conpassword) {
            redirect('admin-create.php', 'Passwords do not match.');
        }
        
        // Check if the password meets the strength criteria
        if (!isPasswordStrong($password)) {
            redirect('admin-create.php', 'Passwords does not meet the requirements: </br>
            1. The password must be at least 8 characters long.</br>
            2. The password must contain at least one uppercase letter.</br>
            3. The password must contain at least one lowercase letter.</br>
            4. The password must contain at least one numeric digit.</br>
            5. The password must contain at least one special character among the following: !@#$%^&*(),.?":{}|<>');
        }else{
            $bcryptpassword = password_hash($password, PASSWORD_BCRYPT);
        }
        
        // Check if username is used by another user
        $userCheck = mysqli_query($con, "SELECT * FROM user WHERE userName='$username'");
        if ($userCheck) {
            if ($userCheck) {
                if (mysqli_num_rows($userCheck) > 0) {
                    redirect('admin-create.php', 'Username Already Used By Another User.');
                }
            }
        }
        
        // Check if email is used by another user
        $emailCheck = mysqli_query($con, "SELECT * FROM user WHERE email='$email' LIMIT 1");
        if ($emailCheck) {
            if ($emailCheck) {
                if (mysqli_num_rows($emailCheck) > 0) {
                    redirect('admin-create.php', 'Email Already Used By Another User.');
                }else{
                    $encryptemail = encryption($email);
                }
            }
        }

        if (!isValidEmailFormat($email)) {
            redirect('admin-create.php', 'Invalid Email Address. Format: <email>@amc.tp.edu.sg');
        }

        // Check if phone number is numeric
        if (!is_numeric($phone)) {
            redirect('admin-create.php', 'Invalid Phone Number. Please enter a numeric value.');
        }
        
        // Check if phone number is used by another user
        $phoneCheck = mysqli_query($con, "SELECT * FROM user WHERE telephone='$phone'");
        if ($phoneCheck) {
            if ($phoneCheck) {
                if (mysqli_num_rows($phoneCheck) > 0) {
                    redirect('admin-create.php', 'Phone Number Already Used By Another User.');
                }else{
                    $encryptphone = encryption($phone);
                }
            }
        }
       
        if (!isAlphabeticFullName($fullname)) {
            redirect('admin-create.php', 'Please enter alphabetic characters.');
        }

        $data = [
            'roleID' => $roleID,
            'userName' => $username,
            'fullName' => $fullname,
            'email' => $encryptemail,
            'password' => $bcryptpassword,
            'telephone' => $encryptphone,
            'lock_acc' => $lock_acc
        ];
        $result = insert('user', $data);

        if ($result) {
            redirect('admin.php', 'User Created Successfully!');
        } else {
            redirect('admin-create.php', 'Something Went Wrong!');
        }
    } else {
        redirect('admin-create.php', 'Please fill required fields');
    }
}

if (isset($_POST['updateUser'])) 
    {
        $userId = validate($_POST['userId']);
        
        $userData = getById('user', $userId);
        if ($userData['status'] != 200) {
            redirect('admin-edit.php', 'Please fill required fields.');
        }
    
        $username = validate($_POST['username']);
        $fullname = validate($_POST['fullname']);
        $email = validate($_POST['email']);
        $password = validate($_POST['password']);
        $conpassword = validate($_POST['confirmpassword']);
        $phone = validate($_POST['phone']);
        $lock_acc = isset($_POST['lock_acc']) == true ? 1:0;

        
        // Check if username is used by another user
        $userCheck = mysqli_query($con, "SELECT * FROM user WHERE userName='$username' AND _id!='$userId'");
        if ($userCheck) {
            if ($userCheck) {
                if (mysqli_num_rows($userCheck) > 0) {
                    redirect('admin-edit.php', 'Username Already Used By Another User.');
                }
            }
        }

        if (!isValidEmailFormat($email)) {
            redirect('admin-edit.php', 'Invalid Email Address. Format: <email>@amc.tp.edu.sg');
        }
        
        // Check if email is used by another user
        $emailCheck = mysqli_query($con, "SELECT * FROM user WHERE email='$email' AND _id!='$userId'");
        if ($emailCheck) {
            if ($emailCheck) {
                if (mysqli_num_rows($emailCheck) > 0) {
                    redirect('admin-edit.php','Email Already Used By Another User');
                }else{
                    $encryptemail = encryption($email);
                }
            }
        }
        
        // Check if phone number is numeric
        if (!is_numeric($phone)) {
            redirect('admin-edit.php', 'Invalid Phone Number. Please enter a numeric value.');
        }
        
        // Check if phone number is used by another user
        $phoneCheck = mysqli_query($con, "SELECT * FROM user WHERE telephone='$phone' AND _id!='$userId'");
        if ($phoneCheck) {
            if ($phoneCheck) {
                if (mysqli_num_rows($phoneCheck) > 0) {
                    redirect('admin-edit.php', 'Phone Number Already Used By Another User.');
                }else{
                    $encryptphone = encryption($phone);
                }
            }
        }
        
        if ($password != '' && $conpassword!='') {
            // Check if password match
            if ($password != $conpassword) {
                redirect('admin-edit.php', 'Passwords do not match.');
            }else{
                // Check if the password meets the strength criteria
                if (!isPasswordStrong($password)) {
                    redirect('admin-create.php', 'Passwords does not meet the requirements: </br>
            1. The password must be at least 8 characters long.</br>
            2. The password must contain at least one uppercase letter.</br>
            3. The password must contain at least one lowercase letter.</br>
            4. The password must contain at least one numeric digit.</br>
            5. The password must contain at least one special character among the following: !@#$%^&*(),.?":{}|<>');
                }else{
                    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                }
            }           
        } else {
            $hashedPassword = $userData['data']['password'];
        }
        
        if ($lock_acc == 0) {
            // Reset failed attempts to 0
            $resetAttemptsQuery = "UPDATE user SET failed_attempts = 0 WHERE _id = $userId";
            mysqli_query($con, $resetAttemptsQuery);
        }

        if ($username != '' && $fullname != '' && $email != '') {
            $data = [
                'userName' => $username,
                'fullName' => $fullname,
                'email' => $encryptemail,
                'password' => $hashedPassword,
                'telephone' => $encryptphone,
                'lock_acc' => $lock_acc,
            ];
            $result = updateData('user', $userId, $data);

            if ($result) {
                redirect('admin-edit.php', 'User Updated Successfully!');
            } else {
                redirect('admin-edit.php', 'Something Went Wrong!');
            }
        } else {
            redirect('admin-edit.php', 'Please fill required fields.');
        }
    }
    
    if(isset($_POST['saveCategory'])){
        $name = validate($_POST['name']);
        $status = isset($_POST['status']) == true ? 1:0;
        
        $data = [
            'categoryName' => $name,
            'status' => $status
        ];
        $result = insert('categories', $data);
        
        if ($result) {
            redirect('categories.php', 'Category Created Successfully!');
        } else {
            redirect('categories-create.php', 'Something Went Wrong!');
        }
    }

    if(isset($_POST['updateCategory'])){
        $categoryId = validate($_POST['categoryId']);
        
        $name = validate($_POST['name']);
        $status = isset($_POST['status']) == true ? 1:0;
        
        $data = [
            'categoryName' => $name,
            'status' => $status
        ];
        $result = update('categories', $categoryId, $data);
        
        if ($result) {
            redirect('categories-edit.php', 'Category Updated Successfully!');
        } else {
            redirect('categories-edit.php', 'Something Went Wrong!');
        }
    }
    
    if(isset($_POST['saveProduct'])){
        
        $category_id = validate($_POST['category_id']);
        $colour_id = validate($_POST['colour_id']);
        $name = validate($_POST['name']);
        $description = validate($_POST['description']);
        
        $price = validate($_POST['price']);
        $quantity = validate($_POST['quantity']);
        $status = isset($_POST['status']) == true ? 1:0;
        
        if($_FILES['image']['size']>0)
        {
            $path = "../assets/uploads/products";
            $image_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            
            $filename = time().'.'.$image_ext;
            
            move_uploaded_file($_FILES['image']['tmp_name'], $path."/".$filename);
            
            $finalImage = "assets/uploads/products/".$filename;
        }else{
            
            $finalImage = "";
        }
        
        $data = [
            'categoryID' => $category_id,
            'colourID' => $colour_id,
            'title' => $name,
            'quantity' => $quantity,
            'cost' => $price,
            'description' => $description,
            'image' => $finalImage,
            'status' => $status
        ];
        $result = insert('inventory', $data);
        
        if ($result) {
            redirect('inventory.php', 'Product Created Successfully!');
        } else {
            redirect('inventory-create.php', 'Something Went Wrong!');
        }
    }
    
    
    if(isset($_POST['updateProduct'])){
        $product_id = validate($_POST['product_id']);
        $productData = getById('inventory', $product_id);
        if(!$productData){
            redirect('inventory.php', 'No Such Product Found');
        }
        
        $category_id = validate($_POST['category_id']);
        $colour_id = validate($_POST['colour_id']);
        $name = validate($_POST['name']);
        $description = validate($_POST['description']);
        
        $price = validate($_POST['price']);
        $quantity = validate($_POST['quantity']);
        $status = isset($_POST['status']) == true ? 1:0;
        
        if($_FILES['image']['size']>0)
        {
            $path = "../assets/uploads/products";
            $image_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            
            $filename = time().'.'.$image_ext;
            
            move_uploaded_file($_FILES['image']['tmp_name'], $path."/".$filename);
            
            $finalImage = "assets/uploads/products/".$filename;
            
            $deleteImage = "../".$productData['data']['image'];
            if(file_exists($deleteImage)){
                unlink($deleteImage);
            }
        }else{
            
            $finalImage = $productData['data']['image'];
        }
        
        $data = [
            'categoryID' => $category_id,
            'colourID' => $colour_id,
            'title' => $name,
            'quantity' => $quantity,
            'cost' => $price,
            'description' => $description,
            'image' => $finalImage,
            'status' => $status
        ];
        $result = update('inventory', $product_id, $data);
        
        if ($result) {
            redirect('inventory-edit.php', 'Product Updated Successfully!');
        } else {
            redirect('inventory-edit.php', 'Something Went Wrong!');
        }
    }
    
    if(isset($_POST['saveCustomer'])){
        $name = validate($_POST['name']);
        $companyName = validate($_POST['companyName']);
        
        $email = validate($_POST['email']);
        $phone = validate($_POST['phone']);
        
        // Check if phone number is numeric
        if (!is_numeric($phone)) {
            redirect('customer-create.php', 'Invalid Phone Number. Please enter a numeric value.');
        }
        
        // Check if phone number is used by another customer
        $phoneCheck = mysqli_query($con, "SELECT * FROM customer WHERE telephone='$phone'");
        if ($phoneCheck) {
            if ($phoneCheck) {
                if (mysqli_num_rows($phoneCheck) > 0) {
                    redirect('customer-create.php', 'Phone Number Already Used By Another Customer.');
                }else{
                    $encryptphone = encryption($phone);
                }
            }
        }
        
        // Check if email is a valid format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            redirect('customer-create.php', 'Invalid Email Address.');
        }
        
        // Check if email is used by another user
        $emailCheck = mysqli_query($con, "SELECT * FROM customer WHERE email='$email'");
        if ($emailCheck) {
            if ($emailCheck) {
                if (mysqli_num_rows($emailCheck) > 0) {
                    redirect('customer-create.php', 'Email Already Used By Another Customer.');
                }else{
                    $encryptemail = encryption($email);
                }
            }
        }

        if (!isAlphabeticFullName($name)) {
            redirect('customer-create.php', 'Please enter alphabetic characters.');
        }
        

        $data = [
            'customerName' => $name,
            'companyName' => $companyName,
            'email' => $encryptemail,
            'telephone' => $encryptphone
        ];
        $result = insert('customer', $data);
        
        if ($result) {
            redirect('customer.php', 'Customer Created Successfully!');
        } else {
            redirect('customer-create.php', 'Something Went Wrong!');
        }
    }
    
    if(isset($_POST['updateCustomer'])){
        
        $customerId = validate($_POST['customerId']);
        $customerData = getById('customer', $customerId);
        if(!$customerData){
            redirect('customer.php', 'No Such Customer Found');
        }
        
        $name = validate($_POST['name']);
        $companyName = validate($_POST['companyName']);
        
        $email = validate($_POST['email']);
        $phone = validate($_POST['phone']);
        
        // Check if phone number is numeric
        if (!is_numeric($phone)) {
            redirect('customer-edit.php', 'Invalid Phone Number. Please enter a numeric value.');
        }
        
        // Check if phone number is used by another customer
        $phoneCheck = mysqli_query($con, "SELECT * FROM customer WHERE telephone='$phone' AND _id !='$customerId'");
        if ($phoneCheck) {
            if ($phoneCheck) {
                if (mysqli_num_rows($phoneCheck) > 0) {
                    redirect('customer-edit.php', 'Phone Number Already Used By Another Customer.');
                }else{
                    $encryptphone = encryption($phone);
                }
            }
        }

        // Check if email is a valid format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            redirect('customer-edit.php', 'Invalid Email Address.');
        }
        
        // Check if email is used by another user
        $emailCheck = mysqli_query($con, "SELECT * FROM customer WHERE email='$email' AND _id !='$customerId'");
        if ($emailCheck) {
            if ($emailCheck) {
                if (mysqli_num_rows($emailCheck) > 0) {
                    redirect('customer-edit.php', 'Email Already Used By Another Customer.');
                }else{
                    $encryptemail = encryption($email);
                }
            }
        }
        
        if (!isAlphabeticFullName($name)) {
            redirect('customer-edit.php', 'Please enter alphabetic characters.');
        }

        $data = [
            'companyName' => $companyName,
            'customerName' => $name,
            'email' => $encryptemail,
            'telephone' => $encryptphone
        ];
        $result = updateData('customer', $customerId, $data);
        
        if ($result) {
            redirect('customer-edit.php', 'Customer Updated Successfully!');
        } else {
            redirect('customer-edit.php', 'Something Went Wrong!');
        }
    }

    if(isset($_POST['saveUserBtn']))
    {
        $roleID = validate($_POST['roleID']);
        $username = validate($_POST['username']);
        $fullname = validate($_POST['fullname']);
        $telephone = validate($_POST['phone']);
        $email = validate($_POST['email']);
        $password = validate($_POST['password']);
        $conpassword = validate($_POST['conpassword']);
        $_id = validate($_POST['id']);
        
        if($roleID != '' && $username != '' && $fullname != '' && $telephone != '' && $email != '' && $password != '' && $conpassword != ''){
            
            
            // Check if phone number is used by another user
            $phoneCheck = mysqli_query($con, "SELECT * FROM user WHERE telephone= '$telephone'");
            if ($phoneCheck) {
                if ($phoneCheck) {
                    if (mysqli_num_rows($phoneCheck) > 0) {
                        redirect('user-request.php', 'Phone Number Already Used By Another User.');
                    }else{
                        $encryptphone = encryption($telephone);
                    }
                }
            }

            if (!isValidEmailFormat($email)) {
                redirect('user-request.php', 'Invalid Email Address. Format: <email>@amc.tp.edu.sg');
            }
            
            // Check if email is used by another user
            $emailCheck = mysqli_query($con, "SELECT * FROM user WHERE email='$email'");
            if ($emailCheck) {
                if ($emailCheck) {
                    if (mysqli_num_rows($emailCheck) > 0) {
                        redirect('user-request.php', 'Email Already Used By Another User.');
                    }else{
                        $encryptemail = encryption($email);
                    }
                }
            }
            
                // Check if password match
                if ($password != $conpassword) {
                    redirect('user-request.php', 'Passwords do not match.');
                }else{
                    // Check if the password meets the strength criteria
                    if (!isPasswordStrong($password)) {
                        redirect('admin-create.php', 'Passwords does not meet the requirements: </br>
                        1. The password must be at least 8 characters long.</br>
                        2. The password must contain at least one uppercase letter.</br>
                        3. The password must contain at least one lowercase letter.</br>
                        4. The password must contain at least one numeric digit.</br>
                        5. The password must contain at least one special character among the following: !@#$%^&*(),.?":{}|<>');
                    }else{
                        $bcryptpassword = password_hash($password, PASSWORD_BCRYPT);
                    }
                }

                if (!isAlphabeticFullName($fullname)) {
                    redirect('admin-create.php', 'Please enter alphabetic characters.');
                }
            
            $data = [
                'roleID' => $roleID,
                'userName' => $username,
                'fullName' => $fullname,
                'password' => $bcryptpassword,
                'email' => $encryptemail,
                'telephone' => $encryptphone,
            ];
            
            $result = insert('user', $data);
            if($result){
                
                // User Created Successfully
                jsonResponse(200, 'success', 'User Created Successfully');
                
                // Now, update the status of request_user to "Approved"
                $updateQuery = "UPDATE request_user SET status = 'Approved', updated_at = current_timestamp, userName = '$username' WHERE _id = '$_id'";
                $updateResult = mysqli_query($con, $updateQuery);
                
            }else{
                jsonResponse(500, 'error', 'Something Went Wrong');
            }
        }else{
            jsonResponse(422, 'warning', 'Please Fill Required Fields');
        }
    }
    
    if (isset($_POST['declineUser'])) {
        $requestId = validate($_POST['requestId']);
        $requestData = getById('request_user', $requestId);
        
        if (!$requestData) {
            redirect('user-request.php', 'No Such Request Found');
        }
        
        // Update the status in the database
        $updateQuery = "UPDATE request_user SET status = 'Declined', updated_at = current_timestamp WHERE _id = '$requestId'";
        $result = mysqli_query($con, $updateQuery);
        
        if ($result) {
            redirect('user-request.php', 'Request Declined Successfully!');
        } else {
            redirect('user-request.php', 'Something Went Wrong!');
        }
    }
    
    if (isset($_POST['updateProfile'])) {
        $userID = validate($_SESSION['loggedInUser']['user_id']);
        $userData = getById('user', $userID);
        
        if (!$userData) {
            redirect('profile.php', 'No Such User Found');
        }
        
        $fullname = validate($_POST['fullname']);
        $telephone = validate($_POST['telephone']);
        $email = validate($_POST['email']);
        $dob = validate($_POST['dob']);
        
        // Check if a new image is being uploaded
        if($_FILES['image']['size']>0)
        {
            $path = "../assets/profile";
            $image_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            
            $filename = time().'.'.$image_ext;
            
            move_uploaded_file($_FILES['image']['tmp_name'], $path."/".$filename);
            
            $finalImage = "../assets/profile/".$filename;
            
            // Delete the existing image if it's not the default image
            if ($userData['data']['avatar'] !== "../assets/profile/default.png") {
                $deleteImage = $userData['data']['avatar'];
                if (file_exists($deleteImage)) {
                    unlink($deleteImage);
                }
            }
        } else {
            // No new image uploaded, keep the existing image
            $finalImage = $_SESSION['loggedInUser']['image'];
        }
        
        if (!is_numeric($telephone)) {
            redirect('profile-edit.php', 'Invalid Phone Number. Please enter a numeric value.');
        }
        
        // Check if phone number is used by another customer
        $phoneCheck = mysqli_query($con, "SELECT * FROM customer WHERE telephone='$telephone' AND _id !='$userID'");
        if ($phoneCheck) {
            if ($phoneCheck) {
                if (mysqli_num_rows($phoneCheck) > 0) {
                    redirect('profile-edit.php', 'Phone Number Already Used By Another User.');
                }else{
                    $encryptphone = encryption($telephone);
                }
            }
        }

        if (!isValidEmailFormat($email)) {
            redirect('profile-edit.php', 'Invalid Email Address. Format: <email>@amc.tp.edu.sg');
        }
        
        // Check if email is used by another user
        $emailCheck = mysqli_query($con, "SELECT * FROM user WHERE email='$email' AND _id !='$userID'");
        if ($emailCheck) {
            if ($emailCheck) {
                if (mysqli_num_rows($emailCheck) > 0) {
                    redirect('profile-edit.php', 'Email Already Used By Another User.');
                }else{
                    $encryptemail = encryption($email);
                }
            }
        }

        if (!isAlphabeticFullName($fullname)) {
            redirect('profile-edit.php', 'Please enter alphabetic characters.');
        }
        
        $data = [
            'fullname' => $fullname,
            'telephone' => $encryptphone,
            'email' => $encryptemail,
            'dob' => $dob,
            'avatar' => $finalImage, // Update the 'image' field in the database
        ];
        
        $result = updateData('user', $userID, $data);
        
        if ($result) {
            // Update session data with the new user details
            $_SESSION['loggedInUser']['fullname'] = $fullname;
            $_SESSION['loggedInUser']['phone'] = $encryptphone;
            $_SESSION['loggedInUser']['email'] = $encryptemail;
            $_SESSION['loggedInUser']['dob'] = $dob;
            $_SESSION['loggedInUser']['image'] = $finalImage; // Update the 'image' field in the session
            
            redirect('profile-edit.php', 'Profile Updated Successfully!');
        } else {
            redirect('profile-edit.php', 'Something Went Wrong!');
        }
    }

    // Check if the request is for checking existing phone
if (isset($_POST['checkExistingTelephone'])) {
    $phone = validate($_POST['phone']);
    $phoneExists = checkExistingTelephone($phone);
    if ($phoneExists) {
        echo json_encode(['status' => 409, 'message' => 'Phone already exists', 'status_type' => 'warning']);
    } else {
        echo json_encode(['status' => 200, 'message' => 'Phone does not exist']);
    }
    exit(); // Stop further execution
}

// Check if the request is for checking existing email
if (isset($_POST['checkExistingMail'])) {
    $email = validate($_POST['email']);
    $emailExists = checkExistingMail($email);
    if ($emailExists) {
        echo json_encode(['status' => 409, 'message' => 'Email already exists', 'status_type' => 'warning']);
    } else {
        echo json_encode(['status' => 200, 'message' => 'Email does not exist']);
    }
    exit(); // Stop further execution
}

// Check if the request is for checking existing email
if (isset($_POST['checkExistingUsername'])) {
    $username = validate($_POST['username']);
    $usernameExists = checkExistingUsername($username);
    if ($usernameExists) {
        echo json_encode(['status' => 409, 'message' => 'Username already exists', 'status_type' => 'warning']);
    } else {
        echo json_encode(['status' => 200, 'message' => 'Username does not exist']);
    }
    exit(); // Stop further execution
}
   
?>

