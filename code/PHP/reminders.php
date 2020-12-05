<?php

	require "../config/config.php";
	$userid = $_POST["user"];
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	if($mysqli->connect_errno) {
		echo $mysqli->connect_error;
		exit();
	}
	if (isset($_POST["title"]) && !empty($_POST["title"])) {
		$deadline = null;
		if (isset($_POST["deadline"]) && !empty($_POST["deadline"])) {
			$deadline = $_POST["deadline"];
		}
		
		$statement = $mysqli->prepare("UPDATE reminders SET title = ?, deadline = ?, notes = ? WHERE userid = ? AND id = ?");
		$statement->bind_param("sssii", $_POST["title"], $deadline, $_POST["notes"], $userid, $_POST["id"]);

		$executed = $statement->execute();
		if(!$executed) {
			echo $mysqli->error;
		}
		if($statement->affected_rows == 1) {
			echo "true";
		}
		
	} else {
		$statement = $mysqli->prepare("UPDATE reminders SET finished = ? WHERE userid = ? AND id = ?");
		$statement->bind_param("iii", $_POST["status"], $userid, $_POST["id"]);

		$executed = $statement->execute();
		if(!$executed) {
			echo $mysqli->error;
		}
		if($statement->affected_rows == 1) {
			echo "true";
		}
	}
	$statement->close();

	$mysqli->close();




?>
