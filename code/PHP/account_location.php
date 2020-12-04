<?php

require "../config/config.php";

if ( isset($_POST["country"]) && !empty($_POST["country"]) ) {
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	if ( $mysqli->connect_errno ) {
		echo $mysqli->connect_error;
		exit();
	}
	$mysqli->set_charset('utf8');

	$country = $mysqli->real_escape_string($_POST["country"]);
	if ( isset($_POST["state"]) && !empty($_POST["state"]) ) {
		$state = $mysqli->real_escape_string($_POST["state"]);
		$statement = "SELECT * FROM locations WHERE country = '" . $country . "' AND state = '" . $state . "' ORDER BY cityname;";
	} else {
		$statement = "SELECT * FROM locations WHERE country = '" . $country . "' ORDER BY cityname;";
	}
	$locations = $mysqli->query($statement);
	if(!$locations) {
		echo $mysqli->error;
		exit();
	}

	$results_array = [];
	while( $row = $locations->fetch_assoc()) {
		array_push($results_array, $row);
	}

	echo json_encode($results_array);

	$mysqli->close();

} else {
	echo "Error";
	exit();
}

?>