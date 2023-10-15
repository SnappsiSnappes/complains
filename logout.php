<?php
header('Location: login.php');
require_once 'head.php';

// Destroy the session
session_destroy();
$_SESSION=[];
$_POST = [];
// Redirect to login

?>
