<?php
	require "../config/config.php";
	$userid = $_POST["user"];

	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	if($mysqli->connect_errno) {
		echo $mysqli->connect_error;
		exit();
	}
	if (isset($_POST["title"]) && !empty($_POST["title"])) {
		$statement = $mysqli->prepare("UPDATE events SET title = ?, location = ?, start_time = ?, end_time = ?, notes = ? WHERE userid = ? AND id = ?");
		$statement->bind_param("sssssii", $_POST["title"], $_POST["location"], $_POST["start"], $_POST["end"], $_POST["notes"], $userid, $_POST["eventid"]);
	} else {
		$statement = $mysqli->prepare("DELETE FROM events where userid = ? AND id=?");
		$statement->bind_param("ii", $userid, $_POST["eventid"]);
	}

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