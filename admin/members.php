<?php
/*
============================================
== Manage Members Page
== You Can Add | Edit | Delete Members From Here
============================================
*/

ob_start();
session_start();

$pageTitle = 'Members';

if (isset($_SESSION['Username'])) {
    include 'init.php';
    
    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
    // Start Manage Page

    if ($do == 'Manage') { // Manage Members Page 

    	$query = '';

    	if (isset($_GET['page']) && $_GET['page'] == 'pending') {
    		$query = 'AND RegStatus = 0';
    	}

    	// Select All Users Except Admin

    	$stmt = $con->prepare("SELECT * FROM users WHERE GroupID != 1 $query ORDER BY UserID DESC");

    	// EXecute The Statement

    	$stmt->execute();

    	// Assign To Variable

    	$rows = $stmt->fetchAll();

    	if(!empty($rows)) {
    	?>

	    <h1 class="text-center">Manage Members</h1>
	    <div class="container">
	    	<div class="table-resposive">
	    		<table class="main-table text-center table table-bordered">
	    			<tr>
	    				<td>#ID</td>
	    				<td>Username</td>
	    				<td>Email</td>
	    				<td>Full Name</td>
	    				<td>Registerd Date</td>
	    				<td>Control</td>
	    			</tr>

	    			<?php
	    			foreach ($rows as $row) {
		    				 	echo "<tr>";
		    				 		echo "<td>" . $row['UserID'] . "</td>";
		    				 		echo "<td>" . $row['Username'] . "</td>";
		    				 		echo "<td>" . $row['Email'] . "</td>";
		    				 		echo "<td>" . $row['Fullname'] . "</td>";
		    				 		echo "<td>" . $row['Date'] . "</td>";
		    				 		echo "<td>";
					    				echo '<a href="members.php?do=Edit&userid=' . $row['UserID'] . '" class="btn btn-success"><i class="fa fa-edit"></i> Edit</a>';
					    				echo '<a href="members.php?do=Delete&userid=' . $row['UserID'] . '" class="btn btn-danger confirm"><i class="fa fa-close"></i> Delete</a>';
					    				if ($row['RegStatus'] == 0) {
											echo '<a href="members.php?do=Activate&userid=' . $row['UserID'] . '" class="btn btn-info "><i class="fa fa-check"></i> Activate</a>';
					    				}
					    				if($row['banned'] == 0) {
					    					echo '<a href="members.php?do=Banned&userid=' . $row['UserID'] . '" class="btn btn-danger "><i class="fa fa-close"></i> Banned</a>';	
					    				}else {
					    					echo '<a href="members.php?do=UnBanned&userid=' . $row['UserID'] . '" class="btn btn-info "><i class="fa fa-check"></i>Un Banned</a>';
					    				}
		    				 		echo "</td>";
		    				 	echo "</tr>";
	    			}	 
	    			?>
	    		</table>
	    	</div>
	    	<a href="members.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> New Member</a>
	    </div>

	<?php }else {
		echo "<div class='container'>";
			echo '<div class="nice-message">There\'s No Members To Show </div>';
			echo '<a href="members.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> New Member</a>';
		echo "</div>";
	} 
		 }elseif ($do == 'Add') { //Add Members Page  ?>

	    	<h1 class="text-center">Add New Member</h1>

	    	<div class="container">
	    		<form class="form-horizontal" action="?do=Insert" method="POST">
	    			<!-- Start Username Field -->
	    			<div class="form-group form-group-lg">
	    				<label class="col-sm-2 control-label">Username</label>
	    				<div class="col-sm-10 col-md-6">
	    					<input type="text" name="username" class="form-control" autocomplete="off" required="required" placeholder="Username To Login Shop">
	    				</div>
	    			</div>
	    			<!-- End Username Field -->
	    			<!-- Start Password Field -->
	    			<div class="form-group form-group-lg">
	    				<label class="col-sm-2 control-label">Password</label>
	    				<div class="col-sm-10 col-md-6">
	    					<input type="Password" name="password" class="password form-control" autocomplete="new-password" placeholder="Password Must Be Hard & Complex" required="required">
	    					<i class="show-pass fa fa-eye fa-2x"></i>
	    				</div>
	    			</div>
	    			<!-- End Password Field -->
	    			<!-- Start Email Field -->
	    			<div class="form-group form-group-lg">
	    				<label class="col-sm-2 control-label">Email</label>
	    				<div class="col-sm-10 col-md-6">
	    					<input type="email" name="email" class="form-control" required="required" placeholder="Email Must Be Vaild">
	    				</div>
	    			</div>
	    			<!-- End Email Field -->
	    			<!-- Start Fullname Field -->
	    			<div class="form-group form-group-lg">
	    				<label class="col-sm-2 control-label">Fullname</label>
	    				<div class="col-sm-10 col-md-6">
	    					<input type="text" name="full" class="form-control" required="required" placeholder="Full Name In Your Profile Page">
	    				</div>
	    			</div>
	    			<!-- End Fullname Field -->
					<!-- Start Submit Field -->
	    			<div class="form-group form-group-lg">
	    				<div class="col-sm-offset-2 col-sm-10">
	    					<input type="submit" value="Add Member" class="btn btn-primary btn-lg">
	    				</div>
	    			</div>
	    			<!-- End Submit Field -->
	    		</form>
	    	</div>


  <?php

  	}elseif ($do == 'Insert') { // Insert Member Page 

	    	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

		        echo "<h1 class='text-center'>Update Members</h1>";
		    	echo "<div class='container'>";

	    		// Get Variables From The Form

	    		$user 		= $_POST['username'];
	    		$pass 		= $_POST['password'];
	    		$email 		= $_POST['email'];
	    		$name 		= $_POST['full'];

	    		$hashpass = sha1($pass);


	    		// Validate The Form

	    		$formErrors = array();

	    		if (strlen($user) < 4) {
	    			$formErrors[] = 'Username Cant Be Less Than 4 Character Your Username Count Is ' . strlen($user) . ' :(';
	    		}

	    		if (strlen($user) > 20) {
	    			$formErrors[] = 'Username Cant Be More Than 20 Character Your Username Count Is ' . strlen($user) . ' :(';
	    		}

	    		if (empty($user)) {
	    			$formErrors[] = 'Username Cant By Empty';
	    		}

	    		if (empty($pass)) {
	    			$formErrors[] = 'Password Cant By Empty';
	    		}

	    		if (empty($name)) {
	    			$formErrors[] = 'Fullname Cant By Empty';
	    		}

	    		if (empty($email)) {
	    			$formErrors[] = 'Email Cant By Empty';
	    		}

	    		// Loop Into Errors Array And Echo It

	    		foreach ($formErrors as $error) {
	    			echo '<div class="alert alert-danger"> ' . $error . '</div>';
	    		}

	    		// Check If Theher's No Error Proceed The Update Operation

	    		if (empty($formErrors)) {
	    			// Check If User Exist In Database

	    			$check = checkItem("Username", "users", $user);

	    			if ($check == 1) {
	    				$theMsg = '<div class="alert alert-danger">Sorry This User Is Exist</div>';
	    				redirectHome($theMsg, 'back');
	    			} else {
	    			
				    		// Insert User Info In Database

			    			$stmt = $con->prepare("INSERT INTO 
			    										users(Username, Password, Email, Fullname, RegStatus, Date)
			    									    VALUES(:zuser, :zpass, :zmail, :zname, 1, now()) ");
			    			$stmt->execute(array(

			    				'zuser' => $user,
			    				'zpass' => $hashpass,
			    				'zmail' => $email,
			    				'zname' => $name
			    			));

				    		// Echo Success Message 

				    		 $theMsg = '<div class="alert alert-success"> Success Operation Your Operation Count Is ' . $stmt->rowCount() . ' Record Inserted</div>';
				    		 redirectHome($theMsg, 'back');
			    		}
	    			}
	    		
	    	}else {
	    		echo "<div class='container'>";
	    		$theMsg = '<div class="alert alert-danger">Sorry You Cant Browse This Page Directly</div>';

	    		redirectHome($theMsg);
	    		echo "</div>";
	    	}

    	echo "</div>";	

    }elseif ($do == 'Edit') { // Edit Page

    	// Check If Get Request userid Is Numeric & Get The Integer Value Of It

    	$userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ?  intval($_GET['userid']) : 0;

    	// Select All Data Depend On This ID

	    $stmt = $con->prepare("SELECT * FROM users WHERE Userid = ? LIMIT 1");

	    // Execute  Query

	    $stmt->execute(array($userid));

	    // Fetch Query 

	    $row = $stmt->fetch();

	    // The Row Count 

	    $count = $stmt->rowCount();

	    // If Ther's Such ID Show The Form

	    if ($count > 0) { ?>

	    	<h1 class="text-center">Edit Member</h1>

	    	<div class="container">
	    		<form class="form-horizontal" action="?do=Update" method="POST">
	    			<input type="hidden" name="userid" value="<?php echo $userid ?>">
	    			<!-- Start Username Field -->
	    			<div class="form-group form-group-lg">
	    				<label class="col-sm-2 control-label">Username</label>
	    				<div class="col-sm-10 col-md-6">
	    					<input type="text" name="username" class="form-control" value="<?php echo $row['Username'] ?>" autocomplete="off" required="required">
	    				</div>
	    			</div>
	    			<!-- End Username Field -->
	    			<!-- Start Password Field -->
	    			<div class="form-group form-group-lg">
	    				<label class="col-sm-2 control-label">Password</label>
	    				<div class="col-sm-10 col-md-6">
	    					<input type="hidden" name="oldpassword" value="<?php echo $row['Password'] ?>">
	    					<input type="Password" name="newpassword" class="form-control" autocomplete="new-password" placeholder="Leave Blank If You Don't Want To Change">
	    				</div>
	    			</div>
	    			<!-- End Password Field -->
	    			<!-- Start Email Field -->
	    			<div class="form-group form-group-lg">
	    				<label class="col-sm-2 control-label">Email</label>
	    				<div class="col-sm-10 col-md-6">
	    					<input type="email" name="email" class="form-control" value="<?php echo $row['Email'] ?>" required="required">
	    				</div>
	    			</div>
	    			<!-- End Email Field -->
	    			<!-- Start Fullname Field -->
	    			<div class="form-group form-group-lg">
	    				<label class="col-sm-2 control-label">Fullname</label>
	    				<div class="col-sm-10 col-md-6">
	    					<input type="text" name="full" class="form-control" value="<?php echo $row['Fullname'] ?>" required="required">
	    				</div>
	    			</div>
	    			<!-- End Fullname Field -->
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
    	echo "<h1 class='text-center'>Update Members</h1>";
    	echo "<div class='container'>";

	    	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	    		// Get Variables From The Form

	    		$id 		= $_POST['userid'];
	    		$user 		= $_POST['username'];
	    		$email 		= $_POST['email'];
	    		$name 		= $_POST['full'];

	    		// Password Trick

	    		$pass = empty($_POST['newpassword']) ? $_POST['oldpassword'] : sha1($_POST['newpassword']);

	    		// Validate The Form

	    		$formErrors = array();

	    		if (strlen($user) < 4) {
	    			$formErrors[] = 'Username Cant Be Less Than 4 Character Your Username Count Is ' . strlen($user) . ' :(';
	    		}

	    		if (strlen($user) > 20) {
	    			$formErrors[] = 'Username Cant Be More Than 20 Character Your Username Count Is ' . strlen($user) . ' :(';
	    		}

	    		if (empty($user)) {
	    			$formErrors[] = 'Username Cant By Empty';
	    		}

	    		if (empty($name)) {
	    			$formErrors[] = 'Fullname Cant By Empty';
	    		}

	    		if (empty($email)) {
	    			$formErrors[] = 'Email Cant By Empty';
	    		}

	    		// Loop Into Errors Array And Echo It

	    		foreach ($formErrors as $error) {
	    			echo '<div class="alert alert-danger"> ' . $error . '</div>';
	    		}

	    		// Check If Theher's No Error Proceed The Update Operation

	    		if (empty($formErrors)) {

	    			$stmt2 = $con->prepare("SELECT * FROM users WHERE Username = ? AND UserID != ?");


	    			$stmt2->execute(array($user, $id));

	    			$count = $stmt2->rowCount();


	    			if($count == 1) {
		    			$theMsg = '<div class="alert alert-danger">Sorry This User Is Exist</div>';
	    				redirectHome($theMsg, 'back');
	    			} else {
	    			
			    		// Update The Database With This Info

			    		 $stmt = $con->prepare("UPDATE users SET Username = ?, Email = ?, Fullname = ?, Password = ? WHERE UserID = ?");
			    		 $stmt->execute(array($user, $email, $name, $pass, $id));
			 
			    		 // Echo Success Message 

			    		$theMsg =  '<div class="alert alert-success"> Success Operation Your Operation Count Is ' . $stmt->rowCount() . ' Record Update</div>';
			    		redirectHome($theMsg, 'back');

		    		}
	    		}
	    		
	    	}else {
	    		$theMsg = '<div class="alert alert-danger">Sorry You Cant Browse This Page Directly</div>';
	    		redirectHome($theMsg);
	    	}

    	echo "</div>";
    }elseif ($do == 'Delete') { // Delete Member Page

    	echo "<h1 class='text-center'>Delete Members</h1>";
    	echo "<div class='container'>";
			    	// Check If Get Request userid Is Numeric & Get The Integer Value Of It

			    	$userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ?  intval($_GET['userid']) : 0;

			    	// Select All Data Depend On This ID

				    $check = checkItem("userid", "users", $userid);

				    // If Ther's Such ID Show The Form

				    if ($check > 0) { 
				    	$stmt = $con->prepare("DELETE FROM users WHERE UserID = :zuser");
				    	$stmt->bindparam(":zuser", $userid);

				    	$stmt->execute();

				    	$theMsg = '<div class="alert alert-success"> Success Operation Your Operation Count Is ' . $stmt->rowCount() . ' Record Deleted</div>';
				    	redirectHome($theMsg, 'back');

				    }else {
				    	$theMsg = '<div class="alert alert-danger">This Id Is Not Exist</div>';
				    	redirectHome($theMsg);
				    }
	    echo "</div>";
    } elseif ($do == 'Activate') {
    	echo "<h1 class='text-center'>Activate Members</h1>";
    	echo "<div class='container'>";
			    	// Check If Get Request userid Is Numeric & Get The Integer Value Of It

			    	$userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ?  intval($_GET['userid']) : 0;

			    	// Select All Data Depend On This ID

				    $check = checkItem("UserID", "users", $userid);

				    // If Ther's Such ID Show The Form

				    if ($check > 0) { 
				    	$stmt = $con->prepare("UPDATE users SET RegStatus = 1 WHERE UserID = ?");
				    	$stmt->execute(array($userid));

				    	$theMsg = '<div class="alert alert-success"> Success Operation Your Operation Count Is ' . $stmt->rowCount() . ' Record Activated</div>';
				    	redirectHome($theMsg, 'back');

				    }else {
				    	$theMsg = '<div class="alert alert-danger">This Id Is Not Exist</div>';
				    	redirectHome($theMsg);
				    }
	    echo "</div>";
    }elseif ($do == 'Banned') {
    	echo "<h1 class='text-center'>Banned Members</h1>";
    	echo "<div class='container'>";
			    	// Check If Get Request userid Is Numeric & Get The Integer Value Of It

			    	$userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ?  intval($_GET['userid']) : 0;

			    	// Select All Data Depend On This ID

				    $check = checkItem("UserID", "users", $userid);

				    // If Ther's Such ID Show The Form

				    if ($check > 0) { 
				    	$stmt = $con->prepare("UPDATE users SET banned = 1 WHERE UserID = ?");
				    	$stmt->execute(array($userid));

				    	$theMsg = '<div class="alert alert-success"> Success Operation Your Operation Count Is ' . $stmt->rowCount() . ' Record Activated</div>';
				    	redirectHome($theMsg, 'back');

				    }else {
				    	$theMsg = '<div class="alert alert-danger">This Id Is Not Exist</div>';
				    	redirectHome($theMsg);
				    }
	    echo "</div>";
    }elseif ($do == 'UnBanned') {
    	echo "<h1 class='text-center'>UnBanned Members</h1>";
    	echo "<div class='container'>";
			    	// Check If Get Request userid Is Numeric & Get The Integer Value Of It

			    	$userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ?  intval($_GET['userid']) : 0;

			    	// Select All Data Depend On This ID

				    $check = checkItem("UserID", "users", $userid);

				    // If Ther's Such ID Show The Form

				    if ($check > 0) { 
				    	$stmt = $con->prepare("UPDATE users SET banned = 0 WHERE UserID = ?");
				    	$stmt->execute(array($userid));

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