let number = $("#all").children().length + 1;
let onEdit = null;
$("#viewall").on("click", function(event) {
	event.preventDefault();
	$(".card").slideDown("slow");
	$("#from1").val("");
	$("#to1").val("");
	$("#from2").val("");
	$("#to2").val("");
});

$("#collapseall").on("click", function(event) {
	event.preventDefault();
	$("#all .collapse").collapse("hide");
});

$("#expand").on("click", function(event) {
	event.preventDefault();
	$("#all .collapse").collapse("show");
});


$("#all").on("click", ".card-header button", function(event) {
	let icon = $(this).find("i");
	if (icon.hasClass("fa-caret-square-down")) {
		icon.removeClass("fa-caret-square-down");
		icon.addClass("fa-caret-square-up");
	} else {
		icon.addClass("fa-caret-square-down");
		icon.removeClass("fa-caret-square-up");
	}
});

$("#all").on("click", ".card-body ul li .check", function(event) {
	let li = $(this).parent();
	let id = li.data("id");
	let text = li.find(".item");
	let icon = $(this).find("i");
	if (icon.hasClass("fa-square")) {
		let data = "id=" + id + "&title=&deadline=&notes=&status=1&user=" + current_user_id;
		ajaxUpdate(data, function(results) {
			if (results == "true") {
				icon.removeClass("fa-square");
				icon.addClass("fa-check-square");
				text.addClass("crossed");
			} else {
				alert("error");
			}
		});

		
	} else {
		let data = "id=" + id + "&title=&deadline=&notes=&status=0&user=" + current_user_id;
		ajaxUpdate(data, function(results) {
			if (results == "true") {
				icon.addClass("fa-square");
				icon.removeClass("fa-check-square");
				text.removeClass("crossed");
			} else {
				alert("error");
			}
		});
	}
	
});

function ajaxCreate(postData, returnFunction) {
	let xhr = new XMLHttpRequest();
	xhr.open("POST", "PHP/reminders_create.php", true);
	xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	xhr.onreadystatechange = function(){
		if (xhr.readyState == XMLHttpRequest.DONE) {
			if (xhr.status == 200) {
				returnFunction( xhr.responseText );
			} else {
				console.log(xhr.status);
			}
		}
	}
	xhr.send(postData);
}

function ajaxUpdate(postData, returnFunction) {
	let xhr = new XMLHttpRequest();
	xhr.open("POST", "PHP/reminders.php", true);
	xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	xhr.onreadystatechange = function(){
		if (xhr.readyState == XMLHttpRequest.DONE) {
			if (xhr.status == 200) {
				returnFunction( xhr.responseText );
			} else {
				console.log(xhr.status);
			}
		}
	}
	xhr.send(postData);
}



$("#all").on("click", ".card-body ul li .trash", function(event) {
	let li = $(this).parent();
	let ul = li.parent();
	let card = li.parent().parent().parent().parent();
	let data = "id=" + li.data("id") + "&user=" + current_user_id;
	ajaxDelete(data, function(results) {
		if (results == "true") {
			li.fadeOut("slow", function() {
				li.remove();
				if (ul.children().length < 1) {
					card.slideUp("slow", function(){
						card.remove();
					});
				}
			});
		} else {
			alert("Deletion Error");
		}
	});
});

function ajaxDelete(postData, returnFunction) {
	let xhr = new XMLHttpRequest();
	xhr.open("POST", "PHP/reminders_delete.php", true);
	xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	xhr.onreadystatechange = function(){
		if (xhr.readyState == XMLHttpRequest.DONE) {
			if (xhr.status == 200) {
				returnFunction( xhr.responseText );
			} else {
				console.log(xhr.status);
			}
		}
	}
	xhr.send(postData);
}



$(".range #searchform1").on("submit", function(event) {
	event.preventDefault();
	let from = $("#from1").val()+"T00:00:00";
	let to = $("#to1").val()+"T00:00:00";
	let fromObj = new Date(from);
	let toObj = new Date(to);
	if ( ( from != "T00:00:00" && isNaN(fromObj.getTime()) ) || ( to != "T00:00:00" && isNaN(toObj.getTime()) ) ) {
		$("#from1").val("");
		$("#to1").val("");
		alert("Invalid Dates!");
		return false;
	}
	if (fromObj.getTime() > toObj.getTime() ) {
		alert("Invalid Range!");
		return false;
	}
	$(".card").each(function() {
		let date = $(this).find(".date").html();
		let dateObj = new Date(date);
		let d = dateObj.getUTCDate();
		let m = dateObj.getUTCMonth();
		let y = dateObj.getUTCFullYear();
		let laterThanFrom = false;
		let earlierThanTo = false;
		if ( !isNaN(fromObj.getTime()) ) {
			if (isNaN(dateObj.getTime())) {
				$(this).slideUp("slow");
				return true;
			}
			let fromDay = fromObj.getUTCDate();
			let fromMonth = fromObj.getUTCMonth();
			let fromYear = fromObj.getUTCFullYear();
			
			if (y < fromYear) {
				$(this).slideUp("slow");
				return true;
			} else if (y == fromYear) {
				if (m < fromMonth) {
					$(this).slideUp("slow");
					return true;
				} else if (m == fromMonth) {
					if (d < fromDay) {
						$(this).slideUp("slow");
						return true;
					} else {
						laterThanFrom = true;
					}
				} else {
					laterThanFrom = true;
				}
			} else {
				laterThanFrom = true;
			}
		} else {
			laterThanFrom = true;
		}
		if ( !isNaN(toObj.getTime()) ) {
			if (isNaN(dateObj.getTime())) {
				$(this).slideUp("slow");
				return true;
			}
			let toDay = toObj.getUTCDate();
			let toMonth = toObj.getUTCMonth();
			let toYear = toObj.getUTCFullYear();
			if (y > toYear) {
				$(this).slideUp("slow");
				return true;
			} else if (y == toYear) {
				if (m > toMonth) {
					$(this).slideUp("slow");
					return true;
				} else if (m == toMonth) {
					if (d > toDay) {
						$(this).slideUp("slow");
						return true;
					} else {
						earlierThanTo = true;
					}
				} else {
					earlierThanTo = true;
				}
			} else {
				earlierThanTo = true;
			}
		} else {
			earlierThanTo = true;
		}
		if ( laterThanFrom && earlierThanTo ) {
			$(this).slideDown("slow");
		} else {
			$(this).slideUp("slow");
		}
	});

});


$(".range #searchform2").on("submit", function(event) {
	event.preventDefault();
	let from = $("#from2").val()+"T00:00:00";
	let to = $("#to2").val()+"T00:00:00";
	let fromObj = new Date(from);
	let toObj = new Date(to);
	if ( ( from != "T00:00:00" && isNaN(fromObj.getTime()) ) || ( to != "T00:00:00" && isNaN(toObj.getTime()) ) ) {
		$("#from2").val("");
		$("#to2").val("");
		alert("Invalid Dates!");
		return false;
	}
	if (fromObj.getTime() > toObj.getTime() ) {
		alert("Invalid Range!");
		return false;
	}
	$(".card").each(function() {
		let date = $(this).find(".date").html();
		let dateObj = new Date(date);
		let d = dateObj.getUTCDate();
		let m = dateObj.getUTCMonth();
		let y = dateObj.getUTCFullYear();
		let laterThanFrom = false;
		let earlierThanTo = false;
		if ( !isNaN(fromObj.getTime()) ) {
			if (isNaN(dateObj.getTime())) {
				$(this).slideUp("slow");
				return true;
			}
			let fromDay = fromObj.getUTCDate();
			let fromMonth = fromObj.getUTCMonth();
			let fromYear = fromObj.getUTCFullYear();
			
			if (y < fromYear) {
				$(this).slideUp("slow");
				return true;
			} else if (y == fromYear) {
				if (m < fromMonth) {
					$(this).slideUp("slow");
					return true;
				} else if (m == fromMonth) {
					if (d < fromDay) {
						$(this).slideUp("slow");
						return true;
					} else {
						laterThanFrom = true;
					}
				} else {
					laterThanFrom = true;
				}
			} else {
				laterThanFrom = true;
			}
		} else {
			laterThanFrom = true;
		}
		if ( !isNaN(toObj.getTime()) ) {
			if (isNaN(dateObj.getTime())) {
				$(this).slideUp("slow");
				return true;
			}
			let toDay = toObj.getUTCDate();
			let toMonth = toObj.getUTCMonth();
			let toYear = toObj.getUTCFullYear();
			if (y > toYear) {
				$(this).slideUp("slow");
				return true;
			} else if (y == toYear) {
				if (m > toMonth) {
					$(this).slideUp("slow");
					return true;
				} else if (m == toMonth) {
					if (d > toDay) {
						$(this).slideUp("slow");
						return true;
					} else {
						earlierThanTo = true;
					}
				} else {
					earlierThanTo = true;
				}
			} else {
				earlierThanTo = true;
			}
		} else {
			earlierThanTo = true;
		}
		if ( laterThanFrom && earlierThanTo ) {
			$(this).slideDown("slow");
		} else {
			$(this).slideUp("slow");
		}
	});

});

function isJson(json) {
	try {
		JSON.parse(json);
	} catch(e) {
		return false;
	}
	return true;
}

$("#content").on("input", function() {
	let len = $(this).val().length;
	if (len > 100) {
		$(this).addClass("text-danger");
	} else {
		$(this).removeClass("text-danger");
	}

});


$(".add form").on("submit", function(event) {
	event.preventDefault();
	let item = $("#content").val().trim();
	if (item.length == 0) {
		alert("Empty Entry!");
		return false;
	} else if (item.length > 100) {
		alert("Max length: " + item.length + "/100");
		return false;
	}
	let deadline = $("#deadline").val()+"T00:00:00";
	let d = new Date(deadline);
	if ( !isNaN(d.getTime()) ) {
		let ye = new Intl.DateTimeFormat('en', { year: 'numeric' }).format(d);
		let mo = new Intl.DateTimeFormat('en', { month: 'long' }).format(d);
		let da = new Intl.DateTimeFormat('en', { day: '2-digit' }).format(d);
		let date = `${mo} ${da}, ${ye}`;
		let data = "title=" + encodeURIComponent(item) + "&deadline=" + $("#deadline").val() + "&notes=&status=0&user=" + current_user_id;
		ajaxCreate(data, function(results) {
			if (isJson(results)) {
				results = JSON.parse(results);
				if (results.success == true) {
					let found = false;
					let smaller = null;
					$(".card").each(function(){
						if ($(this).find(".date").html() == date) {
							$(this).find("ul").append("<li class='list-group-item list-group-item-action d-flex flex-row justify-content-start' data-id='" + results.id + "' data-notes=''><div class='d-flex flex-column justify-content-center button check'><i class='far fa-square'></i></div><div class='item'><span>"+ item.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;') +"</span></div><div class='flex-grow-1'></div><div class='d-flex flex-column justify-content-center button edit'><i class='fas fa-edit'></i></div><div class='d-flex flex-column justify-content-center button trash'><i class='far fa-trash-alt'></i></div></li>");
							$("#content").val("");
							$("#deadline").val("");
							found = true;
							return false;
						} else {
							let currDate = new Date($(this).find(".date").html());
							if (currDate < d) {
								smaller = $(this);
							}
						}
					});
					if (!found) {
						let parent = $("#all");
						let headerid = "head" + number;
						let collapseid = "collapse" + number;
						let newElement = "<div class='card'><div class='card-header' id="+ headerid +"><h2 class='mb-0'><button class='btn btn-link btn-block text-left shadow-none d-flex flex-row' type='button' data-toggle='collapse' data-target='#"+ collapseid + "' aria-expanded='true' aria-controls='" + collapseid + "'><div class='flex-grow-1 date'>" + date + "</div><div class='d-flex flex-column justify-content-center'><i class='far fa-caret-square-up'></i></div></button></h2></div><div id='" + collapseid + "' class='collapse multi-collapse show' aria-labelledby='" + headerid + "'><div class='card-body'><ul class='list-group text-wrap reminders'><li class='list-group-item list-group-item-action d-flex flex-row justify-content-start' data-id='" + results.id + "' data-notes=''><div class='d-flex flex-column justify-content-center button check'><i class='far fa-square'></i></div><div class='item'><span>" + item.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;') +"</span></div><div class='flex-grow-1'></div><div class='d-flex flex-column justify-content-center button edit'><i class='fas fa-edit'></i></div><div class='d-flex flex-column justify-content-center button trash'><i class='far fa-trash-alt'></i></div></li></ul></div></div></div>";
						if (smaller != null) {
							smaller.after(newElement);
						} else {
							parent.prepend(newElement);
						}
						number++;
						$("#content").val("");
						$("#deadline").val("");
					}
					$("#from1").val("");
					$("#to1").val("");
					$("#from2").val("");
					$("#to2").val("");
				} else {
					alert("error");
				}
			} else {
				console.log(results);
			}
		});
	} else if ($("#deadline").val() == "") {
		let data = "title=" + encodeURIComponent(item) + "&deadline=&notes=&status=0&user=" + current_user_id;
		ajaxCreate(data, function(results) {
			if (isJson(results)) {
				results = JSON.parse(results);
				if (results.success == true) {
					let found = false;
					$(".card").each(function(){
						if ($(this).find(".date").html() == "Undated") {
							$(this).find("ul").append("<li class='list-group-item list-group-item-action d-flex flex-row justify-content-start' data-id='" + results.id + "' data-notes=''><div class='d-flex flex-column justify-content-center button check'><i class='far fa-square'></i></div><div class='item'><span>"+ item.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;') +"</span></div><div class='flex-grow-1'></div><div class='d-flex flex-column justify-content-center button edit'><i class='fas fa-edit'></i></div><div class='d-flex flex-column justify-content-center button trash'><i class='far fa-trash-alt'></i></div></li>");
							$("#content").val("");
							$("#deadline").val("");
							found = true;
							return false;
						}
					});
					if (!found) {
						let parent = $("#all");
						let headerid = "head" + number;
						let collapseid = "collapse" + number;
						let newElement = "<div class='card'><div class='card-header' id="+ headerid +"><h2 class='mb-0'><button class='btn btn-link btn-block text-left shadow-none d-flex flex-row' type='button' data-toggle='collapse' data-target='#"+ collapseid + "' aria-expanded='true' aria-controls='" + collapseid + "'><div class='flex-grow-1 date'>Undated</div><div class='d-flex flex-column justify-content-center'><i class='far fa-caret-square-up'></i></div></button></h2></div><div id='" + collapseid + "' class='collapse multi-collapse show' aria-labelledby='" + headerid + "'><div class='card-body'><ul class='list-group text-wrap reminders'><li class='list-group-item list-group-item-action d-flex flex-row justify-content-start' data-id='" + results.id + "' data-notes=''><div class='d-flex flex-column justify-content-center button check'><i class='far fa-square'></i></div><div class='item'><span>" + item.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;') +"</span></div><div class='flex-grow-1'></div><div class='d-flex flex-column justify-content-center button edit'><i class='fas fa-edit'></i></div><div class='d-flex flex-column justify-content-center button trash'><i class='far fa-trash-alt'></i></div></li></ul></div></div></div>";
						parent.append(newElement);
						number++;
						$("#content").val("");
						$("#deadline").val("");
					}
					$("#from1").val("");
					$("#to1").val("");
					$("#from2").val("");
					$("#to2").val("");
				}
			}
		});
	} else {
		alert("Invalid Date!");
	}
});

$(".edit-form form").on("submit", function(event) {
	event.preventDefault();
	let title = $.trim($("#title").val());
	$("#title").val(title);
	if (title.length == 0) {
		$("#title").next().children().first().css("display", "block");
	} else if (title.length > 100) {
		return;
	} else {
		$("#title").next().children().first().css("display", "none");
	}
	let notes = $("#notes").val();
	if (notes.length > 1000) {
		return;
	}
	let id = onEdit.data("id");
	onEdit.data("notes", notes);
	let date = $("#date").val() + "T00:00:00";
	let d = new Date(date);
	if ( isNaN(d.getTime()) && date != "T00:00:00" ) {
		$("#date + .error").css("display", "block");
	} else {
		$("#date + .error").css("display", "none");
	}

	if ( $("#title").next().children().first().css("display") == "none" && $("#date + .error").css("display") == "none" ) {

		let data = "id=" + id + "&title=" + encodeURIComponent(title) + "&deadline=" + $("#date").val() + "&notes=" + encodeURIComponent(notes) + "&user=" + current_user_id;
		ajaxUpdate(data, function(results) {
			if (results == "true") {
				let dateTitle = "Undated";
				if (date != "T00:00:00") {
					let ye = new Intl.DateTimeFormat('en', { year: 'numeric' }).format(d);
					let mo = new Intl.DateTimeFormat('en', { month: 'long' }).format(d);
					let da = new Intl.DateTimeFormat('en', { day: '2-digit' }).format(d);
					dateTitle = `${mo} ${da}, ${ye}`;
				}
				let prevDateTitle = onEdit.parent().parent().parent().parent().find(".date").html();
				let oldUl = onEdit.parent();
				let card = onEdit.parent().parent().parent().parent();
				onEdit.find("span").text(title);
				if (dateTitle != prevDateTitle) {
					let found = false;
					let smaller = null;
					$(".card").each(function(){
						if ( $(this).find(".date").html() == dateTitle ) {
							let ul = $(this).find(".reminders");
							onEdit.detach().appendTo(ul);
							found = true;
							return false;
						} else {
							let currDate = new Date($(this).find(".date").html());
							if (currDate < d) {
								smaller = $(this);
							}
						}
					});
					if (!found) {
						let parent = $("#all");
						let headerid = "head" + number;
						let collapseid = "collapse" + number;
						let newElement = "<div class='card'><div class='card-header' id="+ headerid +"><h2 class='mb-0'><button class='btn btn-link btn-block text-left shadow-none d-flex flex-row' type='button' data-toggle='collapse' data-target='#"+ collapseid + "' aria-expanded='true' aria-controls='" + collapseid + "'><div class='flex-grow-1 date'>" + dateTitle + "</div><div class='d-flex flex-column justify-content-center'><i class='far fa-caret-square-up'></i></div></button></h2></div><div id='" + collapseid + "' class='collapse multi-collapse show' aria-labelledby='" + headerid + "'><div class='card-body'><ul class='list-group text-wrap reminders newUl'></ul></div></div></div>";
						if (smaller != null) {
							smaller.after(newElement);
						} else {
							parent.prepend(newElement);
						}
						$(".newUl").append(onEdit);
						$(".newUl").removeClass("newUl");
						number++;
					}
					if (oldUl.children().length < 1) {
						card.slideUp("slow", function(){
							card.remove();
						});
					}
				}
				$(".form-wrapper").fadeOut("slow", function() {
					$("#edit-btn").css("display", "block");
					$("#save-btn").css("display", "none");
					$(".edit-form form input, .edit-form form textarea").prop("readonly", true);
					$(".edit-form .error").css("display", "none");
				});
			} else {
				alert("error");
			}
		});
	}
	
});



$(".close").on("click", function(event) {
	event.preventDefault();
	$(".form-wrapper").fadeOut("slow", function() {
		$("#edit-btn").css("display", "block");
		$("#save-btn").css("display", "none");
		$(".edit-form form input, .edit-form form textarea").prop("readonly", true);
		$(".edit-form .error").css("display", "none");
	});
});

$("#all").on("click", ".card-body ul li .edit",  function(event) {
	onEdit = $(this).parent();
	let title = onEdit.find("span").text();
	let date = onEdit.parent().parent().parent().parent().find(".date").html();
	dateStr = "";
	if (date != "Undated") {
		let dateObj = new Date(date);
		let ye = new Intl.DateTimeFormat('en', { year: 'numeric' }).format(dateObj);
		let mo = new Intl.DateTimeFormat('en', { month: '2-digit' }).format(dateObj);
		let da = new Intl.DateTimeFormat('en', { day: '2-digit' }).format(dateObj);
		dateStr = `${ye}-${mo}-${da}`;
	}
	let notes = onEdit.data("notes");
	$("#title").val(title);
	$("#title").next().children().last().addClass("text-muted");
	$("#title").next().children().last().removeClass("text-danger");
	$("#title").next().children().last().children().text(title.length);
	$("#date").val(dateStr);
	$("#notes").val(notes);
	$("#notes").next().addClass("text-muted");
	$("#notes").next().removeClass("text-danger");
	$("#notes").next().children().text(notes.length);

	$(".form-wrapper").fadeIn("slow");

});

$("#edit-btn").on("click", function(event) {
	$(this).fadeOut("slow", function(){
		$(".edit-form form input, .edit-form form textarea").prop("readonly", false);
		$("#save-btn").fadeIn();
	});
});

$("#title").on("input", function() {
	let len = $(this).val().length;
	$(this).next().children().last().children().text(len);
	if (len > 100) {
		$(this).next().children().last().addClass("text-danger");
		$(this).next().children().last().removeClass("text-muted");
	} else {
		$(this).next().children().last().addClass("text-muted");
		$(this).next().children().last().removeClass("text-danger");
	}
});

$("#notes").on("input", function() {
	let len = $(this).val().length;
	$(this).next().children().text(len);
	if (len > 1000) {
		$(this).next().addClass("text-danger");
		$(this).next().removeClass("text-muted");
	} else {
		$(this).next().addClass("text-muted");
		$(this).next().removeClass("text-danger");
	}
});

$("#delete-btn").on("click", function(event) {
	$(".form-wrapper").fadeOut("slow", function(){
		$(".edit-form form input, .edit-form form textarea").prop("readonly", true);
		$("#edit-btn").css("display", "block");
		$("#save-btn").css("display", "none");
	});
	let ul = onEdit.parent();
	let card = onEdit.parent().parent().parent().parent();

	let data = "id=" + onEdit.data("id") + "&user=" + current_user_id;
	ajaxDelete(data, function(results) {
		if (results == "true") {
			onEdit.fadeOut("slow", function() {
				onEdit.remove();
				if (ul.children().length < 1) {
					card.slideUp("slow", function(){
						card.remove();
					});
				}
			});
		} else {
			alert("Deletion Error");
		}
	});


});
