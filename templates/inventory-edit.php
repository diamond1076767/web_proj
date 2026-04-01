<?php include("includes/header.php");
allowedRole([1,2]);
?>

<div class="container-fluid px-4">
	<div class="card mt-4 shadow-sm">
		<div class="card-header">
			<h4 class="mb-0">Edit Product
				<a href="inventory.php" class="btn btn-primary float-end">Back</a>
			</h4>
		</div>
		<div class="card-body">
			<?php alertMessage();?>
			
			<form action="admin-code.php" method="POST" enctype="multipart/form-data">
			
				<?php 
				if (isset($_POST['invenId'])) {
					$_SESSION['invenID'] = $_POST['invenId'];
					}
	
					if (isset($_SESSION['invenID'])) {
						$invenID = $_SESSION['invenID'];
						$product = getById('inventory', $invenID);
					}
				if($product)
				{
				    if($product['status'] == 200)
				    {
				        ?> 
				        <input type="hidden" name="product_id" value="<?= $product['data']['_id'] ?>"/> 
				        
				<div class="row">
					<div class="col-md-12 mb-3">
    					<label for="">Product Name *</label>
    					<input type="text" name="name" required value="<?= $product['data']['title'];?>" class="form-control" />
    				</div>
				
					<div class="col-md-12 mb-3">
						<label>Select Category</label>
						<select name="category_id" class="form-select">
							<option value="">Select Category</option>
							<?php 
							$categories = getAll('categories');
							if ($categories){
							    if(mysqli_num_fields($categories)>0){
							        foreach($categories as $cateItem){
							            ?>
							            	<option 
							            		value="<?=$cateItem['_id'];?>"
							            		<?= $product['data']['categoryID'] == $cateItem['_id'] ? 'selected':'';?>	
							            	>
							            		<?= $cateItem['categoryName']; ?>
							            	</option>
							            <?php
							        }
							    }else{
							        echo '<option value="">No Categories found</option>';
							    }
							}else{
							    echo '<option value="">Something Went Wrong!</option>';
							}
							?>
						</select>
					</div>

    				<div class="col-md-12 mb-3">
						<label>Select Colour</label>
						<select name="colour_id" class="form-select">
							<option value="">Select Colour</option>
							<?php 
							$colour = getAll('colour');
							if ($colour){
							    if(mysqli_num_fields($colour)>0){
							        foreach($colour as $cateItem){
							        ?>
							        	<option 
							           		value="<?=$cateItem['_id'];?>"
							           		<?= $product['data']['colourID'] == $cateItem['_id'] ? 'selected':'';?>	
							         	>
							            	<?= $cateItem['colourName']; ?>
							            </option>
							            <?php
							        }
							    }else{
							        echo '<option value="">No Colours found</option>';
							    }
							}else{
							    echo '<option value="">Something Went Wrong!</option>';
							}
							?>
						</select>
					</div>
    				<div class="col-md-12 mb-3">
						<label>Description</label>
						<br/>
						<input type="text" name="description" value="<?= $product['data']['description'];?>" class="form-control">
					</div>
    				<div class="col-md-4 mb-3">
						<label>Price *</label>
						<br/>
						<input type="text" name="price" required value="<?= $product['data']['cost'];?>" class="form-control">
					</div>
					<div class="col-md-4 mb-3">
						<label>Quantity *</label>
						<br/>
						<input type="text" name="quantity" required value="<?= $product['data']['quantity'];?>" class="form-control">
					</div>
					<div class="col-md-4 mb-3">
						<label>Image *</label>
						<input type="file" name="image" class="form-control"/>
						<img src="<?= $product['data']['image'] != '' ? '../' . $product['data']['image'] : '../assets/images/no-img.png'; ?>"
								style="width:40px;height:40px;" alt="Img" />
					</div>
					<div class="col-md-4 mb-3">
						<label>Status: Hidden</label>
						<br/>
						<input type="checkbox" name="status" <?= $product['data']['status'] == true ? 'checked':'';?> style="width:30px;height:30px";>
					</div>
					<div class="col-md-8 mb-3 text-end">
    					<button type="submit" name="updateProduct" class="btn btn-primary" style="margin-top:15px">Submit</button>	
    				</div>
				</div>
				<?php
				}
				else
				{
				    echo '<h5>Something Went Wrong!</h5>';
				    return false;
				}
				
				}else{
				    echo '<h5>'.$product['status'].'</h5>';
				    return false;
				}
				?>  
			</form>
		</div>
	</div>
</div>

<?php include("includes/footer.php");?>
