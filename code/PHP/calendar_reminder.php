<?php
	require "../config/config.php";
	$userid = $_POST["user"];
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	if($mysqli->connect_errno) {
		echo $mysqli->connect_error;
		exit();
	}
	$mysqli->set_charset('utf8');
	if (isset($_POST["id"]) && !empty($_POST["id"])) {
		$statement = $mysqli->prepare("UPDATE reminders SET finished = ? WHERE userid = ? AND id = ?");
		$statement->bind_param("iii", $_POST["status"], $userid, $_POST["id"]);

		$executed = $statement->execute();
		if(!$executed) {
			echo $mysqli->error;
		}
		if($statement->affected_rows == 1) {
			echo "true";
		}
		$statement->close();
	} else {
		$statement = "SELECT * FROM reminders WHERE userid = " . $userid . " AND (deadline >= '" . $_POST["start"] . "' AND deadline <= '" . $_POST["end"] . "');";
		$reminders = $mysqli->query($statement);
		if(!$reminders) {
			echo $mysqli->error;
			exit();
		}

		$results_array = [];
		while( $row = $reminders->fetch_assoc()) {
			array_push($results_array, $row);
		}

		echo json_encode($results_array);
	}

	$mysqli->close();
?>