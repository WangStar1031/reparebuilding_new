<?php
require_once __DIR__ . "/Mysql.php";
if( !isset($db)){
	$db = new Mysql();
	$db->exec("set names utf8");
}

function userVerification($_email_name, $_password){
	global $db;
	$sql = "SELECT * FROM user WHERE UserEmail='$_email_name' OR UserName='$_email_name'";
	$result = $db->select($sql);
	if( $_email_name == "admin"){
		if( $result){
			$row = $result[0];
			if( strcasecmp($row['Password'], $_password) == 0){
				return 'admin';
			}
			return false;
		} else{
			$sql = "insert into user(UserName, Password) values(?, ?)";
			$stmt = $db->prepare($sql);
			$stmt->execute(['admin', 'admin']);
			return "admin";
		}
	} else{
		if( $result == false){
			return false;
		}
		$row = $result[0];
		if( strcasecmp($row['Password'], $_password) == 0){
			return $_email_name;
		}
		return false;
	}
}
?>