<?php

	require "../config/config.php";
	$userid = $_POST["user"];
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	if($mysqli->connect_errno) {
		echo $mysqli->connect_error;
		exit();
	}
	$mysqli->set_charset('utf8');
	$deadline = null;
	if (isset($_POST["deadline"]) && !empty($_POST["deadline"])) {
		$deadline = $_POST["deadline"];
	}

	$statement = $mysqli->prepare("INSERT INTO reminders(title, deadline, notes, finished, userid) VALUES(?, ?, ?, ?, ?)");

	$statement->bind_param("ssssi", $_POST["title"], $deadline, $_POST["notes"], $_POST["status"], $userid);

	$executed = $statement->execute();
	if(!$executed) {
		echo $mysqli->error;
	}
	if($statement->affected_rows == 1) {
		$php_array = [
			"id" => $mysqli->insert_id,
			"success" => true
		];
		echo json_encode($php_array);
	} else {
		echo $statement->affected_rows;
	}

	$statement->close();

	$mysqli->close();


?>
