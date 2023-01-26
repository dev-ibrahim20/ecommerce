<?php
/*
==================================================
== Template Page
==================================================
*/

ob_start(); // output Buffering Start

session_start();
$pageTitle = '';

if (isset($_SESSION['Username'])) {
	include 'init.php';
	$do == isset($_GET['do']) ? $_GET['do'] : 'Manage';

	if ($do == 'Manage') {
		// code...
	}elseif ($do == 'Add') {
		// code...
	}elseif ($do == 'Insert') {
		// code...
	}elseif ($do == 'Edit') {
		// code...
	}elseif ($do == 'Update') {
		// code...
	}elseif ($do == 'Delete') {
		// code...
	}elseif ($do == 'Activate') {
		// code...
	}

	include $tpl . 'footer.php';
}else {
	header('Location: index.php');
	exit();
}
ob_end_flush();

?>