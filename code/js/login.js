window.onload = function() {
	let error = document.querySelector(".error");
	if (error.innerHTML != "") {
		error.style.display = "block";
	} else {
		error.style.display = "none";
	}
}

document.querySelector("form").onsubmit = function(e) {
	let username = document.querySelector("#username").value.trim();
	if (username.length == 0) {
		document.querySelector(".error").innerHTML = "Empty Username!";
		document.querySelector(".error").style.display = "block";
		return false;
	} else {
		return true;
		document.querySelector(".error").style.display = "none";
		document.querySelector(".error").innerHTML = "";
	}
	
}