<?php
require "config/config.php";
session_start();
if( !isset($_SESSION["logged_in"]) || !$_SESSION["logged_in"] ) {
	$visibility = 0;
	$userid = -1;
}
else {
	$visibility = 1;
	$userid = $_SESSION["userid"];
}
$cityid = 0;
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if($mysqli->connect_errno) {
	echo $mysqli->connect_error;
	exit();
}
$mysqli->set_charset('utf8');
$statement = "SELECT id, title, content, username FROM diaries LEFT JOIN users ON users.userid = diaries.userid WHERE visibility <= " . $visibility . " ORDER BY submitted_time DESC;";
$diaries = $mysqli->query($statement);
if(!$diaries) {
	echo $mysqli->error;
	exit();
}

$statement = "SELECT diaryid, COUNT(*) AS count FROM likes GROUP BY diaryid;";
$likes = $mysqli->query($statement);
if(!$likes) {
	echo $mysqli->error;
	exit();
}
$likesArr = [];
while($like = $likes->fetch_assoc()) {
	$likesArr[$like["diaryid"]] = $like["count"];
}

$likedByThisUser = [];
if ($userid != -1) {
	$statement = "SELECT diaryid FROM likes WHERE userid = " . $userid . ";";
	$results = $mysqli->query($statement);
	if(!$results) {
		echo $mysqli->error;
		exit();
	}
	while($i = $results->fetch_assoc()) {
		array_push($likedByThisUser, $i["diaryid"]);
	}

	$statement = "SELECT location FROM users WHERE userid = " . $userid . ";";
	$results = $mysqli->query($statement);
	if(!$results) {
		echo $mysqli->error;
		exit();
	}
	$temp = $results->fetch_assoc()["location"];
	if (isset($temp)) {
		$cityid = $temp;
	}
}

$mysqli->close();


?>
<!DOCTYPE html>
<html>
<head>
	<title>Diary Board</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="icon" href="images/favicons/diary_board.ico">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/diary_board.css">
	<link href="fontawesome/css/all.css" rel="stylesheet">
</head>
<body>
	<nav class="fixed-top d-flex flex-row" id="nav">
		<div class="icon d-flex flex-column justify-content-center">
<?php if( !isset($_SESSION["logged_in"]) || !$_SESSION["logged_in"] ):?>
			<a href="home.php">
<?php else:?>
			<a href="calendar.php">
<?php endif;?>
			<!-- <a href="home.php"> -->
				<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-calendar-check-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
					<path fill-rule="evenodd" d="M4 .5a.5.5 0 0 0-1 0V1H2a2 2 0 0 0-2 2v1h16V3a2 2 0 0 0-2-2h-1V.5a.5.5 0 0 0-1 0V1H4V.5zM16 14V5H0v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2zm-5.146-5.146a.5.5 0 0 0-.708-.708L7.5 10.793 6.354 9.646a.5.5 0 1 0-.708.708l1.5 1.5a.5.5 0 0 0 .708 0l3-3z"/>
				</svg>
			</a>
		</div>
		<div class="d-flex flex-column justify-content-center inactive-nav" id="home">
<?php if( !isset($_SESSION["logged_in"]) || !$_SESSION["logged_in"] ):?>
			<a class="nav-link" href="home.php">Home</a>
<?php else:?>
			<a class="nav-link" href="calendar.php">Home</a>
<?php endif;?>
		</div>
		<div class="d-flex flex-column justify-content-center active-nav">
			<a class="nav-link" href="#">
				<svg id="boardicon" width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-columns-gap" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
					<path fill-rule="evenodd" d="M6 1H1v3h5V1zM1 0a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1V1a1 1 0 0 0-1-1H1zm14 12h-5v3h5v-3zm-5-1a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1h-5zM6 8H1v7h5V8zM1 7a1 1 0 0 0-1 1v7a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1V8a1 1 0 0 0-1-1H1zm14-6h-5v7h5V1zm-5-1a1 1 0 0 0-1 1v7a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1V1a1 1 0 0 0-1-1h-5z"/>
				</svg>
				<span>Diary Board</span>
			</a>
		</div>
		<form class="form-inline nav-search-bar">
			<input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" id="search-top">
			<button class="btn btn-primary my-2 my-sm-0" type="submit">Search</button>
		</form>
		<div class="flex-grow-1"></div>
		<div id="weather" class="text-center d-flex flex-row">
			<div class="d-flex flex-column justify-content-center weathericon"></div>
			<div class="d-flex flex-column justify-content-center"><p></p></div>
		</div>
<?php if( !isset($_SESSION["logged_in"]) || !$_SESSION["logged_in"] ):?>
		<div class="dropdown d-flex flex-column justify-content-center">
			<a class="dropdown-toggle nav-link" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-person-circle" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
					<path d="M13.468 12.37C12.758 11.226 11.195 10 8 10s-4.757 1.225-5.468 2.37A6.987 6.987 0 0 0 8 15a6.987 6.987 0 0 0 5.468-2.63z"/>
					<path fill-rule="evenodd" d="M8 9a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
					<path fill-rule="evenodd" d="M8 1a7 7 0 1 0 0 14A7 7 0 0 0 8 1zM0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8z"/>
				</svg>
			</a>
			<div class="dropdown-menu dropdown-menu-right" id="dropdown-option" aria-labelledby="navbarDropdown">
				<a class="dropdown-item" href="login.php">
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
<?php else:?>
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
<?php endif;?>	
		
	</nav>


	<div class="nav-divider"></div>
	<div id="dcards">
		<div class="d-flex flex-row justify-content-start flex-wrap">
<?php while( $row = $diaries->fetch_assoc() ): ?>
			<div class="card diary-entry" data-closeclick="true" data-id="<?php echo $row["id"];?>">
				<div class="card-header d-flex flex-row">
					<span class="flex-grow-1"><?php echo $row["title"];?></span>
<?php if( (!isset($_SESSION["logged_in"]) || !$_SESSION["logged_in"]) ): ?>
					<div class="like-unloggedin d-flex flex-column justify-content-center">
<?php else: ?>
					<div class="like d-flex flex-column justify-content-center">
<?php endif;?>


<?php if( (!isset($_SESSION["logged_in"]) || !$_SESSION["logged_in"]) || in_array($row["id"], $likedByThisUser) ):?>
						<i class="fas fa-heart"></i>
<?php else:?>
						<i class="far fa-heart"></i>
<?php endif;?>
						<small class="text-center">
							<?php
								$num = 0;
								if (isset($likesArr[$row["id"]])) {
									$num = $likesArr[$row["id"]];
								}
								echo $num;
							?>
						</small>
					</div>
					<div class="minimize"><i class="far fa-minus-square"></i></div>
				</div>
				<div class="card-body">
					<blockquote class="blockquote mb-0">
						<p class="shown"><?php echo $row["content"];?></p>
						<p class="complete"><?php echo $row["content"];?></p>
						<footer class="blockquote-footer"><?php echo $row["username"];?></footer>
					</blockquote>
				</div>
			</div>
<?php endwhile;?>
		</div>
	</div>
	<div class="bottom-bar">
		<div class="nav-divider"></div>
		<div class="fixed-bottom bottom-search-bar">
			<form class="d-flex flex-row">
				<input class="form-control" type="search" placeholder="Search" aria-label="Search" id="search-bottom">
				<button class="btn btn-primary" type="submit">Search</button>
			</form>
		</div>
	</div>


	<script type="text/JavaScript">
		let vis = <?php echo $visibility; ?>;
		let current_user_id = <?php echo $userid; ?>;
		let cityid = <?php echo $cityid; ?>;
    </script>
	<script type="text/javascript" src="js/jquery-3.5.1.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
	<script type="text/javascript" src="js/diary_board.js"></script>
	<script type="text/javascript" src="js/weather.js"></script>

</body>
</html>