<?php
require_once ("DbTools.class.php");

function check_valid($username){
	$conn = DbTools::getDbConnect('dota');
	$res = $conn->query("SELECT uid FROM dota.user WHERE uname LIKE '".
		$conn->escape_string($username)."' LIMIT 1");
	if (false === $res){

	}
	return empty($res);
}