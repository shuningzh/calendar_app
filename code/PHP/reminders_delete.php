<?php
	require "../config/config.php";
	$userid = $_POST["user"];

	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	if($mysqli->connect_errno) {
		echo $mysqli->connect_error;
		exit();
	}
	$statement = $mysqli->prepare("DELETE FROM reminders where userid = ? AND id = ?");
	$statement->bind_param("ii", $userid, $_POST["id"]);

	$executed = $statement->execute();
	if(!$executed) {
		echo $mysqli->error;
	}
	
	if($statement->affected_rows == 1) {
		echo "true";
	} else {
		echo $statement->affected_rows;
	}
	$statement->close();

	$mysqli->close();
?>