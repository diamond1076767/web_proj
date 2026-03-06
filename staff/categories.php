<?php include("includes/header.php");?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Categories</h4>
        </div>
        <div class="card-body">
            <?php alertMessage();?>

            <?php 
            // Retrieve category data using getAll function
            $categories = getAllVisible('categories');
            if(!$categories){
                echo '<h4>Something Went Wrong!</h4>';
                return false;
            }
            
            if(mysqli_num_rows($categories) > 0) {
            ?>  
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <!-- Table header columns -->
                            <th>ID</th>
                            <th>Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($categories as $item) : ?>
                            <tr>
                                <!-- Output category data -->
                                <td><?= validate($item['_id']) ?></td>
                                <td><?= validate($item['categoryName']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <?php 
            } else {
            ?>
                <h4 class="mb-0">No Record found</h4>
            <?php
            }
            ?>
        </div>
    </div>
</div>

<?php include("includes/footer.php");?>
