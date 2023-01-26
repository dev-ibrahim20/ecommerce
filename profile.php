<?php
	ob_start();
	session_start();
	$pageTitle = 'Profile';
	include 'init.php';
	if (isset($_SESSION['user'])) {


	$getUser = $con->prepare("SELECT * FROM users WHERE Username = ?");

	$getUser->execute(array($sessionUser));

	$info = $getUser->fetch();

	$userid = $info['UserID'];


?>

<h1 class="text-center">My Profile</h1>

<div class="information block">
	<div class="container">
		<div class="panel panel-primary">
			<div class="panel-heading">My Information</div>
			<div class="panel-body">
				<ul class="list-unstyled">
					<li>
						<i class="fa fa-unlock-alt fa-fw"></i>
						<span>Login Name</span> : <?php echo $info['Username']?>
					</li>
					<li>
						<i class="fa fa-envelope-o fa-fw"></i>
						<span>Email</span> : <?php echo $info['Email']?>
					</li>
					<li>
						<i class="fa fa-user fa-fw"></i>
						<span>Full Name</span> : <?php echo $info['Fullname']?>
					</li>
					<li>
						<i class="fa fa-calendar fa-fw"></i>
						<span>Register Date</span> : <?php echo $info['Date']?> 
					</li>
					<li>
						<i class="fa fa-tags fa-fw"></i>
						<span>Favourite Category</span> :
					</li> 
				</ul>
				<a href="" class="btn btn-defult">Edit Information</a>
			</div>
		</div>
	</div>
</div>

<div id="my-ads" class="my-ads block">
	<div class="container">
		<div class="panel panel-primary">
			<div class="panel-heading">My Items</div>
			<div class="panel-body">
					<?php
						$myItems = getAllFrom("*", "items", "WHERE Member_ID = $userid", "", "item_ID");
						if (! empty($myItems)) {
							echo '<div class="row">';
								foreach ($myItems as $item) {
								 	echo '<div class="col-sm-6 col-md-3">';
								 		echo '<div class="thumbnail item-box">';
								 		if($item['Approve'] == 0) {
								 			echo '<div class="approve-status">Waiting Approval</div>';	
								 		}
								 			echo '<span class="price-tag">$' . $item['Price'] . '</span>';
								 			echo '<img class="img-responsive img-thumbnail" src="img.ico" alt="" />';
								 			echo '<div class="caption">';
								 				echo '<h3><a href="items.php?itemid=' . $item['item_ID'] . '">' . $item['Name'] . '</a></h3>';
								 				echo '<p>' . $item['Description'] . '</p>';
								 				echo '<div class="date">' . $item['Add_Date'] . '</div>';
								 			echo '</div>';
								 		echo '</div>';
								 	echo '</div>';
							 }
			 	echo '<div>';
						} else {
							echo 'Sorry There\'s No Ads To Show, Create <a href="newad.php">New Ad</a>';
						}
					?>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="my-comments block">
	<div class="container">
		<div class="panel panel-primary">
			<div class="panel-heading">Latest Comment</div>
			<div class="panel-body">
				<?php
		    	// Select All Users Except Admin

				$myComments = getAllFrom("comment", "comments", "where user_id = $userid", "", "c_id");

		    	if(! empty($myComments)){
		    		foreach ($myComments as $comment) {
		    			echo '<p>' . $comment['comment'] . '</p>';
		    		}
		    	}else {
		    		echo 'There\'s No Comments To Show';
		    	}


				?>
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