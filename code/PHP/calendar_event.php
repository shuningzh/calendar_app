<?php
	require "../config/config.php";
	$userid = $_POST["user"];
	$start = $_POST["start"];
	$end = $_POST["end"];
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	if($mysqli->connect_errno) {
		echo $mysqli->connect_error;
		exit();
	}
	$mysqli->set_charset('utf8');
	if (isset($start) && !empty($start)) {
		$statement = "SELECT * FROM events WHERE userid = " . $userid . " AND ( (end_time >= '" . $start . "' AND end_time <= '" . $end . "') OR (start_time >= '" . $start . "' AND start_time <= '" . $end . "' ) ) ORDER BY start_time;";
	} else {
		$statement = "SELECT * FROM events WHERE userid = " . $userid . " AND end_time = '" . $end . "';";
	}
	$events = $mysqli->query($statement);
	if(!$events) {
		echo $mysqli->error;
		exit();
	}

	$results_array = [];
	while( $row = $events->fetch_assoc()) {
		array_push($results_array, $row);
	}

	echo json_encode($results_array);

	$mysqli->close();
?>