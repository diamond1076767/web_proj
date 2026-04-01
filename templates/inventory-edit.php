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
    					<label for="productName">Product Name *</label>
    					<input type="text" id="productName" name="name" required value="<?= $product['data']['title'];?>" class="form-control" />
    				</div>
				
					<div class="col-md-12 mb-3">
					<label for="categoryId">Select Category</label>
					<select id="categoryId" name="category_id" class="form-select">
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
					<label for="colourId">Select Colour</label>
					<select id="colourId" name="colour_id" class="form-select">
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
					<label for="description">Description</label>
					<br/>
					<input type="text" id="description" name="description" value="<?= $product['data']['description'];?>" class="form-control">
					</div>
    				<div class="col-md-4 mb-3">
					<label for="price">Price *</label>
					<br/>
					<input type="text" id="price" name="price" required value="<?= $product['data']['cost'];?>" class="form-control">
					</div>
					<div class="col-md-4 mb-3">
					<label for="quantity">Quantity *</label>
					<br/>
					<input type="text" id="quantity" name="quantity" required value="<?= $product['data']['quantity'];?>" class="form-control">
					</div>
					<div class="col-md-4 mb-3">
					<label for="productImage">Image *</label>
					<input type="file" id="productImage" name="image" class="form-control"/>
						<img src="<?= $product['data']['image'] != '' ? '../' . $product['data']['image'] : '../assets/images/no-img.png'; ?>"
								style="width:40px;height:40px;" alt="Img" />
					</div>
					<div class="col-md-4 mb-3">
					<label for="statusHidden">Status: Hidden</label>
					<br/>
					<input type="checkbox" id="statusHidden" name="status" <?= $product['data']['status'] == true ? 'checked':'';?> style="width:30px;height:30px";>
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
