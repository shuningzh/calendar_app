<?php

require "../config/config.php";

if( isset($_GET["username"]) && !empty($_GET["username"]) ) {
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	if ( $mysqli->connect_errno ) {
		echo $mysqli->connect_error;
		exit();
	}
	$mysqli->set_charset('utf8');
	$statement = $mysqli->prepare("SELECT * FROM users WHERE username = ?");
	$statement->bind_param("s", $_GET["username"]);
	$executed = $statement->execute();
	if(!$executed) {
		echo $mysqli->error;
	}
	$statement->store_result();
	if($statement->num_rows == 0) {
		echo "true";
	} else {
		echo "false";
	}

	$statement->close();

	$mysqli->close();


} else if ( (isset($_POST["username"]) && !empty($_POST["username"])) && (isset($_POST["name"]) && !empty($_POST["name"])) && (isset($_POST["email"]) && !empty($_POST["email"])) && (isset($_POST["password"]) && !empty($_POST["password"])) ) {
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	if ( $mysqli->connect_errno ) {
		echo $mysqli->connect_error;
		exit();
	}
	$mysqli->set_charset('utf8');
	$password = hash("sha256", $_POST["password"]);
	$statement = $mysqli->prepare("INSERT INTO users(username, name, email, password) VALUES(?, ?, ?, ?)");
	$statement->bind_param("ssss", $_POST["username"], $_POST["name"], $_POST["email"], $password);
	$executed = $statement->execute();
	if(!$executed) {
		echo $mysqli->error;
	}
	if($statement->affected_rows == 1) {
		echo "created";
	} else {
		echo "false";
	}

	$statement->close();

	$mysqli->close();

} else {
	echo "Error";
	exit();
}

?>