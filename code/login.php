<?php

require "config/config.php";
session_start();
if( !isset($_SESSION["logged_in"]) || !$_SESSION["logged_in"] ) {
	if ( isset($_POST["username"]) && isset($_POST["password"]) ) {
		if ( empty($_POST["username"]) || empty($_POST["password"]) ) {
			$error = "Please enter username and password.";

		}
		else {
			$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
			if($mysqli->connect_errno) {
				echo $mysqli->connect_error;
				exit();
			}
			$password = hash("sha256", $_POST["password"]);
			$statement = $mysqli->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
			$statement->bind_param("ss", $_POST["username"], $password);
			$executed = $statement->execute();
			if(!$executed) {
				echo $mysqli->error;
				exit();
			}
			$result = $statement->get_result();
			// $statement->store_result();
			if($result->num_rows > 0) {
				$user = $result->fetch_assoc();
				$_SESSION["userid"] = $user["userid"];
				$_SESSION["logged_in"] = true;
				header("Location: calendar.php");
			} else {
				$error = "Invalid username or password.";
			}

			$statement->close();

			$mysqli->close();
		} 
	}
}
else {
	header("Location: calendar.php");
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Sign In</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="icon" href="images/favicons/login.ico">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/login_signup.css">
</head>
<body>
	<nav class="fixed-top d-flex flex-row" id="nav">
		<div class="icon d-flex flex-column justify-content-center">
			<a href="home.php">
				<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-calendar-check-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
					<path fill-rule="evenodd" d="M4 .5a.5.5 0 0 0-1 0V1H2a2 2 0 0 0-2 2v1h16V3a2 2 0 0 0-2-2h-1V.5a.5.5 0 0 0-1 0V1H4V.5zM16 14V5H0v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2zm-5.146-5.146a.5.5 0 0 0-.708-.708L7.5 10.793 6.354 9.646a.5.5 0 1 0-.708.708l1.5 1.5a.5.5 0 0 0 .708 0l3-3z"/>
				</svg>
			</a>
		</div>
		<div class="d-flex flex-column justify-content-center inactive-nav">
			<a class="nav-link" href="home.php">Home</a>
		</div>
		<div class="d-flex flex-column justify-content-center inactive-nav">
			<a class="nav-link" href="diary_board.php">Diary Board</a>
		</div>
		<div class="flex-grow-1"></div>
		<div class="dropdown d-flex flex-column justify-content-center">
			<a class="dropdown-toggle nav-link" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-person-circle" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
					<path d="M13.468 12.37C12.758 11.226 11.195 10 8 10s-4.757 1.225-5.468 2.37A6.987 6.987 0 0 0 8 15a6.987 6.987 0 0 0 5.468-2.63z"/>
					<path fill-rule="evenodd" d="M8 9a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
					<path fill-rule="evenodd" d="M8 1a7 7 0 1 0 0 14A7 7 0 0 0 8 1zM0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8z"/>
				</svg>
			</a>
			<div class="dropdown-menu dropdown-menu-right" id="dropdown-option" aria-labelledby="navbarDropdown">
				<a class="dropdown-item" href="#">
					<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-person-check-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
						<path fill-rule="evenodd" d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm9.854-2.854a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 0 1 .708-.708L12.5 7.793l2.646-2.647a.5.5 0 0 1 .708 0z"/>
					</svg>
					Sign In
				</a>
				<a class="dropdown-item" href="signup.php">
					<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-person-plus-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
						<path fill-rule="evenodd" d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm7.5-3a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5z"/>
					</svg>
					Sign Up
				</a>
			</div>
			
		</div>
	</nav>
	<div class="nav-divider"></div>

	<div class="wrapper">
		<h1>Sign In</h1>
		<hr>
		<form method="POST" action="login.php">
			<div class="form-group">
				<label for="username">Username</label>
				<?php
					if ( isset($_POST["username"]) && !empty($_POST["username"])) {
						echo "<input type='text' class='form-control' placeholder='Username' id='username' name='username' value=" . $_POST["username"] . ">";
					} else {
						echo "<input type='text' class='form-control' placeholder='Username' id='username' name='username'>";
					}
				?>
			</div>
			<div class="form-group">
				<label for="password">Password</label>
				<input type="password" class="form-control" placeholder="Password" id="password" name="password">
			</div>
			<div class="error" style="margin-bottom: 10px;">
				<?php
					if ( isset($error) && !empty($error)) {
						echo $error;
					}
				?>
			</div>
			<button type="submit" class="btn btn-info btn-block">Sign In</button>
		</form>
		
	</div>
	<div class="text-center notes">
		<p>Don't have an account? <a href="signup.php" class="text-info">Sign up here</a></p>

	</div>




	<script type="text/javascript" src="js/jquery-3.5.1.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
	<script type="text/javascript" src="js/login.js"></script>
</body>
</html>