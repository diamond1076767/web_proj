<?php require '../config/function.php';
allowedRole([1,2]);

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