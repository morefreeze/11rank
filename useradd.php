<?php
require_once ('DbTools.class.php');
require_once ('11rank.php');

session_start();
if (isset($_POST['new_user']) && $_SESSION['uid'] > 0){
	if (watchNewUser($_SESSION['uid'], $_POST['new_user'])){
		$msg = "?msg=add new user watched successfully.";
	}
	else{
		$msg = "?msg=add new user watched failed.";
	}
}
else{
	$msg = "?msg=invalid user.";
}

header("Location: userlist".$get);
