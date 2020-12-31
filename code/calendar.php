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
	$statement = "SELECT location FROM users WHERE userid = " . $userid . ";";
	$results = $mysqli->query($statement);
	if(!$results) {
		echo $mysqli->error;
		exit();
	}
	$cityid = $results->fetch_assoc()["location"];
	if (!isset($cityid)) {
		$cityid = 0;
	}
}


?>
<!DOCTYPE html>
<html>
<head>
	<title>My Calendar</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="icon" href="images/favicons/calendar.ico">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/calendar.css">
	<link href="fontawesome/css/all.css" rel="stylesheet">
</head>
<body>
	<nav class="fixed-top d-flex flex-row" id="nav">
		<div class="icon d-flex flex-column justify-content-center">
			<a href="#">
				<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-calendar-check-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
					<path fill-rule="evenodd" d="M4 .5a.5.5 0 0 0-1 0V1H2a2 2 0 0 0-2 2v1h16V3a2 2 0 0 0-2-2h-1V.5a.5.5 0 0 0-1 0V1H4V.5zM16 14V5H0v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2zm-5.146-5.146a.5.5 0 0 0-.708-.708L7.5 10.793 6.354 9.646a.5.5 0 1 0-.708.708l1.5 1.5a.5.5 0 0 0 .708 0l3-3z"/>
				</svg>
			</a>
		</div>
		<div class="d-flex flex-column justify-content-center inactive-nav">
			<a class="nav-link" href="diary_board.php">
				<svg id="boardicon" width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-columns-gap" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
					<path fill-rule="evenodd" d="M6 1H1v3h5V1zM1 0a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1V1a1 1 0 0 0-1-1H1zm14 12h-5v3h5v-3zm-5-1a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1h-5zM6 8H1v7h5V8zM1 7a1 1 0 0 0-1 1v7a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1V8a1 1 0 0 0-1-1H1zm14-6h-5v7h5V1zm-5-1a1 1 0 0 0-1 1v7a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1V1a1 1 0 0 0-1-1h-5z"/>
				</svg>
				<span>Diary Board</span>
			</a>
		</div>
		<div id="lg-month" class="flex-grow-1">
			<div class="d-flex flex-column justify-content-center">
				<h1 class="title text-center">
					<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-arrow-left-circle-fill left" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
						<path fill-rule="evenodd" d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-4.5.5a.5.5 0 0 0 0-1H5.707l2.147-2.146a.5.5 0 1 0-.708-.708l-3 3a.5.5 0 0 0 0 .708l3 3a.5.5 0 0 0 .708-.708L5.707 8.5H11.5z"/>
					</svg>
					<span id="month1"></span> <span id="year1"></span>
					<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-arrow-right-circle-fill right" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
						<path fill-rule="evenodd" d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-11.5.5a.5.5 0 0 1 0-1h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5z"/>
					</svg>
				</h1>
			</div>
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
				<a class="dropdown-item" href="account.php">
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
	<div id="calendar-wrapper">
		<div id="sm-month">
			<div class="d-flex flex-column justify-content-center">
				<h1 class="text-center">
					<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-arrow-left-square-fill left" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
						<path fill-rule="evenodd" d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm9.5 8.5a.5.5 0 0 0 0-1H5.707l2.147-2.146a.5.5 0 1 0-.708-.708l-3 3a.5.5 0 0 0 0 .708l3 3a.5.5 0 0 0 .708-.708L5.707 8.5H11.5z"/>
					</svg>
					<span id="month2"></span> <span id="year2"></span>
					<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-arrow-right-square-fill right" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
						<path fill-rule="evenodd" d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm2.5 8.5a.5.5 0 0 1 0-1h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5z"/>
					</svg>
				</h1>
			</div>
		</div>
		<div class="calendar">
			<div class="text-center header header1">Sun</div>
			<div class="text-center header">Mon</div>
			<div class="text-center header">Tue</div>
			<div class="text-center header">Wed</div>
			<div class="text-center header">Thu</div>
			<div class="text-center header">Fri</div>
			<div class="text-center header">Sat</div>
			
		</div>
	</div>


	<div id="sm-events">
		<div class="calendar-divider"></div>
		<div class="content-container"></div>
		
	</div>

	<div class="navbar-bottom">
		<div class="nav-divider"></div>
		<div class="fixed-bottom row navbar text-center">
			<a href="diaries.php" class="col-6">
					<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-journal-bookmark-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
						<path d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2z"/>
						<path d="M1 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1H1zm0 3v-.5a.5.5 0 0 1 1 0V8h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1H1zm0 3v-.5a.5.5 0 0 1 1 0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1H1z"/>
						<path fill-rule="evenodd" d="M6 1h6v7a.5.5 0 0 1-.757.429L9 7.083 6.757 8.43A.5.5 0 0 1 6 8V1z"/>
					</svg>
					My Diaries

			</a>
			<a href="reminders.php" class="col-6">
					<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-ui-checks" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
						<path d="M7 2.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-7a.5.5 0 0 1-.5-.5v-1z"/>
						<path fill-rule="evenodd" d="M2 1a2 2 0 0 0-2 2v2a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2H2zm0 8a2 2 0 0 0-2 2v2a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2v-2a2 2 0 0 0-2-2H2zm.854-3.646l2-2a.5.5 0 1 0-.708-.708L2.5 4.293l-.646-.647a.5.5 0 1 0-.708.708l1 1a.5.5 0 0 0 .708 0zm0 8l2-2a.5.5 0 0 0-.708-.708L2.5 12.293l-.646-.647a.5.5 0 0 0-.708.708l1 1a.5.5 0 0 0 .708 0z"/>
						<path d="M7 10.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-7a.5.5 0 0 1-.5-.5v-1z"/>
						<path fill-rule="evenodd" d="M7 5.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm0 8a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5z"/>
					</svg>
					My Reminders

			</a>
		</div>
		<div class="circle-add fixed-bottom d-flex flex-column justify-content-center">
			<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-calendar2-plus" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
				<path fill-rule="evenodd" d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM2 2a1 1 0 0 0-1 1v11a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1H2z"/>
				<path d="M2.5 4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5H3a.5.5 0 0 1-.5-.5V4z"/>
				<path fill-rule="evenodd" d="M8 8a.5.5 0 0 1 .5.5V10H10a.5.5 0 0 1 0 1H8.5v1.5a.5.5 0 0 1-1 0V11H6a.5.5 0 0 1 0-1h1.5V8.5A.5.5 0 0 1 8 8z"/>
			</svg>
		</div>
	</div>
	<div class="form-wrapper">
		<div class="wrapper">
			<div class="d-flex flex-row">
				<h1 class="flex-grow-1">New Event</h1>
				<button type="button" class="close rounded-lg" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<hr>
			<form>
				<div class="form-group">
					<label for="title" class="sr-only">Title</label>
					<input type="text" class="form-control" id="title" placeholder="Title">
					<small class="error">Empty Title!</small>
				</div>
				<div class="form-group">
					<label for="location" class="sr-only">Location</label>
					<input type="text" class="form-control" id="location" placeholder="Location">
				</div>
				<div class="form-row">
					<div class="form-group col-12 col-md-6">
						<label for="start">Start Time</label>
						<input type="datetime-local" class="form-control" id="start">
						<small class="error">Invalid Start Time!</small>
					</div>
					<div class="form-group col-12 col-md-6">
						<label for="end">End Time</label>
						<input type="datetime-local" class="form-control" id="end">
						<small class="error">Invalid End Time!</small>
					</div>
				</div>
				<div class="form-group">
					<label for="notes" class="sr-only">Notes</label>
					<textarea class="form-control" id="notes" rows="3" placeholder="Notes..."></textarea>
					<div class="d-flex flex-row justify-content-between">
						<small class="font-italic text-muted"> Maximum 1000 characters (<span id="notes-remain-char"></span> remaining)</small>
						<small id="notes-char-num" class="text-muted"></small>
					</div>
				</div>
				<button class="btn btn-info btn-lg btn-block" type="submit">Add</button>
			</form>
		</div>
	</div>

	<div id="display-detail">
		<div class="wrapper">
			<div class="d-flex flex-row">
				<h1 class="flex-grow-1">Event Detail</h1>
				<button type="button" class="close rounded-lg" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<hr>
			<form>
				<div class="form-group">
					<label for="dtitle" class="sr-only">Title</label>
					<input type="text" class="form-control" id="dtitle" placeholder="Title">
					<small class="error">Empty Title!</small>
				</div>
				<div class="form-group">
					<label for="dlocation" class="sr-only">Location</label>
					<input type="text" class="form-control" id="dlocation" placeholder="Location">
				</div>
				<div class="form-row">
					<div class="form-group col-12 col-md-6">
						<label for="dstart">Start Time</label>
						<input type="datetime-local" class="form-control" id="dstart">
						<small class="error">Invalid Start Time!</small>
					</div>
					<div class="form-group col-12 col-md-6">
						<label for="dend">End Time</label>
						<input type="datetime-local" class="form-control" id="dend">
						<small class="error">Invalid End Time!</small>
					</div>
				</div>
				<div class="form-group">
					<label for="dnotes" class="sr-only">Notes</label>
					<textarea class="form-control" id="dnotes" rows="3" placeholder="Notes..."></textarea>
					<div class="d-flex flex-row justify-content-between">
						<small class="font-italic text-muted"> Maximum 1000 characters (<span id="dnotes-remain-char"></span> remaining)</small>
						<small id="dnotes-char-num" class="text-muted"></small>
					</div>
				</div>
				<input type="hidden" id="eventid" name="eventid" value="">

				<div class="d-flex flex-row">
					<button class="btn btn-info btn-lg" type="submit">Save</button>
					<button class="btn btn-outline-danger btn-lg" type="button" id="delete-btn">Delete</button>
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
	<script type="text/javascript" src="js/calendar_month.js"></script>
	<script type="text/javascript" src="js/calendar.js"></script>
	<script type="text/javascript" src="js/weather.js"></script>
</body>
</html>