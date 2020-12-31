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
	$statement = "SELECT * FROM diaries WHERE userid = " . $userid . " ORDER BY submitted_time DESC;";
	$diaries = $mysqli->query($statement);
	if(!$diaries) {
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
	<title>My Diaries</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="icon" href="images/favicons/diary.ico">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/diaries.css">
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
			<a class="nav-link" href="reminders.php">
				<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-ui-checks" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
					<path d="M7 2.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-7a.5.5 0 0 1-.5-.5v-1z"/>
					<path fill-rule="evenodd" d="M2 1a2 2 0 0 0-2 2v2a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2H2zm0 8a2 2 0 0 0-2 2v2a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2v-2a2 2 0 0 0-2-2H2zm.854-3.646l2-2a.5.5 0 1 0-.708-.708L2.5 4.293l-.646-.647a.5.5 0 1 0-.708.708l1 1a.5.5 0 0 0 .708 0zm0 8l2-2a.5.5 0 0 0-.708-.708L2.5 12.293l-.646-.647a.5.5 0 0 0-.708.708l1 1a.5.5 0 0 0 .708 0z"/>
					<path d="M7 10.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-7a.5.5 0 0 1-.5-.5v-1z"/>
					<path fill-rule="evenodd" d="M7 5.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm0 8a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5z"/>
				</svg>
				<span>Reminders</span>
			</a>
		</div>
		<div class="d-flex flex-column justify-content-center inactive-nav">
			<a class="nav-link" href="diary_board.php">
				<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-columns-gap" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
					<path fill-rule="evenodd" d="M6 1H1v3h5V1zM1 0a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1V1a1 1 0 0 0-1-1H1zm14 12h-5v3h5v-3zm-5-1a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1h-5zM6 8H1v7h5V8zM1 7a1 1 0 0 0-1 1v7a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1V8a1 1 0 0 0-1-1H1zm14-6h-5v7h5V1zm-5-1a1 1 0 0 0-1 1v7a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1V1a1 1 0 0 0-1-1h-5z"/>
				</svg>
				<span>Diary Board</span>
			</a>
		</div>
		<div class="d-flex flex-column justify-content-center inactive-nav">
			<div class="nav-link" id="new-btn">
				<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-vector-pen" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
					<path fill-rule="evenodd" d="M10.646.646a.5.5 0 0 1 .708 0l4 4a.5.5 0 0 1 0 .708l-1.902 1.902-.829 3.313a1.5 1.5 0 0 1-1.024 1.073L1.254 14.746 4.358 4.4A1.5 1.5 0 0 1 5.43 3.377l3.313-.828L10.646.646zm-1.8 2.908l-3.173.793a.5.5 0 0 0-.358.342l-2.57 8.565 8.567-2.57a.5.5 0 0 0 .34-.357l.794-3.174-3.6-3.6z"/>
					<path fill-rule="evenodd" d="M2.832 13.228L8 9a1 1 0 1 0-1-1l-4.228 5.168-.026.086.086-.026z"/>
				</svg>
				<span>New Diary</span>
			</div>
		</div>
		<div class="flex-grow-1"></div>
		<div id="weather" class="text-center d-flex flex-row">
			<div class="d-flex flex-column justify-content-center weathericon"></div>
			<div class="d-flex flex-column justify-content-center"><p></p></div>
		</div>
		<form class="form-inline nav-search-bar dsearch">
			<input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
			<button class="btn btn-primary my-2 my-sm-0" type="submit">Search</button>
		</form>
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
	<div class="wrapper d-flex flex-row justify-content-start flex-wrap">


<?php while( $row = $diaries->fetch_assoc() ): ?>

		<div class="card" <?php echo "data-id='" . $row["id"] . "' data-visibility='" . $row["visibility"] . "' data-edit='" . $row["edit_time"] . "'";?>>
			<img src="images/diary.jpg" class="card-img" alt="diaryImg">
			<div class="card-img-overlay d-flex flex-column justify-content-center">
				<h5 class="card-title text-center"><?php echo htmlspecialchars($row["title"]);?></h5>
				<p class="card-text"><?php echo htmlspecialchars($row["content"]);?></p>
				<div class="d-flex flex-row">
					<small class="card-text text-muted flex-grow-1 ml-1"></small>
					<small class="card-text text-muted">Created: <?php
					$date = date_create($row["submitted_time"]);
					echo date_format($date,"F j, Y");?></small>

				</div>
				<div class="d-flex flex-row button-group align-bottom">
					<a href="#" type="button" class="card-link btn btn-outline-info">View Detail</a>
					<a href="#" type="button" class="card-link btn btn-outline-danger">Delete</a>
				</div>
			</div>
		</div>
<?php endwhile; ?>

	</div>



	<div class="bottom-bar">
		<div class="bottom-divider"></div>
		<div class="fixed-bottom bottom-search-bar">
			<form class="d-flex flex-row dsearch">
				<input class="form-control" type="search" placeholder="Search" aria-label="Search">
				<button class="btn btn-primary" type="submit">Search</button>
			</form>
		</div>
	</div>

	<div class="new-diary-wrapper">
		<div class="diary-form">
			<div class="d-flex flex-row">
				<h1 class="flex-grow-1">New Diary</h1>
				<button type="button" class="close rounded-lg" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<hr>
			<form>
				<div class="form-group">
					<label for="title" class="sr-only">Title</label>
					<input type="text" class="form-control" id="title" placeholder="Title">
					<small class="error">Empty Title!</small>
				</div>
				<div class="form-group row">
					<label class="sr-only">Visibility: </label>
					<div class="form-check form-check-inline">
						<label class="form-check-label">
							<input type="radio" name="visibility" class="form-check-input ml-3" value="0" checked="checked">
							Public
						</label>
					</div>
					<div class="form-check form-check-inline">
						<label class="form-check-label ml-2">
							<input type="radio" name="visibility" class="form-check-input" value="1">
							Protected
						</label>
					</div>
					<div class="form-check form-check-inline">
						<label class="form-check-label ml-2">
							<input type="radio" name="visibility" class="form-check-input" value="2">
							Private
						</label>
					</div>
				</div>
				<div class="form-group">
					<label for="content" class="sr-only">Content</label>
					<textarea class="form-control" name="content" id="content" rows="9" placeholder="Write Something..."></textarea>
				</div>
				<button class="btn btn-info btn-lg btn-block" type="submit">Submit</button>
			</form>
		</div>
	</div>
	<div id="diary-detail">
		<div id="detail-wrapper" class="rounded-lg">
			<div id="view-edit">
				<h1 id="view-title"></h1>
				<small id="view-visibility"></small>
				<div id="view-content" class="mt-3"></div>
				<div class="d-flex flex-row flex-nowrap detail-buttons">
					<button type="button" class="btn btn-warning btn-lg flex-grow-1" id="edit-btn" style="margin-right: 2%;">Edit</button>
					<button type="button" class="btn btn-secondary btn-lg cancel-btn" style="margin-right: 2%;">Cancel</button>
				</div>
			</div>
			<form id="edit-detail">
				<input class="form-control" type="text" name="detail-title" id="detail-title" placeholder="Title">
				<div class="form-group row">
					<label class="sr-only">Visibility: </label>
					<div class="form-check form-check-inline">
						<label class="form-check-label">
							<input type="radio" name="visibility2" class="visibility2 form-check-input ml-3" value="0" checked="checked">
							Public
						</label>
					</div>
					<div class="form-check form-check-inline">
						<label class="form-check-label ml-2">
							<input type="radio" name="visibility2" class="visibility2 form-check-input" value="1">
							Protected
						</label>
					</div>
					<div class="form-check form-check-inline">
						<label class="form-check-label ml-2">
							<input type="radio" name="visibility2" class="visibility2 form-check-input" value="2">
							Private
						</label>
					</div>
				</div>
				<textarea class="form-control" id="detail-content" placeholder="Write Something..."></textarea>
				<div class="d-flex flex-row flex-nowrap detail-buttons">
					<button id="submit-edit" type="submit" class="btn btn-warning btn-lg flex-grow-1">Save</button>
					<button type="button" class="btn btn-secondary btn-lg cancel-btn">Cancel</button>
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
	<script type="text/javascript" src="js/diaries.js"></script>
	<script type="text/javascript" src="js/weather.js"></script>
</body>
</html>