<?php
include ("../config/function.php");
    
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
    
    
    if (isset($_POST['saveUserRequest'])) {
        $userID = validate($_SESSION['loggedInUser']['user_id']);
        
        $username = validate($_POST['username']);
        $fullname = validate($_POST['fullname']);
        $email = validate($_POST['email']);
        $phone = validate($_POST['telephone']);
        
        
        if ($username != '' && $fullname != '' && $email != '' && $phone != '' && $userID != '') {
            
            
            // Check if username is used by another user
            $userCheck = mysqli_query($con, "SELECT * FROM user WHERE userName='$username'");
            if ($userCheck) {
                if ($userCheck) {
                    if (mysqli_num_rows($userCheck) > 0) {
                        redirect('user-request-create.php', 'Username Already Used By Another User.');
                    }
                }
            }
            
            // Check if phone number is numeric
            if (!is_numeric($phone)) {
                redirect('user-request-create.php', 'Invalid Phone Number. Please enter a numeric value.');
            }
            
            // Check if phone number is used by another user
            $phoneCheck = mysqli_query($con, "SELECT * FROM user WHERE telephone='$phone'");
            if ($phoneCheck) {
                if ($phoneCheck) {
                    if (mysqli_num_rows($phoneCheck) > 0) {
                        redirect('user-request-create.php', 'Phone Number Already Used By Another User.');
                    }else{
                        $encryptphone = encryption($phone);
                    }
                }
            }

            if (!isValidEmailFormat($email)) {
                redirect('user-request-create.php', 'Invalid Email Address. Format: <email>@amc.tp.edu.sg');
            }
            
            // Check if email is used by another user
            $emailCheck = mysqli_query($con, "SELECT * FROM user WHERE email='$email'");
            if ($emailCheck) {
                if ($emailCheck) {
                    if (mysqli_num_rows($emailCheck) > 0) {
                        redirect('user-request-create.php', 'Email Already Used By Another User.');
                    }else{
                        $encryptemail = encryption($email);
                    }
                }
            }

            if (!isAlphabeticFullName($fullname)) {
                redirect('user-request-create.php', 'Please enter alphabetic characters.');
            }
            
            $data = [
                'userID' => $userID,
                'roleID' => '3',
                'userName' => $username,
                'fullName' => $fullname,
                'email' => $encryptemail,
                'telephone' => $encryptphone,
            ];
            $result = insert('request_user', $data);
            
            if ($result) {
                redirect('user-request.php', 'User Request Created Successfully!');
            } else {
                redirect('user-request-create.php', 'Something Went Wrong!');
            }
        } else {
            redirect('user-request-create.php', 'Please fill required fields');
        }
    }
    
    if (isset($_POST['updateUserRequest'])) {
        $requestId = validate($_POST['requestId']);
        $requestData = getById('request_user', $requestId);
        if(!$requestData){
            redirect('user-request.php', 'No Such Request Found');
        }
        
        $username = validate($_POST['username']);
        $fullname = validate($_POST['fullname']);
        $email = validate($_POST['email']);
        $phone = validate($_POST['telephone']);
        
        
        if ($username != '' && $fullname != '' && $email != '' && $phone != '') {
            
            
            // Check if username is used by another user
            $userCheck = mysqli_query($con, "SELECT * FROM user WHERE userName='$username'");
            if ($userCheck) {
                if ($userCheck) {
                    if (mysqli_num_rows($userCheck) > 0) {
                        redirect('user-request-edit.php', 'Username Already Used By Another User.');
                    }
                }
            }
            
            // Check if phone number is numeric
            if (!is_numeric($phone)) {
                redirect('user-request-edit.php', 'Invalid Phone Number. Please enter a numeric value.');
            }
            
            // Check if phone number is used by another user
            $phoneCheck = mysqli_query($con, "SELECT * FROM user WHERE telephone='$phone'");
            if ($phoneCheck) {
                if ($phoneCheck) {
                    if (mysqli_num_rows($phoneCheck) > 0) {
                        redirect('user-request-edit.php', 'Phone Number Already Used By Another User.');
                    }else{
                        $encryptphone = encryption($phone);
                    }
                }
            }

            if (!isValidEmailFormat($email)) {
                redirect('user-request-edit.php', 'Invalid Email Address. Format: <email>@amc.tp.edu.sg');
            }
            
            // Check if email is used by another user
            $emailCheck = mysqli_query($con, "SELECT * FROM user WHERE email='$email'");
            if ($emailCheck) {
                if ($emailCheck) {
                    if (mysqli_num_rows($emailCheck) > 0) {
                        redirect('user-request-edit.php', 'Email Already Used By Another User.');
                    }else{
                        $encryptemail = encryption($email);
                    }
                }
            }

            if (!isAlphabeticFullName($fullname)) {
                redirect('user-request-edit.php', 'Please enter alphabetic characters.');
            }
            
            $data = [
                'userName' => $username,
                'fullName' => $fullname,
                'email' => $encryptemail,
                'telephone' => $encryptphone,
            ];
            $result = updateData('request_user', $requestId, $data);
            
            if ($result) {
                redirect('user-request-edit.php', 'Request Updated Successfully, Please Inform Your Respective Administrator To Approve This Request.');
            } else {
                redirect('user-request-edit.php', 'Something Went Wrong!');
            }
        } else {
            redirect('user-request-edit.php', 'Please fill required fields');
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
    
?>

