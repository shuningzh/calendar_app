$("#edit-button").on("click", function() {
	$("input, select").prop("disabled", false);
	$("#edit-div").fadeOut("slow", function() {
		$("#update-button").fadeIn();
	});
});

$(".wrapper form").on("submit", function(event) {
	event.preventDefault();
	let username = $.trim($("#username").val());
	let name = $.trim($("#name").val());
	let email = $.trim($("#email").val());
	let cityId = $("#city").val();
	if ( username.length == 0 ) {
		$("#username + .error").html("Empty Username");
		$("#username + .error").css("display", "block");
	} else {
		$("#username + .error").css("display", "none");
	}
	if (name.length == 0) {
		$("#name + .error").css("display", "block");
	} else {
		$("#name + .error").css("display", "none");
	}
	if ( !validateEmail(email) ) {
		$("#email + .error").css("display", "block");
	} else {
		$("#email + .error").css("display", "none");
	}
	if ( $("#country").val() != "NA") {
		if ( $("#state").val() == null ) {
			$("#country + .error").html("You must select a state");
			$("#country + .error").css("display", "block");
		} else if ( $("#city").val() == null ) {
			$("#country + .error").html("You must select a city");
			$("#country + .error").css("display", "block");
		} else {
			$("#country + .error").css("display", "none");
		}
	} else {
		$("#country + .error").css("display", "none");
		cityId = "NA";
	}
	let hasError = false;
	$(".wrapper .error").each(function() {
		if ($(this).css("display") == "block") {
			hasError = true;
			return false;
		}
	});
	if (hasError) {
		return;
	} else {
		let data = "username=" + encodeURIComponent(username) + "&name=" + encodeURIComponent(name) + "&email=" + email + "&cityid=" + cityId + "&userid=" + current_user_id;
		updateProfile(data, function(results) {
			if (results == "existed") {
				$("#username + .error").html("Username Existed");
				$("#username + .error").css("display", "block");
			} else {
				$("#username + .error").css("display", "none");
				if ( results == "updated" ) {
					$("input, select").prop("disabled", true);
					$(".header h1").html(name);
					$(".header h5").html("Username: " + username);
					$("#update-button").fadeOut("slow", function() {
						$("#edit-div").fadeIn();
					});
					cityid = cityId;
					$("#weather .weathericon").empty();
					$("#weather p").html("");
					if (cityid != "NA") {
						updateWeather(cityid);
					} else {
						cityid = 0;
					}
				} else {
					console.log("update profile error");
				}
			}
		});
	}
		
});

function updateProfile(postData, returnFunction) {
	let xhr = new XMLHttpRequest();
	xhr.open("POST", "PHP/account.php", true);
	xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	xhr.onreadystatechange = function(){
		if (xhr.readyState == XMLHttpRequest.DONE) {
			if (xhr.status == 200) {
				returnFunction( xhr.responseText );
			} else {
				alert('AJAX Error.');
				console.log(xhr.status);
			}
		}
	}
	xhr.send(postData);
}

// validating email function
function validateEmail(email) {
    const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}


$("#changePass").on("click", function() {
	$("#changepassword-wrapper").fadeIn();
	$("body").css("overflow", "hidden");
});

$("#form-wrapper").on("click", function(e) {
	e.stopPropagation();
});

$("#changepassword-wrapper").on("click", function() {
	$("#changepassword-wrapper").fadeOut();
	$("body").css("overflow", "auto");
});

$("#changepassword-wrapper, #changepassword-wrapper .btn-secondary").on("click", function() {
	$("#changepassword-wrapper").fadeOut("slow", function() {
		$("#oldpass").val("");
		$("#newpass").val("");
		$("#confirmpass").val("");
		$("#form-wrapper .error").css("display", "none");
	});
	$("body").css("overflow", "auto");
});

$("#form-wrapper form").on("submit", function(e) {
	e.preventDefault();
	let oldPass = $("#oldpass").val();
	if (oldPass.length == 0) {
		$("#oldpass + .error").html("Empty Old Password!");
		$("#oldpass + .error").css("display", "block");
	} else {
		$("#oldpass + .error").css("display", "none");
	}
	let newPass = $("#newpass").val();
	let error = validatePassword(newPass);
	if (error != "none") {
		$("#newpass + .error").html(error);
		$("#newpass + .error").css("display", "block");
	} else if (newPass.length < 8) {
		$("#newpass + .error").html("Minimum Length: 8 characters");
		$("#newpass + .error").css("display", "block");
	} else {
		$("#newpass + .error").css("display", "none");
	}
	let confirmPass = $("#confirmpass").val();
	if (newPass != confirmPass) {
		$("#confirmpass + .error").css("display", "block");
	} else {
		$("#confirmpass + .error").css("display", "none");
	}
	let hasError = false;
	$("#form-wrapper .error").each(function() {
		if ($(this).css("display") == "block") {
			hasError = true;
			return false;
		}
	});
	if (hasError) {
		return;
	} else {
		let data = "userid=" + current_user_id + "&oldPass=" + oldPass + "&newPass=" + newPass;
		updateProfile(data, function(results) {
			if (results == "wrong") {
				$("#oldpass + .error").html("Wrong Old Password!");
				$("#oldpass + .error").css("display", "block");
			} else {
				$("#oldpass + .error").css("display", "none");
				if ( results == "changed" ) {
					$("#changepassword-wrapper").fadeOut("slow", function() {
						$("#oldpass").val("");
						$("#newpass").val("");
						$("#confirmpass").val("");
					});
					$("body").css("overflow", "auto");
				}
			}
		});
	}
});

$("#newpass, #confirmpass").on("input", function() {
	if ($("#newpass").val() != $("#confirmpass").val()) {
		$("#confirmpass + .error").css("display", "block");
	} else {
		$("#confirmpass + .error").css("display", "none");
	}
});

$("#newpass").on("input", function() {
	let newPass = $("#newpass").val();
	let error = validatePassword(newPass);
	if (error != "none") {
		$("#newpass + .error").html(error);
		$("#newpass + .error").css("display", "block");
	} else if (newPass.length < 8) {
		$("#newpass + .error").html("Minimum Length: 8 characters");
		$("#newpass + .error").css("display", "block");
	} else {
		$("#newpass + .error").css("display", "none");
	}
});

// validate password and return error message
function validatePassword(password) {
	let lowercaseLetters = /[a-z]/g;
	let uppercaseLetters = /[A-Z]/g;
	let numbers = /[0-9]/g;
	if (password.match(lowercaseLetters)) {
		if (password.match(uppercaseLetters)) {
			if (password.match(numbers)) {
    			if (/\s/.test(password)) {
    				return "No whitespace allowed!";
    			} else {
    				return "none";
    			}
    		} else {
    			return "Must contain at least an number!";
    		}
		} else {
			return "Must contain at least an uppercase letter!";
		}
	} else {
		return "Must contain at least a lowercase letter!";
	}
}

let locations = null;
$("#country").on("change", function() {
	$("#state").html("<option selected disabled>Choose...</option>");
	$("#city").html("<option selected disabled>Choose...</option>");
	let country =  $("#country").val();
	if (country != "NA") {
		let data = "country=" + country;
		getLocations(data, function(results) {
			if (isJson(results)) {
				locations = JSON.parse(results);
				let states = [];
				for (let i = 0; i < locations.length; i++) {
					if (!states.includes(locations[i].state)) {
						states.push(locations[i].state);
					}
				}
				states.sort();
				for (let i = 0; i < states.length; i++) {
					let newElement = "<option value='" + states[i] + "'>" + states[i] + "</option>";
					$("#state").append(newElement);
				}
			}
		});
	}
});

$("#state").on("change", function() {
	$("#city").html("<option selected disabled>Choose...</option>");
	let country =  $("#country").val();
	let state = $("#state").val();
	if ( country != "NA" && state != null ) {
		if (locations != null) {
			for (let i = 0; i < locations.length; i++) {
				if (locations[i].state == state) {
					let newElement = "<option value='" + locations[i].cityid + "'>" + locations[i].cityname + "</option>";
					$("#city").append(newElement);
				}
			}
		} else {
			let data = "country=" + country;
			getLocations(data, function(results) {
				if (isJson(results)) {
					locations = JSON.parse(results);
					for (let i = 0; i < locations.length; i++) {
						if (locations[i].state == state) {
							let newElement = "<option value='" + locations[i].cityid + "'>" + locations[i].cityname + "</option>";
							$("#city").append(newElement);
						}
					}
				}
			});
		}
	}
});

function getLocations(postData, returnFunction) {
	let xhr = new XMLHttpRequest();
	xhr.open("POST", "PHP/account_location.php", true);
	xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	xhr.onreadystatechange = function(){
		if (xhr.readyState == XMLHttpRequest.DONE) {
			if (xhr.status == 200) {
				returnFunction( xhr.responseText );
			} else {
				alert('AJAX Error.');
				console.log(xhr.status);
			}
		}
	}
	xhr.send(postData);
}

function isJson(json) {
	try {
		JSON.parse(json);
	} catch(e) {
		return false;
	}
	return true;
}