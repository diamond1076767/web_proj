<?php include("includes/header.php");
allowedRole([1,2]);
?>

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
						<label for="productName" class="form-label">Product Name *</label>
						<input type="text" id="productName" name="name" required class="form-control" placeholder="Enter product name"/>
				
					<div class="col-md-12 mb-3">
						<label for="category_id">Select Category</label>
						<select id="category_id" required name="category_id" class="form-select" aria-label="Select category">
							<option value="" selected disabled>Choose a category...</option>
							<?php 
                            $categories = getAll('categories');
                            if ($categories && mysqli_num_rows($categories) > 0){
                                foreach($categories as $cateItem){
                                    echo '<option value="'.$cateItem['_id'].'">'.$cateItem['categoryName'].'</option>';
                                }
                            } else {
                                echo '<option value="">No Categories found</option>';
                            }
                            ?>
						</select>
					</div>

    				<div class="col-md-12 mb-3">
						<label for="colour_id">Select Colour</label>
						<select id="colour_id" required name="colour_id" class="form-select" aria-label="Select colour">
							<option value="" selected disabled>Choose a colour...</option>
							<?php 
                            $colour = getAll('colour');
                            if ($colour && mysqli_num_rows($colour) > 0){
                                foreach($colour as $cateItem){
                                    echo '<option value="'.$cateItem['_id'].'">'.$cateItem['colourName'].'</option>';
                                }
                            } else {
                                echo '<option value="">No Colours found</option>';
                            }
                            ?>
						</select>
					</div>
    				<div class="col-md-12 mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea id="description" name="description" class="form-control" rows="3" placeholder="Enter product details"></textarea>
                    </div>
    				<div class="col-md-4 mb-3">
						<label for="price" class="form-label">Price *</label>
						<input id="price" type="number" name="price" step="0.01" required class="form-control" placeholder="0.00">
					</div>
					<div class="col-md-4 mb-3">
						<label for="quantity" class="form-label">Quantity *</label>
						<input id="quantity" type="number" name="quantity" step="1" required class="form-control" placeholder="0">
					</div>
					<div class="col-md-4 mb-3">
                        <label for="image" class="form-label">Product Image</label>
                        <input id="image" type="file" name="image" accept="image/*" class="form-control" >
                    </div>
					<div class="col-md-4 mb-3">
                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input" type="checkbox" name="status" id="statusSwitch" style="width:40px; height:20px;">
                            <label class="form-check-label ms-2" for="statusSwitch">Hidden</label>
                        </div>
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
