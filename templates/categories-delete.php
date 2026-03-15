<?php require '../config/function.php';
    
    if (!isset($_SESSION['loggedInUser']['roleID']) || !in_array($_SESSION['loggedInUser']['roleID'], [1,2])) {
        redirect('index.php', 'Access Denied. Admin or Manager only.');
        exit();
    }
    

    $cateID = $_POST['cateId'];

    if (is_numeric($cateID)) {
        $cateId = validate($cateID);
        
        // Get request order by ID
        $category = getById('categories', $cateId);
        
        if($category['status'] == 200)
        {
            $categoryDeleteRes = delete('categories', $cateId);
            if($categoryDeleteRes){
                redirect('categories.php', 'Category Deleted Successfully.');
            }else{
                redirect('categories.php', 'Something Went Wrong.');
            }
        }else{
            redirect('categories.php',$category['message']);
        }
//         echo $categoryId;
    }else{
       redirect('categories.php', 'Something Went Wrong.');
    }
?>