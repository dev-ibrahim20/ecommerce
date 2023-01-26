<?php
	ob_start();
	session_start();
	$pageTitle = 'Create New Ad';
	include 'init.php';
	if (isset($_SESSION['user'])) {


	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$formErrors = array();

		$name 		= filter_var($_POST['name'], FILTER_SANITIZE_STRING);
		$desc 		= filter_var($_POST['description'], FILTER_SANITIZE_STRING);
		$price 		= filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);
		$country 	= filter_var($_POST['country'], FILTER_SANITIZE_STRING);
		$status 	= filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT);
		$category 	= filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);


		if (strlen($name) < 4) {
			$formErrors[] = 'Item Title Must Be At Leatst 4 Characters';
		}

		if (strlen($desc) < 10) {
			$formErrors[] = 'Item Description Must Be At Leatst 10 Characters';
		}

		if (strlen($country) < 2) {
			$formErrors[] = 'Item country Must Be At Leatst 2 Characters';
		}

		if (empty($price)) {
			$formErrors[] = 'Item Price Must Be Not Empty';
		}

		if (empty($status)) {
			$formErrors[] = 'Item Status Must Be Not Empty';
		}

		if (empty($category)) {
			$formErrors[] = 'Item Category Must Be Not Empty';
		}

		// Check If Theher's No Error Proceed The Update Operation

		if (empty($formErrors)) {

    		// Insert User Info In Database

			$stmt = $con->prepare("INSERT INTO 
										items(Name, Description, Price, Country_Made, Status, Add_Date, Member_ID, Cat_ID)
									    VALUES(:zname, :zdesc, :zprice, :zcountry, :zstatus, now(), :zmember, :zcat)");
			$stmt->execute(array(

				'zname' 	=> $name,
				'zdesc' 	=> $desc,
				'zprice' 	=> $price,
				'zcountry' 	=> $country,
				'zstatus' 	=> $status,
				'zmember'	=> $_SESSION['uid'],
				'zcat'		=> $category
 			)); 

    		// Echo Success Message 
			if($stmt){
 				$successMsg = 'Item Has Been Added';
 			}
 		}
	}

?>

<h1 class="text-center">Create New Ad</h1>

<div class="create-ad block">
	<div class="container">
		<div class="panel panel-primary">
			<div class="panel-heading">Create New Ad</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-8">
			    		<form class="form-horizontal main-form" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
			    			<!-- Start Name Field -->
			    			<div class="form-group form-group-lg">
			    				<label class="col-sm-3 control-label">Name</label>
			    				<div class="col-sm-10 col-md-9">
			    					<input pattern=".{4,}" title="This Field Required At Least 4 Charaters" type="text" name="name" class="form-control live-name" required="required" placeholder="Name Of The Item" required>
			    				</div>
			    			</div>
			    			<!-- End Name Field -->
			    			<!-- Start Description Field -->
			    			<div class="form-group form-group-lg">
			    				<label class="col-sm-3 control-label">Description</label>
			    				<div class="col-sm-10 col-md-9">
			    					<input pattern=".{10,}" title="This Field Required At Least 10 Charaters" type="text" name="description" class="form-control live-desc" required="required" placeholder="Description Of The Item" required>
			    				</div>
			    			</div>
			    			<!-- End Description Field -->
			    			<!-- Start Price Field -->
			    			<div class="form-group form-group-lg">
			    				<label class="col-sm-3 control-label">Price</label>
			    				<div class="col-sm-10 col-md-9">
			    					<input type="text" name="price" class="form-control live-price" required="required" placeholder="Price Of The Item" required>
			    				</div>
			    			</div>
			    			<!-- End Price Field -->
			    			<!-- Start Country Field -->
			    			<div class="form-group form-group-lg">
			    				<label class="col-sm-3 control-label">Country</label>
			    				<div class="col-sm-10 col-md-9">
			    					<input type="text" name="country" class="form-control" required="required" placeholder="Country Of Made Item" required>
			    				</div>
			    			</div>
			    			<!-- End Country Field -->
			    			<!-- Start Status Field -->
			    			<div class="form-group form-group-lg">
			    				<label class="col-sm-3 control-label">Status</label>
			    				<div class="col-sm-10 col-md-9">
			    					<select name="status" required>
			    						<option value="0">...</option>
			    						<option value="1">New</option>
			    						<option value="2">Like New</option>
			    						<option value="3">Used</option>
			    						<option value="4">Very Old</option>
			    					</select>
			    				</div>
			    			</div>
			    			<!-- End Status Field -->
			    			<!-- Start Category Field -->
			    			<div class="form-group form-group-lg">
			    				<label class="col-sm-3 control-label">Category</label>
			    				<div class="col-sm-10 col-md-9">
			    					<select name="category" required>
			    						<option value="0">...</option>
			    						<?php
			    							$categories = getAllFrom('*' ,'categories','','', 'ID');
			    							foreach ($categories as $cotegory) {
			    								echo "<option value='" . $cotegory['ID'] . "'>" . $cotegory['Name'] . "</option>";
			    							}
			    						 ?>
			    					</select>
			    				</div>
			    			</div>
			    			<!-- End Category Field -->
							<!-- Start Submit Field -->
			    			<div class="form-group form-group-lg">
			    				<div class="col-sm-offset-3 col-sm-9">
			    					<input type="submit" value="Add Item" class="btn btn-primary btn-lg">
			    				</div>
			    			</div>
			    			<!-- End Submit Field -->
			    		</form>
					</div>
					<div class="col-sm-4">
						<div class="thumbnail item-box live-preview">
				 			<span class="price-tag">$0</span>
				 			<img class="img-responsive" src="img.ico" alt="" />
				 			<div class="caption">
				 				<h3>Title</h3>
				 				<p>Description</p>
				 			</div>
				 		</div>
					</div>
				</div>
				<!-- Start Looping Through Errors -->
				<?php
					if (! empty($formErrors)) {
					 	foreach ($formErrors as $error) {
					 		echo '<div class="alert alert-danger">' . $error . '</div>';
					 	}
					}
					if (isset($successMsg)) {
						echo '<div class="alert alert-success">' . $successMsg . '</div>';
					} 
				?>
				<!-- End Looping Through Errors -->				
			</div>
		</div>
	</div>
</div>
<?php

	}else {
		header('Location: login.php');
		exit();
	}
	include $tpl . 'footer.php';
	ob_end_flush();
?>