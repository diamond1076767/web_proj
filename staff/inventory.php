<?php include("includes/header.php");?>

<div class="container-fluid px-4">
	<div class="card mt-4 shadow-sm">
		<div class="card-header">
			<h4 class="mb-0">Inventories
				
			</h4>
		</div>
		<div class="card-body">
			<?php alertMessage();?>
			
			<?php 
            $inventory = getAllVisible('inventory');
            
            if(!$inventory){
                echo '<h4>Something Went Wrong!</h4>';
                return false;
            }
            
            if(mysqli_num_rows($inventory)>0)
    		{
			?>	
			<div class="table-responsive">
				<table class="table table-striped table-bordered">
					<thead>
						<tr>
    						<th>ID</th>
    						<th>Image</th>
    						<th>Name</th>
    						<th>Category</th>
    						<th>Colour</th>
    						<th>Quantity</th>
    						<th>Price</th>
    						<th>Description</th>
    					</tr>
					</thead>
					<tbody>
					<?php foreach($inventory as $item) : ?>
    			    <tr>
						<td><?= $item['_id']?></td>
						<td>
							<img src="<?= $item['image'] != '' ? '../' . $item['image'] : '../assets/images/no-img.png'; ?>"
								style="width:50px;height:50px;"
								alt="Img" />
						</td>
						<td><?= $item['title']?></td>
                        <td>
                            <?php
                            $colourID = $item['colourID'];
                   
                            $query = "SELECT colourName FROM colour WHERE _id = $colourID";
                            $result = mysqli_query($con, $query);
                        
                            if ($result) {
                                $colorRow = mysqli_fetch_assoc($result);
                                $colorName = $colorRow['colourName'];
                        
                                echo $colorName;
                            } else {
                                echo "Error retrieving color information";
                            }
                            ?>
                        </td>
                        <td><?php
                            $categoryID = $item['categoryID'];
                   
                            $query = "SELECT categoryName FROM categories WHERE _id = $categoryID";
                            $result = mysqli_query($con, $query);
                        
                            if ($result) {
                                $catRow = mysqli_fetch_assoc($result);
                                $catName = $catRow['categoryName'];
                        
                                echo $catName;
                            } else {
                                echo "Error retrieving category information";
                            }
                            ?>
                        </td>
						<td><?= $item['quantity']?></td>
						<td><?= $item['cost']?></td>
						<td><?= !empty($item['description']) ? $item['description'] : '-'; ?></td>
				
					</tr>
					<?php endforeach;?>
					</tbody>
				</table>
			</div>
			
			<?php 
            }
            else
    	    {
    	       ?>
    				<h4 class="mb-0">No Record found</h4>
    			<?php
            }
			?>
		</div>
	</div>
</div>

<?php include("includes/footer.php");?>
?>