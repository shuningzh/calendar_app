<?php
require "config/config.php";
session_start();
if( !isset($_SESSION["logged_in"]) || !$_SESSION["logged_in"] ) {
	header("Location: login.php");
	exit();
}
else {
	$userid = $_SESSION["userid"];
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	if($mysqli->connect_errno) {
		echo $mysqli->connect_error;
		exit();
	}
	$mysqli->set_charset('utf8');
	$statement = "SELECT * FROM users WHERE userid = " . $userid . ";";
	$user = $mysqli->query($statement);
	if(!$user) {
		echo $mysqli->error;
		exit();
	}
	$user = $user->fetch_assoc();
	$userlocation = null;
	if (isset($user["location"]) && !empty($user["location"])) {
		$userlocation = $user["location"];
	}
	$statement = "SELECT * FROM locations WHERE cityid = '" . $userlocation . "';";
	$userlocation = $mysqli->query($statement);
	if(!$userlocation) {
		echo $mysqli->error;
		exit();
	}
	$userlocation = $userlocation->fetch_assoc();

	$statement = "SELECT country FROM locations GROUP BY country;";
	$countries = $mysqli->query($statement);
	if(!$countries) {
		echo $mysqli->error;
		exit();
	}
	if ($userlocation != null) {
		$statement = "SELECT * FROM locations WHERE country = '" . $userlocation["country"] . "' ORDER BY cityname;";
		$locations = $mysqli->query($statement);
		if(!$locations) {
			echo $mysqli->error;
			exit();
		}
	}
	$cityid = 0;
	if (isset($user["location"]) && !empty($user["location"])) {
		$cityid = $user["location"];
	}

	$mysqli->close();
}


?>
<!DOCTYPE html>
<html>
<head>
	<title>My Account</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="icon" href="images/favicons/account.ico">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/account.css">
	<link href="fontawesome/css/all.css" rel="stylesheet">
</head>
<body>
	<nav class="fixed-top d-flex flex-row" id="nav">
		<div class="icon d-flex flex-column justify-content-center">
			<a href="calendar.php">
				<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-calendar-check-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
					<path fill-rule="evenodd" d="M4 .5a.5.5 0 0 0-1 0V1H2a2 2 0 0 0-2 2v1h16V3a2 2 0 0 0-2-2h-1V.5a.5.5 0 0 0-1 0V1H4V.5zM16 14V5H0v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2zm-5.146-5.146a.5.5 0 0 0-.708-.708L7.5 10.793 6.354 9.646a.5.5 0 1 0-.708.708l1.5 1.5a.5.5 0 0 0 .708 0l3-3z"/>
				</svg>
			</a>
		</div>
		<div class="d-flex flex-column justify-content-center inactive-nav">
			<a class="nav-link" id="home" href="calendar.php">Home</a>
		</div>
		<div class="d-flex flex-column justify-content-center inactive-nav">
			<a class="nav-link" href="diary_board.php">
				<svg id="boardicon" width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-columns-gap" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
					<path fill-rule="evenodd" d="M6 1H1v3h5V1zM1 0a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1V1a1 1 0 0 0-1-1H1zm14 12h-5v3h5v-3zm-5-1a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1h-5zM6 8H1v7h5V8zM1 7a1 1 0 0 0-1 1v7a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1V8a1 1 0 0 0-1-1H1zm14-6h-5v7h5V1zm-5-1a1 1 0 0 0-1 1v7a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1V1a1 1 0 0 0-1-1h-5z"/>
				</svg>
				<span>Diary Board</span>
			</a>
		</div>
		<div class="flex-grow-1"></div>
		<div id="weather" class="text-center d-flex flex-row">
			<div class="d-flex flex-column justify-content-center weathericon"></div>
			<div class="d-flex flex-column justify-content-center"><p></p></div>
		</div>
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
					<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-person-lines-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
						<path fill-rule="evenodd" d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm7 1.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5zm-2-3a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5zm0-3a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5zm2 9a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5z"/>
					</svg>
					My Account
				</a>
				<a class="dropdown-item" href="home.php">
					<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-box-arrow-left" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
						<path fill-rule="evenodd" d="M6 12.5a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v2a.5.5 0 0 1-1 0v-2A1.5 1.5 0 0 1 6.5 2h8A1.5 1.5 0 0 1 16 3.5v9a1.5 1.5 0 0 1-1.5 1.5h-8A1.5 1.5 0 0 1 5 12.5v-2a.5.5 0 0 1 1 0v2z"/>
						<path fill-rule="evenodd" d="M.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L1.707 7.5H10.5a.5.5 0 0 1 0 1H1.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3z"/>
					</svg>
					Log Out
				</a>
			</div>
			
		</div>
	</nav>
	<div class="nav-divider"></div>
	<div class="wrapper">
		<div class="header text-center">
			<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-person-circle" fill="currentColor" xmlns="http://www.w3.org/2000/svg" style="font-size: 120px;">
				<path d="M13.468 12.37C12.758 11.226 11.195 10 8 10s-4.757 1.225-5.468 2.37A6.987 6.987 0 0 0 8 15a6.987 6.987 0 0 0 5.468-2.63z"/>
				<path fill-rule="evenodd" d="M8 9a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
				<path fill-rule="evenodd" d="M8 1a7 7 0 1 0 0 14A7 7 0 0 0 8 1zM0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8z"/>
			</svg>
			<h1><?php echo $user["name"];?></h1>
			<h5>Username: <?php echo $user["username"];?></h5>
		</div>
		<hr>
		<form method="POST">
			<div class="form-group form-row">
				<div class="col-12 col-md-6">
					<label for="username">Username</label>
					<input type="text" class="form-control" disabled="true" value="<?php echo $user["username"];?>" id="username" name="username">
					<small class="error"></small>
				</div>
				<div class="col-12 col-md-6">
					<label for="name">Preferred Name</label>
					<input type="text" disabled="true" class="form-control" value="<?php echo $user["name"];?>" id="name" name="name">
					<small class="error">Empty Name!</small>
				</div>
			</div>
			<div class="form-group">
				<label for="email">Email</label>
				<input type="email" class="form-control" disabled="true" value="<?php echo $user["email"];?>" id="email" name="email">
				<small class="error">Invalid Email!</small>
			</div>
			<div class="form-group form-row">
				<div class="col-12 col-md-4">
					<label for="country">Country</label>
					<select id="country" class="form-control" name="country" disabled="true">
						<option selected value="NA">Choose...</option>
<?php
	while( $row = $countries->fetch_assoc() ) {
		if ($userlocation != null && $row["country"] == $userlocation["country"]) {
			echo "<option selected value='" . $row["country"] . "'>" . $row["country"] . "</option>";
		} else {
			echo "<option value='" . $row["country"] . "'>" . $row["country"] . "</option>";
		}
	}
?>
					</select>
					<small class="error"></small>
				</div>
				<div class="col-12 col-md-4">
					<label for="state">State</label>
					<select id="state" class="form-control" name="state" disabled="true">
						<option selected disabled>Choose...</option>
<?php
	if (isset($locations) && !empty($locations)) {
		$count = 0;
		$selectedState = "";
		$array = [];
		while( $row = $locations->fetch_assoc() ) {
			if (!in_array($row["state"], $array)) {
				array_push($array, $row["state"]);
			}
		}
		sort($array);
		foreach ($array as $s) {
			if ($userlocation != null && $s == $userlocation["state"]) {
				echo "<option selected value='" . $s . "'>" . $s . "</option>";
				$selectedState = $s;
			} else {
				echo "<option value='" . $s . "'>" . $s . "</option>";
			}
		}
	}
?>
					</select>
				</div>
				<div class="col-12 col-md-4">
					<label for="city">City</label>
					<select id="city" class="form-control" name="city" disabled="true">
						<option selected disabled>Choose...</option>
<?php
	if (isset($locations) && !empty($locations)) {
		$locations->data_seek(0);
		while( $row = $locations->fetch_assoc() ) {
			if ($row["state"] == $selectedState) {
				if ( $userlocation != null && $row["cityid"] == $userlocation["cityid"] ) {
					echo "<option selected value='" . $row["cityid"] . "'>" . $row["cityname"] . "</option>";
				} else {
					echo "<option value='" . $row["cityid"] . "'>" . $row["cityname"] . "</option>";
				}
			}
				
		}
	}
?>
					</select>
				</div>
			</div>
			<div class="form-group form-row">
				<div class="col-12 col-md-6" id="edit-div">
					<button type="button" class="btn btn-warning btn-block" id="edit-button">Edit Profile</button>
				</div>
				<div class="col-12 col-md-6" id="update-button">
					<button type="submit" class="btn btn-info btn-block">Update Profile</button>
				</div>
				<div class="col-12 col-md-6">
					<button type="button" class="btn btn-danger btn-block" id="changePass">Change Password</button>
				</div>
			</div>
			
		</form>
	</div>

	<div id="changepassword-wrapper">
		<div id="form-wrapper">
			<form method="POST">
				<div class="form-group">
					<label for="oldpass" class="sr-only">Old Password</label>
					<input type="password" class="form-control" id="oldpass" placeholder="Old Password">
					<small class="error"></small>
				</div>
				<div class="form-group">
					<label for="newpass" class="sr-only">New Password</label>
					<input type="password" class="form-control" id="newpass" placeholder="New Password">
					<small class="error"></small>
				</div>
				<div class="form-group">
					<label for="confirmpass" class="sr-only">Confirm Password</label>
					<input type="password" class="form-control" id="confirmpass" placeholder="Confirm Password">
					<small class="error">Unmatched New Password!</small>
				</div>
				<div class="d-flex flex-row">
					<button class="btn btn-danger btn-block" type="submit">Change</button>
					<button class="btn btn-secondary" type="button">Cancel</button>
				</div>



			</form>
		</div>
	</div>


	<script type="text/JavaScript">
		let current_user_id = <?php echo $userid; ?>;
		let cityid = <?php echo $cityid; ?>;
	</script>
	<script type="text/javascript" src="js/jquery-3.5.1.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
	<script type="text/javascript" src="js/weather.js"></script>
	<script type="text/javascript" src="js/account.js"></script>
</body>
</html>