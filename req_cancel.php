<?php
session_start();

$cancel = $_POST['cancel'];

if($cancel=='true'){    
	unset($_SESSION['uniqid']);
	unset($_SESSION['status']);
	unset($_SESSION['requestNumber']);
}