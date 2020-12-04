document.querySelector("form").onsubmit = function(e) {
	let username = document.querySelector("#username");
	let name = document.querySelector("#name");
	let email = document.querySelector("#email");
	let password = document.querySelector("#password");
	let confirmPassword = document.querySelector("#confirmPassword");
	if ( !username.classList.contains("is-valid") || !name.classList.contains("is-valid") || !email.classList.contains("is-valid") || !password.classList.contains("is-valid") || !confirmPassword.classList.contains("is-valid") ) {
		alert("Please fulfill all requirement first!");
		return false;
	} else {
		let data = "username=" + encodeURIComponent(username.value) + "&name=" + encodeURIComponent(name.value) + "&email=" + email.value + "&password=" + password.value;
		createUser(data, function(results) {
			if (results == "created") {
				return true;
			} else {
				console.log("User insertion error");
				return false;
			}
		});
	}
}

function createUser(postData, returnFunction) {
	let xhr = new XMLHttpRequest();
	xhr.open("POST", "PHP/signup.php", true);
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



document.querySelector("#username").oninput = function() {
	this.classList.remove("is-valid");
	this.classList.remove("is-invalid");
}

document.querySelector("#username").onchange = function() {
	let username = this.value.trim();
	this.value = username;
	usernameExist(username, function(results){
		if (results == "true") {
			document.querySelector("#username").classList.add("is-valid");
			document.querySelector("#username").classList.remove("is-invalid");
		} else {
			document.querySelector("#username").classList.remove("is-valid");
			document.querySelector("#username").classList.add("is-invalid");
		}
	});
}


function usernameExist(name, returnFunction) {
	let xhr = new XMLHttpRequest();
	let url = "PHP/signup.php?username=" + encodeURIComponent(name);
	xhr.open('GET', url, true);
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
	xhr.send();
}

document.querySelector("#name").oninput = function() {
	this.value = this.value.trim();
	let name = this.value;
	if (name.length != 0) {
		this.classList.remove("is-invalid");
		this.classList.add("is-valid");

	} else {
		this.classList.remove("is-valid");
		this.classList.add("is-invalid");
	}
}

document.querySelector("#email").oninput = function() {
	let email = this.value.trim();
	this.value = "";
	if (email.length != 0) {
		this.value = email;
		if (validateEmail(email)) {
			this.classList.remove("is-invalid");
			this.classList.add("is-valid");

		} else {
			this.classList.remove("is-valid");
			this.classList.add("is-invalid");
		}
	} else {
		this.classList.remove("is-valid");
		this.classList.add("is-invalid");
	}
}


// validating email function
function validateEmail(email) {
    const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}


document.querySelector("#password").oninput = function() {
	let password = this.value.trim();
	this.value = password;
	let error = validatePassword(password);
	if (error == "none") {
		if (password.length >= 8) {
			this.classList.remove("is-invalid");
			this.classList.add("is-valid");
			$("#password + .error").css("display", "none");
		} else {
			$("#password + .error").html("Minimum length: 8");
			$("#password + .error").css("display", "block");
			this.classList.remove("is-valid");
			this.classList.add("is-invalid");
		}
	} else {
		$("#password + .error").html(error);
		$("#password + .error").css("display", "block");
		this.classList.remove("is-valid");
		this.classList.add("is-invalid");
	}
	if (this.value == document.querySelector("#confirmPassword").value) {
		document.querySelector("#confirmPassword").classList.remove("is-invalid");
		document.querySelector("#confirmPassword").classList.add("is-valid");

	} else {
		document.querySelector("#confirmPassword").classList.remove("is-valid");
		document.querySelector("#confirmPassword").classList.add("is-invalid");
	}
}


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

document.querySelector("#confirmPassword").oninput = function() {

	if (this.value == document.querySelector("#password").value) {
		this.classList.remove("is-invalid");
		this.classList.add("is-valid");

	} else {
		this.classList.remove("is-valid");
		this.classList.add("is-invalid");
	}
}