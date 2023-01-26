<?php
include 'connect.php';
//Routs
    
$tpl = 'includes/templates/';	// Template Directory
$lang = 'includes/languages/';	// Language Directory
$func = 'includes/functions/';	// Functions Directory
$css = 'layout/css/';	// Css Directory
$js = 'layout/js/';	// Js Directory

// Include The Important File
include $func . 'functions.php';
include $lang . 'en.php';
include $tpl . 'header.php';

// Include Navbar On Pages Expect The One with $nonavbar Varibale
if (!isset($nonavbar)) {
	include $tpl . 'navbar.php';
}
