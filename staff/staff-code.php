<?php
include ("../config/function.php");
    
if (isset($_POST['saveCustomer'])) {
    // Validate input data
    $name = validate($_POST['name']);
    $companyName = validate($_POST['companyName']);
    $email = validate($_POST['email']);
    $phone = validate($_POST['phone']);
    
    // Check if phone number is numeric
    if (!is_numeric($phone)) {
        redirect('customer-create.php', 'Invalid Phone Number. Please enter a numeric value.');
    }
    
    // Check if email is a valid format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        redirect('customer-create.php', 'Invalid Email Address.');
    }
    
    // Encrypt phone and email
    $encryptphone = encryption($phone);
    $encryptemail = encryption($email);
    
    // Check if phone number is used by another customer
    $phoneCheckQuery = mysqli_prepare($con, "SELECT * FROM customer WHERE telephone = ?");
    mysqli_stmt_bind_param($phoneCheckQuery, "s", validateInput($encryptphone));
    mysqli_stmt_execute($phoneCheckQuery);
    $phoneCheckResult = mysqli_stmt_get_result($phoneCheckQuery);
    
    if (mysqli_num_rows($phoneCheckResult) > 0) {
        redirect('customer-create.php', 'Phone Number Already Used By Another Customer.');
    }
    
    // Check if email is used by another customer
    $emailCheckQuery = mysqli_prepare($con, "SELECT * FROM customer WHERE email = ?");
    mysqli_stmt_bind_param($emailCheckQuery, "s", validateInput($encryptemail));
    mysqli_stmt_execute($emailCheckQuery);
    $emailCheckResult = mysqli_stmt_get_result($emailCheckQuery);
    
    if (mysqli_num_rows($emailCheckResult) > 0) {
        redirect('customer-create.php', 'Email Already Used By Another Customer.');
    }

    if (!isAlphabeticFullName($name)) {
        redirect('customer-create.php', 'Please enter alphabetic characters.');
    }
    
    // Insert customer data using prepared statement
    $insertCustomerQuery = mysqli_prepare($con, "INSERT INTO customer (customerName, companyName, email, telephone) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($insertCustomerQuery, "ssss", validateInput($name), validateInput($companyName), validateInput($encryptemail), validateInput($encryptphone));
    $result = mysqli_stmt_execute($insertCustomerQuery);
    
    if ($result) {
        redirect('customer.php', 'Customer Created Successfully!');
    } else {
        redirect('customer-create.php', 'Something Went Wrong!');
    }
}
    
    if (isset($_POST['updateCustomer'])) {
        // Validate input data
        $customerId = validate($_POST['customerId']);
        $customerData = getById('customer', $customerId);
        
        // Check if customer exists
        if (!$customerData) {
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
        $phoneCheckQuery = mysqli_prepare($con, "SELECT * FROM customer WHERE telephone = ? AND _id != ?");
        mysqli_stmt_bind_param($phoneCheckQuery, "si", validateInput(encryption($phone)), validateInput($customerId));
        mysqli_stmt_execute($phoneCheckQuery);
        $phoneCheckResult = mysqli_stmt_get_result($phoneCheckQuery);
        
        if (mysqli_num_rows($phoneCheckResult) > 0) {
            redirect('customer-edit.php', 'Phone Number Already Used By Another Customer.');
        }
        
        // Check if email is a valid format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            redirect('customer-edit.php', 'Invalid Email Address.');
        }
        
        // Check if email is used by another customer
        $emailCheckQuery = mysqli_prepare($con, "SELECT * FROM customer WHERE email = ? AND _id != ?");
        mysqli_stmt_bind_param($emailCheckQuery, "si", validateInput(encryption($email)), validateInput($customerId));
        mysqli_stmt_execute($emailCheckQuery);
        $emailCheckResult = mysqli_stmt_get_result($emailCheckQuery);
        
        if (mysqli_num_rows($emailCheckResult) > 0) {
            redirect('customer-edit.php', 'Email Already Used By Another Customer.');
        }

        if (!isAlphabeticFullName($name)) {
            redirect('customer-edit.php', 'Please enter alphabetic characters.');
        }
        
        // Update customer data using prepared statement
        $updateDataQuery = mysqli_prepare($con, "UPDATE customer SET companyName = ?, customerName = ?, email = ?, telephone = ? WHERE _id = ?");
        mysqli_stmt_bind_param($updateDataQuery, "ssssi", validateInput($companyName), validateInput($name), validateInput(encryption($email)), validateInput(encryption($phone)), validateInput($customerId));
        $result = mysqli_stmt_execute($updateDataQuery);
        
        if ($result) {
            redirect('customer-edit.php', 'Customer Updated Successfully!');
        } else {
            redirect('customer-edit.php', 'Something Went Wrong!');
        }
    }
    
    if (isset($_POST['updateProfile'])) {
        // Get user ID from session
        $userID = validate($_SESSION['loggedInUser']['user_id']);
        
        // Get user data from the database
        $userData = getById('user', $userID);
        
        // Check if user data exists
        if (!$userData) {
            redirect('profile.php', 'No Such User Found');
        }
        
        // Validate input data
        $fullname = validate($_POST['fullname']);
        $telephone = validate($_POST['telephone']);
        $email = validate($_POST['email']);
        $dob = validate($_POST['dob']);
        
        // Check if a new image is being uploaded
        if ($_FILES['image']['size'] > 0) {
            $path = "../assets/profile";
            $image_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $filename = time() . '.' . $image_ext;
            move_uploaded_file($_FILES['image']['tmp_name'], $path . "/" . $filename);
            $finalImage = "../assets/profile/" . $filename;
            
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
        
        // Check if phone number is numeric
        if (!is_numeric($telephone)) {
            redirect('profile-edit.php', 'Invalid Phone Number. Please enter a numeric value.');
        }
        
        // Check if phone number is used by another user
        $phoneCheckQuery = mysqli_prepare($con, "SELECT * FROM user WHERE telephone = ? AND _id != ?");
        mysqli_stmt_bind_param($phoneCheckQuery, "si", validateInput(encryption($telephone)), validateInput($userID));
        mysqli_stmt_execute($phoneCheckQuery);
        $phoneCheckResult = mysqli_stmt_get_result($phoneCheckQuery);
        
        if (mysqli_num_rows($phoneCheckResult) > 0) {
            redirect('profile-edit.php', 'Phone Number Already Used By Another User.');
        }

        if (!isValidEmailFormat($email)) {
            redirect('profile-edit.php', 'Invalid Email Address. Format: <email>@amc.tp.edu.sg');
        }
        
        // Check if email is used by another user
        $emailCheckQuery = mysqli_prepare($con, "SELECT * FROM user WHERE email = ? AND _id != ?");
        mysqli_stmt_bind_param($emailCheckQuery, "si", validateInput(encryption($email)), validateInput($userID));
        mysqli_stmt_execute($emailCheckQuery);
        $emailCheckResult = mysqli_stmt_get_result($emailCheckQuery);
        
        if (mysqli_num_rows($emailCheckResult) > 0) {
            redirect('profile-edit.php', 'Email Already Used By Another User.');
        }
        
        // Update user data using prepared statement
        $updateDataQuery = mysqli_prepare($con, "UPDATE user SET fullname = ?, telephone = ?, email = ?, dob = ?, avatar = ? WHERE _id = ?");
        mysqli_stmt_bind_param($updateDataQuery, "sssssi", validateInput($fullname), validateInput(encryption($telephone)), validateInput(encryption($email)), validateInput($dob), validateInput($finalImage), validateInput($userID));
        $result = mysqli_stmt_execute($updateDataQuery);
        
        if ($result) {
            // Update session data with the new user details
            $_SESSION['loggedInUser']['fullname'] = $fullname;
            $_SESSION['loggedInUser']['phone'] = encryption($telephone);
            $_SESSION['loggedInUser']['email'] = encryption($email);
            $_SESSION['loggedInUser']['dob'] = $dob;
            $_SESSION['loggedInUser']['image'] = $finalImage;
            
            redirect('profile-edit.php', 'Profile Updated Successfully!');
        } else {
            redirect('profile-edit.php', 'Something Went Wrong!');
        }
    }
    
?>

