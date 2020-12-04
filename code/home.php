<?php
	session_start();
	session_destroy();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Home</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="icon" href="images/favicons/home.ico">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/home.css">
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
		<div class="d-flex flex-column justify-content-center active-nav">
			<a class="nav-link" href="#">Home</a>
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
	</nav>

	<div class="lg-slide-show">
		<div id="carouselCaptions" class="carousel slide" data-ride="carousel">
			<ol class="carousel-indicators">
				<li data-target="#carouselCaptions" data-slide-to="0" class="active"></li>
				<li data-target="#carouselCaptions" data-slide-to="1"></li>
				<li data-target="#carouselCaptions" data-slide-to="2"></li>
			</ol>
			<div class="carousel-inner">
				<div class="carousel-item active">
					<img src="images/home-1.jpg" class="d-block w-100" alt="calendar">
					<div class="carousel-caption d-none d-md-block">
						<h5>Calendar</h5>
						<p>Create events. Keep track of your schedule. Stay organized.</p>
					</div>
				</div>
				<div class="carousel-item">
					<img src="images/home-2.jpg" class="d-block w-100" alt="todo">
					<div class="carousel-caption d-none d-md-block">
						<h5>To Do List</h5>
						<p>List your tasks. Strike them off. Feel accomplished.</p>
					</div>
				</div>
				<div class="carousel-item">
					<img src="images/home-3.jpg" class="d-block w-100" alt="diary">
					<div class="carousel-caption d-none d-md-block">
						<h5>Diary Book</h5>
						<p>Record daily events, thoughts, and feelings. Make every moment memorable.</p>
					</div>
				</div>
			</div>
			<a class="carousel-control-prev" href="#carouselCaptions" role="button" data-slide="prev">
				<span class="carousel-control-prev-icon" aria-hidden="true"></span>
				<span class="sr-only">Previous</span>
			</a>
			<a class="carousel-control-next" href="#carouselCaptions" role="button" data-slide="next">
				<span class="carousel-control-next-icon" aria-hidden="true"></span>
				<span class="sr-only">Next</span>
			</a>
		</div>
	</div>
	<div class="md-slide-show">
		<div id="carouselCaptions-md" class="carousel slide" data-ride="carousel">
			<ol class="carousel-indicators">
				<li data-target="#carouselCaptions-md" data-slide-to="0" class="active"></li>
				<li data-target="#carouselCaptions-md" data-slide-to="1"></li>
				<li data-target="#carouselCaptions-md" data-slide-to="2"></li>
			</ol>
			<div class="carousel-inner">
				<div class="carousel-item active">
					<img src="images/home-sm-1.jpg" class="d-block w-100" alt="calendar">
					<div class="carousel-caption d-none d-md-block">
						<h5>Calendar</h5>
						<p>Create events. Keep track of your schedule. Stay organized.</p>
					</div>
				</div>
				<div class="carousel-item">
					<img src="images/home-sm-2.jpg" class="d-block w-100" alt="todo">
					<div class="carousel-caption d-none d-md-block">
						<h5>To Do List</h5>
						<p>List your tasks. Strike them off. Feel accomplished.</p>
					</div>
				</div>
				<div class="carousel-item">
					<img src="images/home-sm-3.jpg" class="d-block w-100" alt="diary">
					<div class="carousel-caption d-none d-md-block">
						<h5>Diary Book</h5>
						<p>Record daily events, thoughts, and feelings. Make every moment memorable.</p>
					</div>
				</div>
			</div>
			<a class="carousel-control-prev" href="#carouselCaptions-md" role="button" data-slide="prev">
				<span class="carousel-control-prev-icon" aria-hidden="true"></span>
				<span class="sr-only">Previous</span>
			</a>
			<a class="carousel-control-next" href="#carouselCaptions-md" role="button" data-slide="next">
				<span class="carousel-control-next-icon" aria-hidden="true"></span>
				<span class="sr-only">Next</span>
			</a>
		</div>
	</div>
	<div class="sm-introduction">
		<div class="nav-divider"></div>
		<div>
			<img src="images/home-sm-1.jpg" class="d-block w-100" alt="calendar">
			<h5 class="text-center">Calendar</h5>
			<p>Create events. Keep track of your schedule. Stay organized.</p>
		</div>
		<hr>
		<div>
			<img src="images/home-sm-2.jpg" class="d-block w-100" alt="todo">
			<h5 class="text-center">To Do List</h5>
			<p>List your tasks. Strike them off. Feel accomplished.</p>
		</div>
		<hr>
		<div>
			<img src="images/home-sm-3.jpg" class="d-block w-100" alt="diary">
			<h5 class="text-center">Diary Book</h5>
			<p>Record daily events, thoughts, and feelings. Make every moment memorable.</p>
		</div>
		<hr>
	</div>
	<div class="links">
        <a href="signup.php" class="btn btn-outline-info btn-lg btn-block">Try Now</a>
        <p class="text-center">Already have an account? <a href="login.php" class="text-info lg-link">Sign in here.</a></p>
        <p class="sm-link text-center" style="text-align: center;"><a href="login.php" class="text-info">Sign in here.</a></p>
    </div>




	<script type="text/javascript" src="js/jquery-3.5.1.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</body>
</html>