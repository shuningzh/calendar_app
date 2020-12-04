<?php

	require "../config/config.php";
	$userid = $_POST["user"];
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	if($mysqli->connect_errno) {
		echo $mysqli->connect_error;
		exit();
	}

	if ($_POST["order"] == "insert") {
		$statement = $mysqli->prepare("INSERT INTO diaries(title, submitted_time, edit_time, content, visibility, userid) VALUES(?, ?, ?, ?, ?, ?)");
		$statement->bind_param("ssssii", $_POST["title"], $_POST["submit"], $_POST["submit"], $_POST["content"], $_POST["visibility"], $userid);
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
	} else if ($_POST["order"] == "update") {
		$statement = $mysqli->prepare("UPDATE diaries SET title = ?, edit_time = ?, content = ?, visibility = ? WHERE userid = ? AND id = ?");
		$statement->bind_param("sssiii", $_POST["title"], $_POST["edit"], $_POST["content"], $_POST["visibility"], $userid, $_POST["id"]);
		$executed = $statement->execute();
		if(!$executed) {
			echo $mysqli->error;
		}
		if($statement->affected_rows == 1) {
			echo "true";
		}
	} else if ($_POST["order"] == "delete") {
		$statement = $mysqli->prepare("DELETE FROM diaries where userid = ? AND id = ?");
		$statement->bind_param("ii", $userid, $_POST["id"]);
		$executed = $statement->execute();
		if(!$executed) {
			echo $mysqli->error;
		}
		if($statement->affected_rows == 1) {
			echo "true";
		}
	} else if ($_POST["order"] == "search") {
		$term = $mysqli->real_escape_string($_POST["term"]);
		$statement = $mysqli->prepare("SELECT id FROM diaries WHERE userid = ? AND (title LIKE '%" . $term . "%' OR content LIKE '%" . $term . "%') ORDER BY submitted_time DESC;");
		$statement->bind_param("i", $userid);
		$executed = $statement->execute();
		if(!$executed) {
			echo $mysqli->error;
		}
		$statement->bind_result($col);
		$results_array = [];
		while( $row = $statement->fetch() ) {
			array_push($results_array, $col);
		}
		echo json_encode($results_array);
	}

	$statement->close();

	$mysqli->close();

?>