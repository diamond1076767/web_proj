<?php

session_start();
session_regenerate_id(true);

require 'dbcon.php';

// Input field validation
function validate($inputData)
{
    global $con;
    $inputData = $inputData ?? '';
    $validatedData = mysqli_real_escape_string($con, $inputData);
    return trim($validatedData);
}

function validateInput($input) {
    $input = trim((string)$input);
    return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
}

// Redirect from 1 page to another page with the message (status)
function redirect($url, $status)
{
    $_SESSION['status'] = $status;
    header('Location: ' . $url);
    exit(0);
}

function alertMessage()
{
    if(isset($_SESSION['status'])) {
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
            <h6>'. $_SESSION['status'].'</h6>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
        unset($_SESSION['status']);
    }
}

//Insert record using this function
function insert($tableName, $data)
{
    global $con;
    
    $table = validate($tableName);
    
    $columns = array_keys($data);
    $values = array_values($data);
    
    $finalColumn = implode(',',$columns);
    $finalValues = "'".implode("', '",$values)."'";
    
    $query = "INSERT INTO $table ($finalColumn) VALUES ($finalValues)";
    $result = mysqli_query($con, $query);
    return $result;
}



// Update data using this function
function update($tableName, $_id, $data){
    
    global $con;
    
    $table = validate($tableName);
    $_id = validate($_id);
    
    $updateDataString = "";
    
    foreach($data as $column => $value){
        
        $updateDataString .= $column.'='."'$value',";
    }
    
    $finalUpdateData = substr(trim($updateDataString),0,-1);
    
    $query = "UPDATE $table SET $finalUpdateData WHERE _id='$_id'";
    $result = mysqli_query($con, $query);
    return $result;
}

// Update user data using this function
function updateData($tableName, $_id, $data){
    
    global $con;
    
    $table = validate($tableName);
    $_id = validate($_id);
    
    $updateDataString = "";
    
    foreach($data as $column => $value){
        
        $updateDataString .= $column.'='."'$value',";
    }
    
    $finalUpdateData = substr(trim($updateDataString),0,-1);
    
    $query = "UPDATE $table SET $finalUpdateData, updated_at = CURRENT_TIMESTAMP WHERE _id='$_id'";
    $result = mysqli_query($con, $query);
    return $result;
}

function getColourName($colourID, $status = NULL)
{
    global $con;
    
    $colourID = validate($colourID);
    
    // Use prepared statement to prevent SQL injection
    $query = "SELECT colourName FROM colour WHERE _id = ?";
    $stmt = mysqli_prepare($con, $query);
    
    // Bind parameters and execute the statement
    mysqli_stmt_bind_param($stmt, "i", $colourID);
    mysqli_stmt_execute($stmt);
    
    // Get result
    $result = mysqli_stmt_get_result($stmt);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['colourName'];
    } else {
        // Handle the error or return a default value
        return 'Unknown Color';
    }
}


function getAll($tableName,$status = NULL){
    
    global $con;
    
    $table = validate($tableName);
    $status = validate($status);
    
    if($status == 'status')
    {
        $query = "SELECT * FROM $table WHERE status='0";
    }
    else
    {
        $query = "SELECT * FROM $table";
    }
    return mysqli_query($con, $query);
}

function getAllVisible($tableName,$status = NULL){
    
    global $con;
    
    $table = validate($tableName);
    $status = validate($status);
    
    if($status == 'status')
    {
        $query = "SELECT * FROM $table WHERE status=0";
    }
    else
    {
        $query = "SELECT * FROM $table WHERE status=0";
    }
    return mysqli_query($con, $query);
}

function getAllManager($tableName,$status = NULL){
    
    global $con;
    
    $table = validate($tableName);
    $status = validate($status);
    
    if($status == 'status')
    {
        $query = "SELECT * FROM $table WHERE roleID='2' && status='0";
    }
    else 
    {
        $query = "SELECT * FROM $table WHERE roleID='2'";
    }
    return mysqli_query($con, $query);
}

function getAllStaff($tableName,$status = NULL){
    
    global $con;
    
    $table = validate($tableName);
    $status = validate($status);
    
    if($status == 'status')
    {
        $query = "SELECT * FROM $table WHERE roleID='3' && status='0";
    }
    else
    {
        $query = "SELECT * FROM $table WHERE roleID='3'";
    }
    return mysqli_query($con, $query);
}

function getById($tableName, $_id){
    
    global $con;
    
    $table = validate($tableName);
    $_id = validate($_id);
    
    $query = "SELECT * FROM $table WHERE _id='$_id' LIMIT 1";
    $result = mysqli_query($con,$query);
    
    if($result){
        
        if (mysqli_num_rows($result)== 1){
            
            $row = mysqli_fetch_assoc($result);
            $response = [
                'status' => 200,
                'data' => $row,
                'message' => 'Record Found'
            ];
            return $response;
            
        }else{
            $response = [
                'status' => 404,
                'message' => 'No Data Found'
            ];
            return $response;
        }
        
    }else{
        $response = [
            'status' => 500,
            'message' => 'Something Went Wrong'
        ];
        return $response;
    }
}

// Delete data from database using id
function delete($tableName,$_id){
    
    global $con;
    
    $table = validate($tableName);
    $_id = validate($_id);
    
    $query = "DELETE FROM $table WHERE _id='$_id' LIMIT 1";
    $result = mysqli_query($con,$query);
    return $result;
}

function checkParamId($type){
    
    if(isset($_GET[$type])){
        if($_GET[$type] != ''){
            
            return $_GET[$type];
        }else{
            return '<h5>No Id Found<h5/>';
        }
    }else{
        return '<h5>No Id Given<h5/>';
    }
}

function jsonResponse($status,$status_type, $message) {
    
    $response = [
        'status' => $status,
        'status_type' => $status_type,
        'message' => $message
    ];
    echo json_encode($response);
    return;
}

function getCount($tableName) {
    global $con;
    
    $table = validate($tableName);
    
    $query = "SELECT * FROM $table";
    $query_run = mysqli_query($con, $query);
    if($query_run){
        
        $totalCount = mysqli_num_rows($query_run);
        return $totalCount;
        
    }else{
        return 'Something Went Wrong!';
    }
}

// Function to check if the password is strong
function isPasswordStrong($password) {
    // Define password strength criteria
    $minLength = 8;
    $uppercaseRequired = true;
    $lowercaseRequired = true;
    $numberRequired = true;
    $specialCharRequired = true;
    
    // Check minimum length
    if (strlen($password) < $minLength) {
        return false;
    }
    
    // Check uppercase letters
    if ($uppercaseRequired && !preg_match('/[A-Z]/', $password)) {
        return false;
    }
    
    // Check lowercase letters
    if ($lowercaseRequired && !preg_match('/[a-z]/', $password)) {
        return false;
    }
    
    // Check numbers
    if ($numberRequired && !preg_match('/\d/', $password)) {
        return false;
    }
    
    // Check special characters
    if ($specialCharRequired && !preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
        return false;
    }
    
    // Password meets all criteria
    return true;
}

function checkExistingPhone($phone) {
    global $con;
    $phone = encryption($phone);
    $phone = mysqli_real_escape_string($con, $phone);
    $query = "SELECT * FROM customer WHERE telephone = '$phone'";
    $result = mysqli_query($con, $query);
    return mysqli_num_rows($result) > 0;
}

function checkExistingEmail($email) {
    global $con;
    $email = encryption($email);
    $email = mysqli_real_escape_string($con, $email);
    $query = "SELECT * FROM customer WHERE email = '$email'";
    $result = mysqli_query($con, $query);
    return mysqli_num_rows($result) > 0;
}

function checkExistingUsername($username) {
    global $con;

    $username = mysqli_real_escape_string($con, $username);
    $query = "SELECT * FROM user WHERE userName = '$username'";
    $result = mysqli_query($con, $query);
    return mysqli_num_rows($result) > 0;
}

function checkExistingTelephone($phone) {
    global $con;
    $phone = encryption($phone);
    $phone = mysqli_real_escape_string($con, $phone);
    $query = "SELECT * FROM user WHERE telephone = '$phone'";
    $result = mysqli_query($con, $query);
    return mysqli_num_rows($result) > 0;
}

function checkExistingMail($email) {
    global $con;
    $email = encryption($email);
    $email = mysqli_real_escape_string($con, $email);
    $query = "SELECT * FROM user WHERE email = '$email'";
    $result = mysqli_query($con, $query);
    return mysqli_num_rows($result) > 0;
}

function encryption($var){
    $key = "amcsuper secretkey";
    $AES256_CBC = "aes-256-cbc";
    
    $iv = "1234567890123456";
    
    // Encryption
    $crypttext = openssl_encrypt($var, $AES256_CBC, $key, 0, $iv);
    $encryptedtext = base64_encode($iv . $crypttext);
    return $encryptedtext;
}

function decryption($encrypted){
    $key = "amcsuper secretkey";
    $AES256_CBC = "aes-256-cbc";
    
    // Extract the fixed IV size
    $iv_size = openssl_cipher_iv_length($AES256_CBC);
    
    // Decode the base64 encoded string
    $decoded = base64_decode($encrypted);
    
    // Extract the fixed IV
    $iv = substr($decoded, 0, $iv_size);
    
    // Extract the encrypted text
    $crypttext_dec = substr($decoded, $iv_size);
    
    // Decryption
    $decryptedtext = openssl_decrypt($crypttext_dec, $AES256_CBC, $key, 0, $iv);
    
    return $decryptedtext;
}

function checkCustomer($phone) {
    global $con;

    $query = mysqli_prepare($con, "SELECT * FROM customer WHERE telephone = ? LIMIT 1");
    mysqli_stmt_bind_param($query, "s", $phone);
    mysqli_stmt_execute($query);
    return mysqli_stmt_get_result($query);
}

function checkProduct($id) {
    global $con;

    $query = mysqli_prepare($con, "SELECT * FROM inventory WHERE _id=? LIMIT 1");
    mysqli_stmt_bind_param($query, "i", $id);
    mysqli_stmt_execute($query);
    return mysqli_stmt_get_result($query);
}

function logoutSession() {
    unset($_SESSION['loggedIn']);
    unset($_SESSION['loggedInUser']);
    
}


function isAlphabeticFullName($fullName) {
    // Remove spaces and check if all characters are alphabetic
    $cleanedFullName = str_replace(' ', '', $fullName);
    return ctype_alpha($cleanedFullName);
}

function isValidEmailFormat($email) {
    // Check if email is a valid format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }

    // Extract domain part of the email
    $emailParts = explode('@', $email);
    $domain = end($emailParts);

    // Check if the domain is "@sit.singaporetech.edu.sg"
    return (strtolower($domain) === 'sit.singaporetech.edu.sg');
}

?>


