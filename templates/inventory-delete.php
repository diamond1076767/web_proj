<?php require '../config/function.php';
    allowedRole([1,2]);

    $invenID = $_POST['invenId'];

    if (is_numeric($invenID)) {
        $invenId = validate($invenID);
        
        // Get request order by ID
        $product = getById('inventory', $invenId);
        
        if($product['status'] == 200)
        {
            $productDeleteRes = delete('inventory', $invenId);
            if($productDeleteRes)
            {
                $deleteImage = "../".$product['data']['image'];
                if(file_exists($deleteImage)){
                    unlink($deleteImage);
                }
                redirect('inventory.php', 'Product Deleted Successfully.');
            }else{
                redirect('inventory.php', 'Something Went Wrong.');
            }
        }else{
            redirect('inventory.php',$product['message']);
        }
//         echo $productId;
    }else{
       redirect('inventory.php', 'Something Went Wrong.');
    }
?>
