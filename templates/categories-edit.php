<?php include("includes/header.php");
allowedRole([1,2]);
?>

<div class="container-fluid px-4">
	<div class="card mt-4 shadow-sm">
		<div class="card-header">
			<h4 class="mb-0">Edit Category
				<a href="categories.php" class="btn btn-primary float-end">Back</a>
			</h4>
		</div>
		<div class="card-body">
			<?php alertMessage();?>
			<form action="admin-code.php" method="POST">
		
				<?php 
				if (isset($_POST['cateId'])) {
					$_SESSION['cateID'] = $_POST['cateId'];
					}
	
					if (isset($_SESSION['cateID'])) {
						$cateID = $_SESSION['cateID'];
						$category = getById('categories', $cateID);
					}
					
					if($category['status'] == 200)
					{
					?>    
	
				<input type="hidden" name="categoryId" value="<?= $category['data']['_id'];?>">
	
				<div class="row">
					<div class="col-md-12 mb-3">
    					<label for="categoryName">Category Name *</label>
    					<input type="text" id="categoryName" name="name" value='<?=$category['data']['categoryName']?>' required class="form-control" />
    				</div>
					<div class="col-md-6">
					<label for="status">Status: Hidden</label>
					<br/>
					<input type="checkbox" id="status" name="status" <?=$category['data']['status'] == true ? 'checked':''?> style="width:30px;height:30px";>
					</div>
    				<div class="col-md-6 mb-3 text-end">
    				<br/>
    					<button type="submit" name="updateCategory" class="btn btn-primary">Save</button>	
    				</div>
				</div>
				<?php 
				}
				else{
				    echo '<h5>'.$category['message'].'</h5>';
				}
				?>
			</form>
		</div>
	</div>
</div>

<?php include("includes/footer.php");?>
