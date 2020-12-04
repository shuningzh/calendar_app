<?php

require "../config/config.php";

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ( $mysqli->connect_errno ) {
	echo $mysqli->connect_error;
	exit();
}
$mysqli->set_charset('utf8');

if ( (isset($_POST["username"]) && !empty($_POST["username"])) && (isset($_POST["name"]) && !empty($_POST["name"])) && (isset($_POST["email"]) && !empty($_POST["email"])) && (isset($_POST["userid"]) && !empty($_POST["userid"])) ) {


	$statement = $mysqli->prepare("SELECT * FROM users WHERE username = ? AND userid <> ?");
	$statement->bind_param("si", $_POST["username"], $_POST["userid"]);
	$executed = $statement->execute();
	if(!$executed) {
		echo $mysqli->error;
	}
	$statement->store_result();
	if($statement->num_rows != 0) {
		echo "existed";
	} else {
		$cityid = NULL;
		if ($_POST["cityid"] != "NA") {
			$cityid = $_POST["cityid"];
		}
		$statement = $mysqli->prepare("UPDATE users SET username = ?, name = ?, email = ?, location = ? WHERE userid = ?");
		$statement->bind_param("ssssi", $_POST["username"], $_POST["name"], $_POST["email"], $cityid, $_POST["userid"]);
		$executed = $statement->execute();
		if(!$executed) {
			echo $mysqli->error;
		}
		if($statement->affected_rows <= 1) {
			echo "updated";
		}
	}
	$statement->close();

} else if ( (isset($_POST["userid"]) && !empty($_POST["userid"])) && (isset($_POST["oldPass"]) && !empty($_POST["oldPass"])) && (isset($_POST["newPass"]) && !empty($_POST["newPass"])) ) {
	$old = hash("sha256", $_POST["oldPass"]);
	$new = hash("sha256", $_POST["newPass"]);
	$statement = $mysqli->prepare("SELECT * FROM users WHERE password = ? AND userid = ?");
	$statement->bind_param("si", $old, $_POST["userid"]);
	$executed = $statement->execute();
	if(!$executed) {
		echo $mysqli->error;
	}
	$statement->store_result();
	if($statement->num_rows != 1) {
		echo "wrong";
	} else {
		$statement = $mysqli->prepare("UPDATE users SET password = ? WHERE userid = ?");
		$statement->bind_param("si", $new, $_POST["userid"]);
		$executed = $statement->execute();
		if(!$executed) {
			echo $mysqli->error;
		}
		if($statement->affected_rows <= 1) {
			echo "changed";
		}
	}
	$statement->close();



} else {
	echo "Error";
	exit();
}

$mysqli->close();

?>