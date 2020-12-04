<?php
require "config/config.php";
session_start();
if( !isset($_SESSION["logged_in"]) || !$_SESSION["logged_in"] ) {
	header("Location: login.php");
}
else {
	$userid = $_SESSION["userid"];
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	if($mysqli->connect_errno) {
		echo $mysqli->connect_error;
		exit();
	}
	$mysqli->set_charset('utf8');
	$statement = "SELECT * FROM reminders WHERE userid = " . $userid . " ORDER BY deadline IS NULL, deadline ASC;";
	$reminders = $mysqli->query($statement);
	if(!$reminders) {
		echo $mysqli->error;
		exit();
	}

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

	$mysqli->close();
}



?>
<!DOCTYPE html>
<html>
<head>
	<title>My Reminders</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="icon" href="images/favicons/reminders.ico">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/reminders.css">
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
			<a class="nav-link" href="diaries.php">
				<svg id="diaryicon" width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-journal-bookmark-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
					<path d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2z"/>
					<path d="M1 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1H1zm0 3v-.5a.5.5 0 0 1 1 0V8h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1H1zm0 3v-.5a.5.5 0 0 1 1 0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1H1z"/>
					<path fill-rule="evenodd" d="M6 1h6v7a.5.5 0 0 1-.757.429L9 7.083 6.757 8.43A.5.5 0 0 1 6 8V1z"/>
				</svg>
				<span>Diaries</span>
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
	<div class="wrapper">
		<h1 class="d-flex flex-row flex-wrap">
			<span class="flex-grow-1">Reminders</span>
			<div class="col-12 col-md-1"></div>
			<button type="button" id="viewall" class="btn btn-info">View All</button>
			<button type="button" id="expand" class="btn btn-outline-success">Expand</button>
			<button type="button" id="collapseall" class="btn btn-outline-success">Collapse</button>

		</h1>
		<hr>

		<div class="range">
			<form action="" method="" id="searchform1">
				<div class="d-flex flex-row align-items-end">
					<div class="form-group">
						<label for="from">From</label>
						<input class="form-control" type="date" name="from" id="from1">
					</div>
					<div class="form-group">
						<label for="to">To</label>
						<input class="form-control" type="date" name="to" id="to1">
					</div>
					<button class="btn btn-primary flex-grow-1" type="submit">Search</button>
				</div>
					
				
			</form>
			<form class="" action="" method="" id="searchform2">
				<div class="d-flex flex-row align-items-end">
					<div class="form-group">
						<label for="from">From</label>
						<input class="form-control" type="date" name="from" id="from2">
					</div>
					<button class="btn btn-primary flex-grow-1" type="submit" id="sm-submit">Search</button>
				</div>
				<div class="form-group">
					<label for="to">To</label>
					<input class="form-control" type="date" name="to" id="to2">
				</div>
					
				
			</form>
		</div>



		<div id="all">
<?php
	$count = 1;
	$curr = "null";
	while ( $row = $reminders->fetch_assoc() ) {

		$icon = "far fa-square";
		$crossed = "";
		if ($row["finished"] == 1) {
			$icon = "far fa-check-square";
			$crossed = " crossed";
		}

		if ($row["deadline"] != $curr) {
			$curr = $row["deadline"];
			if ($count != 1) {
				echo "</ul></div></div></div>";
			}
			if ($row["deadline"] === null) {
				$str = "Undated";
				echo "<div class='card'><div class='card-header' id='head" . $count . "'><h2 class='mb-0'><button class='btn btn-link btn-block text-left shadow-none d-flex flex-row' type='button' data-toggle='collapse' data-target='#collapse" . $count . "' aria-expanded='true' aria-controls='collapse" . $count . "'><div class='flex-grow-1 date'>Undated</div><div class='d-flex flex-column justify-content-center'><i class='far fa-caret-square-up'></i></div></button></h2></div><div id='collapse" . $count . "' class='collapse multi-collapse show' aria-labelledby='head" . $count . "'><div class='card-body'><ul class='list-group text-wrap reminders'><li class='list-group-item list-group-item-action d-flex flex-row justify-content-start' data-id='" . $row["id"] . "' data-notes='" . $row["notes"] . "'><div class='d-flex flex-column justify-content-center button check'><i class='" . $icon . "'></i></div><div class='item" . $crossed . "'><span>" . $row["title"] . "</span></div><div class='flex-grow-1'></div><div class='d-flex flex-column justify-content-center button edit'><i class='fas fa-edit'></i></div><div class='d-flex flex-column justify-content-center button trash'><i class='far fa-trash-alt'></i></div></li>";
				
			} else {
				$date = date_create($curr);
				echo "<div class='card'><div class='card-header' id='head" . $count . "'><h2 class='mb-0'><button class='btn btn-link btn-block text-left shadow-none d-flex flex-row' type='button' data-toggle='collapse' data-target='#collapse" . $count . "' aria-expanded='true' aria-controls='collapse" . $count . "'><div class='flex-grow-1 date'>" . date_format($date,"F j, Y") . "</div><div class='d-flex flex-column justify-content-center'><i class='far fa-caret-square-up'></i></div></button></h2></div><div id='collapse" . $count . "' class='collapse multi-collapse show' aria-labelledby='head" . $count . "'><div class='card-body'><ul class='list-group text-wrap reminders'><li class='list-group-item list-group-item-action d-flex flex-row justify-content-start' data-id='" . $row["id"] . "' data-notes='" . $row["notes"] . "'><div class='d-flex flex-column justify-content-center button check'><i class='" . $icon . "'></i></div><div class='item" . $crossed . "'><span>" . $row["title"] . "</span></div><div class='flex-grow-1'></div><div class='d-flex flex-column justify-content-center button edit'><i class='fas fa-edit'></i></div><div class='d-flex flex-column justify-content-center button trash'><i class='far fa-trash-alt'></i></div></li>";
			}
			$count++;




		} else {
			echo "<li class='list-group-item list-group-item-action d-flex flex-row justify-content-start' data-id='" . $row["id"] . "' data-notes='" . $row["notes"] . "'><div class='d-flex flex-column justify-content-center button check'><i class='" . $icon . "'></i></div><div class='item" . $crossed . "'><span>" . $row["title"] . "</span></div><div class='flex-grow-1'></div><div class='d-flex flex-column justify-content-center button edit'><i class='fas fa-edit'></i></div><div class='d-flex flex-column justify-content-center button trash'><i class='far fa-trash-alt'></i></div></li>";
		}




	}
	echo "</ul></div></div></div>";

?>


		</div>
	</div>


	<div class="nav-divider"></div>

	<div class="add fixed-bottom d-flex flex-column justify-content-center">
		<form class="form-inline d-flex flex-row justify-content-between" method="" action="">
			<input class="form-control" type="text" placeholder="To Do..." id="content" name="content">
			<input class="form-control" type="date" name="deadline" id="deadline">
			<button class="btn btn-primary" type="submit">Add</button>
		</form>
	</div>


	<div class="form-wrapper">
		<div class="edit-form">
			<div class="d-flex flex-row">
				<h2 class="flex-grow-1">Reminder</h2>
				<button type="button" class="close rounded-lg" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<hr>
			<form>
				<div class="form-group">
					<label for="title" class="sr-only">Title</label>
					<input type="text" class="form-control" id="title" placeholder="To Do..." readonly="true">
					<small class="error">Empty Title!</small>
				</div>
				<div class="form-group">
					<label for="start" class="sr-only">Deadline</label>
					<input type="date" class="form-control" id="date" readonly="true">
					<small class="error">Invalid Date!</small>
				</div>
				<div class="form-group">
					<label for="notes" class="sr-only">Notes</label>
					<textarea class="form-control" id="notes" rows="3" placeholder="Notes..." readonly="true"></textarea>
				</div>
				<div class="d-flex flex-row">
					<button class="btn btn-warning" type="button" id="edit-btn">Edit</button>
					<button class="btn btn-info" type="submit" id="save-btn">Save</button>
					<button class="btn btn-danger" type="button" id="delete-btn">Delete</button>
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
	<script type="text/javascript" src="js/reminders.js"></script>
	<script type="text/javascript" src="js/weather.js"></script>
</body>
</html>