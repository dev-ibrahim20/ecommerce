<?php
ob_start();
session_start();
$pageTitle = 'Login';

if (isset($_SESSION['user'])) {
	header('Location: index.php');
}

include 'init.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if (isset($_POST['login'])) {

		$user = $_POST['username'];
		$pass = $_POST['password'];
		$hashedPass = sha1($pass);

		// Check If The User Exist In Database
		$stmt = $con->prepare("SELECT 
	    							UserID, Username, Password, banned
	                            FROM 
	                                  users
	                            WHERE
	                                  Username = ?
	                            AND
	                                  Password = ?");
		$stmt->execute(array($user, $hashedPass));
		$get = $stmt->fetch();
		$count = $stmt->rowCount();

		// If Count > 0 This Mean The Database Contain Record About This Username

		if ($count > 0) {
			if ($get['banned']) {
				$theMsg = '<div class="alert alert-danger">Sorry You Are Banneded</div>';
				redirectHome($theMsg, 'login.php');
			} else {
				$_SESSION['user'] = $user;  // Register Session Name
				$_SESSION['uid'] = $get['UserID']; // Register User ID In Session
				header('Location: index.php');  // Redirect To index Page
				exit();
			}
		}
	} else {
		$formErrors = array();

		if (isset($_POST['username'])) {
			$filterdUser = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
			if (strlen($filterdUser) < 4) {
				$formErrors[] = 'Username Must Be Larger Than 4 Carters';
			}
		}

		if (isset($_POST['password']) && isset($_POST['password2'])) {
			if (empty($_POST['password'])) {
				$formErrors[] = 'Sorry Password Can\'t Be Empty';
			}
			$pass1 = sha1($_POST['password']);
			$pass2 = sha1($_POST['password2']);

			if ($pass1 !== $pass2) {
				$formErrors[] = 'Sorry Password Is Not Match';
			}
		}

		if (isset($_POST['email'])) {
			$filterdEmail = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
			if (filter_var($filterdEmail, FILTER_VALIDATE_EMAIL) != true) {
				$formErrors[] = 'This Email Is Not Valid';
			}
		}

		// Check If Theher's No Error Proceed The User Add

		if (empty($formErrors)) {
			// Check If User Exist In Database

			$check = checkItem("Username", "users", $_POST['username']);

			if ($check == 1) {
				$formErrors[] = 'Sorry This User Is Exists';
			} else {

				// Insert User Info In Database

				$stmt = $con->prepare("INSERT INTO 
	    										users(Username, Password, Email, RegStatus, Date)
	    									    VALUES(:zuser, :zpass, :zmail, 0, now()) ");
				$stmt->execute(array(

					'zuser' => $_POST['username'],
					'zpass' => sha1($_POST['password']),
					'zmail' => $_POST['email']
				));

				// Echo Success Message 

				$successMsg = 'Congrats You Are Now Registerd User';
			}
		}
	}
}
?>

<div class="container login-page">
	<h1 class="text-center">
		<span class="selected" data-class="login">Login</span> |
		<span data-class="signup">Signup</span>
	</h1>
	<!-- Start Login Form -->
	<form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
		<input class="form-control" type="text" name="username" autocomplete="off" placeholder="Type Your Username">
		<input class="form-control" type="password" name="password" autocomplete="new-password" placeholder="Type Your Password">
		<input class="btn btn-primary btn-block" name="login" type="submit" value="Login">
	</form>
	<!-- End Login Form -->
	<!-- Start Signup Form -->
	<form class="signup" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
		<div class="input-container">
			<input pattern=".{4,}" title="Username Must Be 4 Charaters" class="form-control" type="text" name="username" autocomplete="off" placeholder="Type Your Username" required>
		</div>
		<div class="input-container">
			<input class="form-control" minlength="4" type="password" name="password" autocomplete="new-password" placeholder="Type A Complex Password" required>
		</div>
		<div class="input-container">
			<input class="form-control" minlength="4" type="password" name="password2" autocomplete="new-password" placeholder="Type A Password Again" required>
		</div>
		<div class="input-container">
			<input class="form-control" type="email" name="email" placeholder="Type A Valid Email">
		</div>
		<input class="btn btn-success btn-block" name="signup" type="submit" value="Signup ">
	</form>
	<!-- End Signup Form -->
	<div class="the-errors msg text-center">
		<?php

		if (!empty($formErrors)) {
			foreach ($formErrors as $error) {
				echo '<div class="msg error">' . $error . '</div>';
			}
		}

		if (isset($successMsg)) {
			echo '<div class="mcg success">' . $successMsg . '</div>';
		}

		?>
	</div>
</div>


<?php
include $tpl . 'footer.php';
ob_end_flush();
?>