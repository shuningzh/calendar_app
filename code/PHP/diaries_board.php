<?php

	require "../config/config.php";

	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	if($mysqli->connect_errno) {
		echo $mysqli->connect_error;
		exit();
	}
	if ( !isset($_POST["like"]) || empty($_POST["like"]) ) {
		$term = $mysqli->real_escape_string($_POST["term"]);
		$visibility = $_POST["visibility"];
		$statement = "SELECT id FROM diaries LEFT JOIN users ON users.userid = diaries.userid WHERE visibility <= " . $visibility . " AND (title LIKE '%" . $term . "%' OR content LIKE '%" . $term . "%' OR username LIKE '%" . $term . "%') ORDER BY submitted_time DESC;";
		$results = $mysqli->query($statement);
		if(!$results) {
			echo $mysqli->error;
			exit();
		}

		$results_array = [];
		while( $row = $results->fetch_assoc()) {
			array_push($results_array, $row);
		}
		echo json_encode($results_array);
	} else {
		if ($_POST["like"] == "no") {
			$statement = $mysqli->prepare("DELETE FROM likes WHERE userid = ? AND diaryid = ?");
		} else {
			$statement = $mysqli->prepare("INSERT INTO likes(userid, diaryid) VALUES(?, ?)");
		}
		$statement->bind_param("ii", $_POST["userid"], $_POST["id"]);
		$executed = $statement->execute();
		if(!$executed) {
			echo $mysqli->error;
		}
		if($statement->affected_rows == 1) {
			$statement = "SELECT COUNT(*) AS count FROM likes WHERE diaryid = " . $_POST["id"] . ";";
			$count = $mysqli->query($statement);
			if(!$count) {
				echo $mysqli->error;
				exit();
			}
			$count = $count->fetch_assoc();
			$count = $count["count"];
			$php_array = [
				"count" => $count,
				"success" => true
			];
			echo json_encode($php_array);
		}
	}

	$mysqli->close();


?>