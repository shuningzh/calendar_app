<?php
	require "../config/config.php";
	$userid = $_POST["user"];

	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	if($mysqli->connect_errno) {
		echo $mysqli->connect_error;
		exit();
	}

	$statement = $mysqli->prepare("INSERT INTO events(title, location, start_time, end_time, notes, userid) VALUES(?, ?, ?, ?, ?, ?)");
	$statement->bind_param("sssssi", $_POST["title"], $_POST["location"], $_POST["start"], $_POST["end"], $_POST["notes"], $userid);

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