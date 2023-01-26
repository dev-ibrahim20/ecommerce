<?php

// Start The Session

session_start();

// Unset The Data

session_unset();

// Destory The Session

session_destroy();

header('Location: index.php');
exit();