<?php
/*
============================================
== Manage Comments Page
== You Can Edit | Delete | Approve Comment From Here
============================================
*/

ob_start();
session_start();

$pageTitle = 'Comments';

if (isset($_SESSION['Username'])) {
    include 'init.php';
    
    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
    // Start Manage Page

    if ($do == 'Manage') { // Manage Members Page 

    	// Select All Users Except Admin

    	$stmt = $con->prepare("SELECT 
    								comments.*, items.Name AS Item_Name, users.Username As Member
    							FROM 
    								comments
    							INNER JOIN
    								items
    							ON
    								items.item_ID = comments.item_id
    							INNER JOIN
    								users
    							ON
    								users.UserID = comments.user_id
    							ORDER BY
    								c_id DESC");

    	// EXecute The Statement

    	$stmt->execute();

    	// Assign To Variable

    	$rows = $stmt->fetchAll();
    	if (! empty($rows)) {
    	?>

	    <h1 class="text-center">Manage Comments</h1>
	    <div class="container">
	    	<div class="table-resposive">
	    		<table class="main-table text-center table table-bordered">
	    			<tr>
	    				<td>ID</td>
	    				<td>Comment</td>
	    				<td>Itme Name</td>
	    				<td>User Name</td>
	    				<td>Added Date</td>
	    				<td>Control</td>
	    			</tr>

	    			<?php
	    			foreach ($rows as $row) {
		    				 	echo "<tr>";
		    				 		echo "<td>" . $row['c_id'] . "</td>";
		    				 		echo "<td>" . $row['comment'] . "</td>";
		    				 		echo "<td>" . $row['Item_Name'] . "</td>";
		    				 		echo "<td>" . $row['Member'] . "</td>";
		    				 		echo "<td>" . $row['comment_date'] . "</td>";
		    				 		echo "<td>";
					    				echo '<a href="comments.php?do=Edit&comid=' . $row['c_id'] . '" class="btn btn-success"><i class="fa fa-edit"></i> Edit</a>';
					    				echo '<a href="comments.php?do=Delete&comid=' . $row['c_id'] . '" class="btn btn-danger confirm"><i class="fa fa-close"></i> Delete</a>';
					    				if ($row['status'] == 0) {
											echo '<a href="comments.php?do=Approve&comid=' . $row['c_id'] . '" class="btn btn-info "><i class="fa fa-check"></i> Approve</a>';
					    				}
		    				 		echo "</td>";
		    				 	echo "</tr>";
	    			}	 
	    			?>
	    		</table>
	    	</div>
	    </div>

    <?php 

	} else {
		echo "<div class='container'>";
			echo '<div class="nice-message">There\'s No Comments To Show </div>';
		echo "</div>";
	}

    }elseif ($do == 'Edit') { // Edit Page

    	// Check If Get Request comid Is Numeric & Get The Integer Value Of It

    	$comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ?  intval($_GET['comid']) : 0;

    	// Select All Data Depend On This ID

	    $stmt = $con->prepare("SELECT * FROM comments WHERE c_id = ?");

	    // Execute  Query

	    $stmt->execute(array($comid));

	    // Fetch Query 

	    $row = $stmt->fetch();

	    // The Row Count 

	    $count = $stmt->rowCount();

	    // If Ther's Such ID Show The Form

	    if ($count > 0) { ?>

	    	<h1 class="text-center">Edit Comment</h1>

	    	<div class="container">
	    		<form class="form-horizontal" action="?do=Update" method="POST">
	    			<input type="hidden" name="comid" value="<?php echo $comid ?>">
	    			<!-- Start Comment Field -->
	    			<div class="form-group form-group-lg">
	    				<label class="col-sm-2 control-label">Comment</label>
	    				<div class="col-sm-10 col-md-6">
	    					<textarea class="form-control" name="comment"><?php echo $row['comment']?></textarea>
	    				</div>
	    			</div>
	    			<!-- End Comment Field -->
					<!-- Start Submit Field -->
	    			<div class="form-group form-group-lg">
	    				<div class="col-sm-offset-2 col-sm-10">
	    					<input type="submit" value="Save" class="btn btn-primary btn-lg">
	    				</div>
	    			</div>
	    			<!-- End Submit Field -->
	    		</form>
	    	</div>

    <?php

    	// If There's No Such ID Show Error Message

    	}else {
    		echo '<div class="container">';
    		$theMsg = '<div class="alert alert-danger">Theres No Such ID</div>';
    		redirectHome($theMsg, 'back');
    		echo '</div>';
    	}
    } elseif ($do == 'Update') { // Update Page
    	echo "<h1 class='text-center'>Update Commetns</h1>";
    	echo "<div class='container'>";

	    	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	    		// Get Variables From The Form

	    		$comid 		= $_POST['comid'];
	    		$comment 	= $_POST['comment'];
	    			
	    		// Update The Database With This Info

	    		 $stmt = $con->prepare("UPDATE comments SET comment = ? WHERE c_id = ?");
	    		 $stmt->execute(array($comment, $comid));
	 
	    		 // Echo Success Message 

	    		$theMsg =  '<div class="alert alert-success"> Success Operation Your Operation Count Is ' . $stmt->rowCount() . ' Record Update</div>';
	    		redirectHome($theMsg, 'back');
	    		
	    	}else {
	    		$theMsg = '<div class="alert alert-danger">Sorry You Cant Browse This Page Directly</div>';
	    		redirectHome($theMsg);
	    	}

    	echo "</div>";
    }elseif ($do == 'Delete') { // Delete Member Page

    	echo "<h1 class='text-center'>Delete Comments</h1>";
    	echo "<div class='container'>";
			    	// Check If Get Request comid Is Numeric & Get The Integer Value Of It

			    	$comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ?  intval($_GET['comid']) : 0;

			    	// Select All Data Depend On This ID

				    $check = checkItem("c_id", "comments", $comid);

				    // If Ther's Such ID Show The Form

				    if ($check > 0) { 
				    	$stmt = $con->prepare("DELETE FROM comments WHERE c_id = :zcid");
				    	$stmt->bindparam(":zcid", $comid);

				    	$stmt->execute();

				    	$theMsg = '<div class="alert alert-success"> Success Operation Your Operation Count Is ' . $stmt->rowCount() . ' Record Deleted</div>';
				    	redirectHome($theMsg, 'back');

				    }else {
				    	$theMsg = '<div class="alert alert-danger">This Id Is Not Exist</div>';
				    	redirectHome($theMsg);
				    }
	    echo "</div>";
    } elseif ($do == 'Approve') {
    	echo "<h1 class='text-center'>Approve Comments</h1>";
    	echo "<div class='container'>";
			    	// Check If Get Request comid Is Numeric & Get The Integer Value Of It

			    	$comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ?  intval($_GET['comid']) : 0;

			    	// Select All Data Depend On This ID

				    $check = checkItem("c_id", "comments", $comid);

				    // If Ther's Such ID Show The Form

				    if ($check > 0) { 
				    	$stmt = $con->prepare("UPDATE comments SET Status = 1 WHERE c_id = ?");
				    	$stmt->execute(array($comid));

				    	$theMsg = '<div class="alert alert-success"> Success Operation Your Operation Count Is ' . $stmt->rowCount() . ' Record Activated</div>';
				    	redirectHome($theMsg, 'back');

				    }else {
				    	$theMsg = '<div class="alert alert-danger">This Id Is Not Exist</div>';
				    	redirectHome($theMsg);
				    }
	    echo "</div>";
    }

    include $tpl . 'footer.php';
}else {
    header('Location: index.php');
    exit();
}

ob_end_flush();
?>