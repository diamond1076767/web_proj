<?php include("includes/header.php");?>

<div class="container-fluid px-4">
	<div class="card mt-4 shadow-sm">
		<div class="card-header">
			<h4 class="mb-0">Add New Product
				<a href="inventory.php" class="btn btn-primary float-end">Back</a>
			</h4>
		</div>
		<div class="card-body">
			<?php alertMessage();?>
			
			<form action="admin-code.php" method="POST" enctype="multipart/form-data">
			
				<div class="row">
					<div class="col-md-12 mb-3">
    					<label for="">Product Name *</label>
    					<input type="text" name="name" required class="form-control" />
    				</div>
				
					<div class="col-md-12 mb-3">
						<label>Select Category</label>
						<select required name="category_id" class="form-select">
							<option value="">Select Category</option>
							<?php 
							
                            $categories = getAll('categories');
							if ($categories){
							    if(mysqli_num_fields($categories)>0){
							        foreach($categories as $cateItem){
							            echo '<option value="'.$cateItem['_id'].'">'.$cateItem['categoryName'].'</option>';
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
						<select required name="colour_id" class="form-select">
							<option value="">Select Colour</option>
							<?php 
							$colour = getAll('colour');
							if ($colour){
							    if(mysqli_num_fields($colour)>0){
							        foreach($colour as $cateItem){
							            echo '<option value="'.$cateItem['_id'].'">'.$cateItem['colourName'].'</option>';
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
						<input type="text" name="description" class="form-control">
					</div>
    				<div class="col-md-4 mb-3">
						<label>Price *</label>
						<br/>
						<input type="text" name="price" required class="form-control">
					</div>
					<div class="col-md-4 mb-3">
						<label>Quantity *</label>
						<br/>
						<input type="text" name="quantity" required class="form-control">
					</div>
					<div class="col-md-4 mb-3">
						<label>Image</label>
						<br/>
						<input type="file" name="image" class="form-control">
					</div>	
					<div class="col-md-4 mb-3">
						<label>Hidden</label>
						<br/>
						<input type="checkbox" name="status" style="width:30px;height:30px";>
					</div>
					
    				<div class="col-md-8 mb-3 text-end">
    					<button type="submit" name="saveProduct" class="btn btn-primary" style="margin-top:15px">Submit</button>	
    				</div>
				</div>
			</form>
		</div>
	</div>
</div>

<?php include("includes/footer.php");?>