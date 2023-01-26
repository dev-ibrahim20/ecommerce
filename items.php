<?php
	ob_start();
	session_start();
	$pageTitle = 'Show Itmes';
	include 'init.php';

	// Check If Get Request Item Is Numerice & Its Iteger Value
	$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ?  intval($_GET['itemid']) : 0;

	// Select All Data Depend On This ID

    $stmt = $con->prepare(" SELECT
    							items.*,  		
								categories.Name AS cat_name,
								users.Username AS user_name
							From
								items
							INNER JOIN
								categories
							ON
								categories.ID = items.Cat_ID
							INNER JOIN
								users
							ON
								users.UserID = items.Member_ID
    						WHERE 
    							   item_ID = ?
    						AND
    								Approve = 1");

    // Execute  Query

    $stmt->execute(array($itemid));

    $count = $stmt->rowCount();

    if($count > 0){

	    // Fetch Query 

	    $item = $stmt->fetch();

?>

<h1 class="text-center"><?php echo $item['Name']; ?></h1>

<div class="container">
	<div class="row">
		<div class="col-md-3">
			<img class="img-responsive img-thumbnail center-block" src="img.ico" alt="" />
		</div>
		<div class="col-md-9 item-info">
			<h2><?php echo $item['Name'] ?></h2>
			<p><?php echo $item['Description'] ?></p>
			<ul class="list-unstyled">
				<li>
					<i class="fa fa-calendar fa-fw"></i>
					<span>Added Date</sapn> : <?php echo $item['Add_Date'] ?>
				</li>
				<li>
					<i class="fa fa-money fa-fw"></i>
					<sapn>Price</sapn> : $<?php echo $item['Price'] ?>
				</li>
				<li>
					<i class="fa fa-building fa-fw"></i>
					<sapn>Made IN</sapn> : <?php echo $item['Country_Made'] ?>
				</li>
				<li>
					<i class="fa fa-tags"></i>
					<sapn>Category</sapn> : <a href="categories.php?pageid=<?php echo $item['Cat_ID'] ?>"><?php echo $item['cat_name']?></a>
				</li>
				<li>
					<i class="fa fa-user"></i>
					<sapn>Added BY</sapn> : <a href="#"><?php echo $item['user_name']?></a>
				</li>
			</ul>
		</div>
	</div>
	<hr class="custom-hr">
	<?php if (isset($_SESSION['user'])) { ?>
	<!-- Start Add Comment -->
	<div class="row">
		<div class="col-md-offset-3">
			<div class="add-comment">
				<h3>Add Your Comment</h3>
				<form action="<?php echo $_SERVER['PHP_SELF'] . '?itemid=' . $item['item_ID']?>" method="POST">
					<textarea name="comment" required></textarea>
					<input class="btn btn-primary" type="submit" name="" value="Add Comment">
				</form>
				<?php

					if ($_SERVER['REQUEST_METHOD'] == 'POST') {
						$comment 	= filter_var($_POST['comment'], FILTER_SANITIZE_STRING);
						$itemid 	= $item['item_ID'];
						$userid 	= $_SESSION['uid'];


						if (! empty($comment)) {
							$stmt = $con->prepare("INSERT INTO 
												comments(comment, status, comment_date, item_id, user_id)
												VALUES(:zcomment, 0, NOW(), :zitemid, :zuserid)");

							$stmt->execute(array(
								'zcomment' 	=> $comment,
								'zitemid' 	=> $itemid,
								'zuserid' 	=> $userid
							));

							if ($stmt) {
								echo '<div class="alert alert-success">Comment Added</div>';
							}
						}else {
								echo '<div class="alert alert-danger">Not Found Your Comment Please Write Comment</div>';
						}

					}

				?>
			</div>
		</div>
	</div>
	<!-- End Add Comment -->
<?php }else{
	echo '<a href="login.php">Login</a> Or <a href="login.php">Register</a> To Add Comment';	
} ?>
	<hr class="custom-hr">
		<?php

    	// Select All Users Except Admin

    	$stmt = $con->prepare("SELECT 
    								comments.*, users.Username As Member
    							FROM 
    								comments
    							INNER JOIN
    								users
    							ON
    								users.UserID = comments.user_id
    							WHERE
    								item_id = ?
    							AND
    								status = 1
    							ORDER BY
    								c_id DESC");

    	// EXecute The Statement

    	$stmt->execute(array($item['item_ID']));

    	// Assign To Variable

    	$comments = $stmt->fetchAll();

		?>
		<?php
		foreach ($comments as $comment) {?>
			<div class="comment-box">
				<div class="row">
					<div class="col-sm-2 text-center">
						<img class="img-responsive img-thumbnail img-circle center-block" src="img.ico">
						<?php echo $comment['Member']?>		
					</div>
					<div class="col-sm-10">
						<p class="lead"><?php echo $comment['comment'] ?></p>		
					</div>
				</div>
    		</div>
    		<hr class="custom-hr">
    	<?php }?>
</div>


<?php

	} else {
		echo '<div class="alert alert-danger">There\'s NO Such ID Or This Item Is Waiting Approval</div>';
	}
	include $tpl . 'footer.php';
	ob_end_flush();
?>