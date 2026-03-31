<?php
// createUser, updateUser, resetBtn, saveCategory, updateCategory ,saveProduct, updateProduct, saveCustomer, updateCustomer, saveUserBtn,
// declineUser , updateProfile, checkExistingTelephone, checkExistingmMail, checkExistingUsername

// saveUserRequest

include_once ("../config/function.php"); 
allowedRole([1, 2, 3]);

    if (isset($_POST['createUser'])) {
        // Collect and sanitize input
        $inputs = [
            'roleID' => validate($_POST['role_id'] ?? ''),
            'username' => validate($_POST['username'] ?? ''),
            'fullname' => validate($_POST['fullname'] ?? ''),
            'email' => validate($_POST['email'] ?? ''),
            'password' => validate($_POST['password'] ?? ''),
            'conpassword' => validate($_POST['confirmpassword'] ?? ''),
            'phone' => validate($_POST['phone'] ?? ''),
            'lock_acc' => isset($_POST['lock_acc']) ? 1 : 0
        ];

        // Check required fields
        foreach (['roleID','username','fullname','email','password','conpassword','phone'] as $field) {
            if (empty($inputs[$field])) {
                redirect('admin-create.php', 'Please fill required fields');
            }
        }

        // Password match and strength
        if ($inputs['password'] !== $inputs['conpassword']) {
            redirect('admin-create.php', 'Passwords do not match.');
        }
        if (!isPasswordStrong($inputs['password'])) {
            redirect('admin-create.php', 'Password does not meet the requirements:
            1. The password must be at least 8 characters long.</br>
                2. The password must contain at least one uppercase letter.</br>
                3. The password must contain at least one lowercase letter.</br>
                4. The password must contain at least one numeric digit.</br>
                5. The password must contain at least one special character among the following: !@#$%^&*(),.?":{}|<>');
        }
        $bcryptpassword = password_hash($inputs['password'], PASSWORD_BCRYPT);

        // Unique checks
        if (checkExistingUsername($inputs['username'])) {
            redirect('admin-create.php', 'Username Already Used By Another User.');
        }
        if (checkExistingMail($inputs['email'])) {
            redirect('admin-create.php', 'Email Already Used By Another User.');
        }
        if (checkExistingTelephone($inputs['phone'])) {
            redirect('admin-create.php', 'Phone Number Already Used By Another User.');
        }

        // Encrypt sensitive fields
        $encryptEmail = encryption($inputs['email']);
        $encryptPhone = encryption($inputs['phone']);
        $encryptFname = encryption($inputs['fullname']);

        // Validate formats
        if (!isValidEmailFormat($inputs['email'])) {
            redirect('admin-create.php', 'Invalid Email Address. Format: <email>@inf1005p17.duckdns.org');
        }
        if (!is_numeric($inputs['phone'])) {
            redirect('admin-create.php', 'Invalid Phone Number. Please enter a numeric value.');
        }
        if (!isAlphabeticFullName($inputs['fullname'])) {
            redirect('admin-create.php', 'Full Name must contain alphabetic characters only.');
        }

        // Prepare data and insert
        $data = [
            'roleID' => $inputs['roleID'],
            'userName' => $inputs['username'],
            'fullName' => $encryptFname,
            'email' => $encryptEmail,
            'password' => $bcryptpassword,
            'telephone' => $encryptPhone,
            'lock_acc' => $inputs['lock_acc']
        ];

        $result = insert('user', $data);
        redirect($result ? 'admin.php' : 'admin-create.php', $result ? 'User Created Successfully!' : 'Something Went Wrong!');
    }

    if (isset($_POST['updateUser'])) {
    $userId = validate($_POST['userId']);
    $userData = getById('user', $userId);

    if ($userData['status'] != 200) {
        redirect('admin-edit.php', 'Please fill required fields.');
    }

    // Collect and sanitize input
    $inputs = [
        'username' => validate($_POST['username'] ?? ''),
        'fullname' => validate($_POST['fullname'] ?? ''),
        'email' => validate($_POST['email'] ?? ''),
        'password' => validate($_POST['password'] ?? ''),
        'conpassword' => validate($_POST['confirmpassword'] ?? ''),
        'phone' => validate($_POST['phone'] ?? ''),
        'dob' => $_POST['dob'] ?? null, // Keep as null if not passed
        'lock_acc' => isset($_POST['lock_acc']) ? 1 : 0
    ];

    // Required fields check
    foreach (['username','fullname','email','phone'] as $field) {
        if (empty($inputs[$field])) {
            redirect('admin-edit.php', 'Please fill required fields.');
        }
    }

    // Password handling
    if (!empty($inputs['password']) && !empty($inputs['conpassword'])) {
        if ($inputs['password'] !== $inputs['conpassword']) {
            redirect('admin-edit.php', 'Passwords do not match.');
        }
        if (!isPasswordStrong($inputs['password'])) {
            redirect('admin-edit.php', 'Password does not meet strength requirements.');
        }
        $hashedPassword = password_hash($inputs['password'], PASSWORD_BCRYPT);
    } else {
        $hashedPassword = $userData['data']['password'];
    }

    // Validate email and phone formats
    if (!isValidEmailFormat($inputs['email'])) {
        redirect('admin-edit.php', 'Invalid Email Address. Format: <email>@inf1005p17.duckdns.org');
    }
    if (!is_numeric($inputs['phone'])) {
        redirect('admin-edit.php', 'Invalid Phone Number. Please enter a numeric value.');
    }
    if (!isAlphabeticFullName($inputs['fullname'])) {
        redirect('admin-edit.php', 'Full Name must contain alphabetic characters only.');
    }

    // Unique field checks
    if (checkExistingUsername($inputs['username'], $userId)) {
        redirect('admin-edit.php', 'Username Already Used By Another User.');
    }
    if (checkExistingMail($inputs['email'], $userId)) {
        redirect('admin-edit.php', 'Email Already Used By Another User.');
    }
    if (checkExistingTelephone($inputs['phone'], $userId)) {
        redirect('admin-edit.php', 'Phone Number Already Used By Another User.');
    }

    // Encrypt email, phone, fullname
    $encryptEmail = encryption($inputs['email']);
    $encryptPhone = encryption($inputs['phone']);
    $encryptFname = encryption($inputs['fullname']);

    // Reset failed attempts if account unlocked
    if ($inputs['lock_acc'] == 0) {
        mysqli_query($con, "UPDATE user SET failed_attempts = 0 WHERE _id = $userId");
    }

   // Prepare data array
   $data = [
    'userName' => $inputs['username'],
    'fullName' => $encryptFname,
    'email' => $encryptEmail,
    'password' => $hashedPassword,
    'telephone' => $encryptPhone,
    'lock_acc' => $inputs['lock_acc']
    ];

    // Only include dob if it's not null
    if (!is_null($inputs['dob'])) {
        $data['dob'] = $inputs['dob'];
    }

    // Update user
    $result = updateData('user', $userId, $data);
    redirect('index.php', $result ? 'User Updated Successfully!' : 'Something Went Wrong!');
    }

if (isset($_POST['resetBtn'])) {

    $pass = validate($_POST['pass']);
    $conpass = validate($_POST['conpass']);
    $userID = $_SESSION['user_id'];

    if ($pass == '' || $conpass == '') {
        redirect('change-password.php', 'Please fill required fields');
    }

    if ($pass !== $conpass) {
        redirect('change-password.php', 'Passwords do not match.');
    }

    if (!isPasswordStrong($pass)) {
        redirect('change-password.php', 'Password does not meet the requirements:
        <br>1. At least 8 characters
        <br>2. One uppercase letter
        <br>3. One lowercase letter
        <br>4. One number
        <br>5. One special character');
    }

    // Hash password
    $bcryptpassword = password_hash($pass, PASSWORD_BCRYPT);

    // Update password
    $data = [
        'password' => $bcryptpassword
    ];

    $result = updateData('user', $userID, $data);

    if (!$result) {
        redirect('change-password.php', 'Something Went Wrong!');
    }

    // Update login time and first_log
    $updateQuery = "UPDATE user SET logTime = CURRENT_TIMESTAMP, first_log = 0 WHERE _id = ?";
    $stmt = mysqli_prepare($con, $updateQuery);
    mysqli_stmt_bind_param($stmt, "i", $userID);
    mysqli_stmt_execute($stmt);

    // Get updated user data
    $query = $con->prepare("SELECT * FROM user WHERE _id = ?");
    $query->bind_param("i", $userID);
    $query->execute();
    $result = $query->get_result();
    $row = $result->fetch_assoc();

    // Update session
    $_SESSION['loggedIn'] = true;
    $_SESSION['loggedInUser'] = [
        'user_id' => $row['_id'],
        'username' => $row['userName'],
        'fullname' => $row['fullName'],
        'email' => $row['email'],
        'phone' => $row['telephone'],
        'dob' => $row['dob'],
        'roleID' => $row['roleID'],
        'image' => $row['avatar'],
    ];

    // Redirect to shared template index
    redirect('index.php', 'Password Changed Successfully');
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
    if($productData['status'] != 200){
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
    
    if (isset($_POST['saveUserRequest'])) {
    $username = validate($_POST['username']);
    $fullname = validate($_POST['fullname']);
    $email    = validate($_POST['email']);
    $phone    = validate($_POST['telephone']);

    if ($username == '' || $fullname == '' || $email == '' || $phone == '') {
        redirect('user-request-create.php', 'Please fill required fields');
    }

    // Check username uniqueness
    if (checkExistingUsername($username)) {
        redirect('user-request-create.php', 'Username Already Used By Another User.');
    }

    // Check phone
    if (!is_numeric($phone)) {
        redirect('user-request-create.php', 'Invalid Phone Number.');
    }
    if (checkExistingTelephone($phone)) {
        redirect('user-request-create.php', 'Phone Number Already Used By Another User.');
    }
    $encryptphone = encryption($phone);

    // Check email
    if (!isValidEmailFormat($email)) {
        redirect('user-request-create.php', 'Invalid Email Address.');
    }
    if (checkExistingMail($email)) {
        redirect('user-request-create.php', 'Email Already Used By Another User.');
    }
    $encryptemail = encryption($email);

    if (!isAlphabeticFullName($fullname)) {
        redirect('user-request-create.php', 'Full Name must be alphabetic.');
    }

    $requesterName = $_SESSION['loggedInUser']['username'] ?? 'Unknown';

    $data = [
        'roleID'        => 3,
        'requesterName' => $requesterName, 
        'userName'      => $username,
        'fullName'      => $fullname,
        'email'         => $encryptemail,
        'telephone'     => $encryptphone,
        'status'        => 'Pending',
        'created_at'    => date('Y-m-d H:i:s'),
        'updated_at'    => date('Y-m-d H:i:s'),
    ];

    $result = insert('request_user', $data);

    if ($result) {
        redirect('user-request.php', 'User Request Created Successfully!');
    } else {
        redirect('user-request-create.php', 'Something Went Wrong!');
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
        if (checkExistingPhone($phone)) {
            redirect('customer-create.php', 'Phone Number Already Used By Another Customer.');
        }
        $encryptphone = encryption($phone);
        
        // Check if email is a valid format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            redirect('customer-create.php', 'Invalid Email Address.');
        }
        
        // Check if email is used by another user
        if (checkExistingEmail($email)) {
            redirect('customer-create.php', 'Email Already Used By Another Customer.');
        }
        $encryptemail = encryption($email);

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
        if($customerData['status'] != 200){
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
        if (checkExistingPhone($phone, $customerId)) {
            redirect('customer-edit.php', 'Phone Number Already Used By Another Customer.');
        }
        $encryptphone = encryption($phone);

        // Check if email is a valid format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            redirect('customer-edit.php', 'Invalid Email Address.');
        }
        
        // Check if email is used by another user
        if (checkExistingEmail($email, $customerId)) {
            redirect('customer-edit.php', 'Email Already Used By Another Customer.');
        }
        $encryptemail = encryption($email);
        
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
            if (checkExistingTelephone($telephone)) {
                redirect('user-request.php', 'Phone Number Already Used By Another User.');
            }
            $encryptphone = encryption($telephone);

            if (!isValidEmailFormat($email)) {
                redirect('user-request.php', 'Invalid Email Address. Format: <email>@inf1005p17.duckdns.org');
            }
            
            // Check if email is used by another user
            if (checkExistingMail($email)) {
                redirect('user-request.php', 'Email Already Used By Another User.');
            }
            $encryptemail = encryption($email);
            
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
                'fullName' => encryption($fullname),
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
        
        if ($requestData['status'] != 200) {
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

    if ($userData['status'] != 200) {
        redirect('profile.php', 'No Such User Found');
    }

    // Validate inputs
    $fullname = validate($_POST['fullname']);
    $telephone = validate($_POST['telephone']);
    $email = validate($_POST['email']);
    $dob = $_POST['dob'] ?? null;
    $dob = !empty($dob) ? $dob : NULL; // Properly store NULL if empty

    // Encrypt fullname
    if (!isAlphabeticFullName($fullname)) {
        redirect('profile-edit.php', 'Please enter alphabetic characters.');
    } else {
        $encryptFullname = encryption($fullname);
    }

    // Image upload handling
    if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
        $path = "../assets/profile";
        $image_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $filename = time() . '.' . $image_ext;
        move_uploaded_file($_FILES['image']['tmp_name'], $path . "/" . $filename);
        $finalImage = $path . "/" . $filename;

        // Delete old image if not default
        if ($userData['data']['avatar'] !== "../assets/profile/default.png") {
            $deleteImage = $userData['data']['avatar'];
            if (file_exists($deleteImage)) {
                unlink($deleteImage);
            }
        }
    } else {
        $finalImage = $_SESSION['loggedInUser']['image'];
    }

    // Telephone validation & encryption
    if (!is_numeric($telephone)) {
        redirect('profile-edit.php', 'Invalid Phone Number. Please enter a numeric value.');
    }
    if (checkExistingTelephone($telephone, $userID)) {
        redirect('profile-edit.php', 'Phone Number Already Used By Another User.');
    }
    $encryptphone = encryption($telephone);

    // Email validation & encryption
    if (!isValidEmailFormat($email)) {
        redirect('profile-edit.php', 'Invalid Email Address. Format: <email>@inf1005p17.duckdns.org');
    }
    if (checkExistingMail($email, $userID)) {
        redirect('profile-edit.php', 'Email Already Used By Another User.');
    }
    $encryptemail = encryption($email);

    // Prepare data array for update
    $data = [
        'fullName' => $encryptFullname,
        'telephone' => $encryptphone,
        'email' => $encryptemail,
        'dob' => $dob,       // NULL if empty
        'avatar' => $finalImage,
    ];

    // Update user in database using fixed updateData function
    $result = updateData('user', $userID, $data);

    if ($result) {
        // Update session with latest info
        $_SESSION['loggedInUser']['fullname'] = $encryptFullname;
        $_SESSION['loggedInUser']['phone'] = $encryptphone;
        $_SESSION['loggedInUser']['email'] = $encryptemail;
        $_SESSION['loggedInUser']['dob'] = $dob;
        $_SESSION['loggedInUser']['image'] = $finalImage;

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

