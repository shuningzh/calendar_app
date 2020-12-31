$(".calendar").on("click", ".day", function(event) {
	event.preventDefault();
	let title = $("#month1").html() + " " +$(this).children().first().html() + ", " + $("#year1").html();
	if($(window).width() <= 767) {
		if (loaded) {
			if ($(this).hasClass("inactive-day")) {
				if ($(this).children().first().html() < 7) {
					let date = new Date(title);
					date.setMonth(date.getMonth() + 1);
					const ye = new Intl.DateTimeFormat('en', { year: 'numeric' }).format(date);
					const mo = new Intl.DateTimeFormat('en', { month: 'long' }).format(date);
					const da = new Intl.DateTimeFormat('en', { day: '2-digit' }).format(date);
					title = `${mo} ${da}, ${ye}`;
				} else {
					let date = new Date(title);
					date.setMonth(date.getMonth() - 1);
					const ye = new Intl.DateTimeFormat('en', { year: 'numeric' }).format(date);
					const mo = new Intl.DateTimeFormat('en', { month: 'long' }).format(date);
					const da = new Intl.DateTimeFormat('en', { day: '2-digit' }).format(date);
					title = `${mo} ${da}, ${ye}`;
				}
			}
			let height = $("#calendar-wrapper").outerHeight();
			$("#sm-events .calendar-divider").height(height);


			let container = $("#sm-events .content-container");
			let prevdate = new Date(container.find("h3").html());
			if ( !isNaN(prevdate.getTime()) ) {
				let mo = ("0" + (prevdate.getMonth() + 1)).slice(-2);
				let day = ("0" + prevdate.getDate()).slice(-2);
				let prevId = "#" + mo + "-" + day;
				let prevDay = $(prevId);
				let events = $("#sm-events").find(".events");
				events.css("display", "none");
				events.children().not(".more").css("display", "none");
				events.detach().appendTo(prevDay);
				$("#sm-events .content-container").empty();
			}

			$("#sm-events").css("display", "block");


			$("#sm-events .content-container").append("<h3>" + title + "</h3><hr>");
			
			$(this).find(".events").detach().appendTo(container);
			container.find(".events").css("display", "block");
			container.find(".events").children().not(".more").css("display", "block");
			$(".day").removeClass("small-onclick");
			$(this).addClass("small-onclick");

		}
	} else {
		if ( !($(this).hasClass("zoom") || $(this).hasClass("first-row")) ) {
			let selected = $(".zoom, .first-row");
			selected.removeClass("zoom first-row");
			selected.children().css({
				marginTop: "auto",
				height: "auto"
			});
			selected.find(".more").nextAll().css("display", "none");
		}
	}
});

$(".header, #nav").on("click", function() {
	let selected = $(".zoom, .first-row");
	selected.removeClass("zoom first-row");
	selected.children().css({
		marginTop: "auto",
		height: "auto"
	});
	selected.find(".more").nextAll().css("display", "none");
});

$(window).resize(function() {
	if($(window).width() > 767) {
		$("#calendar-wrapper").css({
			position: "static",
			top: 0
		});
		$("#sm-events").css("display", "none");
		$(".day").removeClass("small-onclick");
		let container = $("#sm-events .content-container");
		let prevdate = new Date(container.find("h3").html());
		if ( !isNaN(prevdate.getTime()) ) {
			let mo = ("0" + (prevdate.getMonth() + 1)).slice(-2);
			let day = ("0" + prevdate.getDate()).slice(-2);
			let prevId = "#" + mo + "-" + day;
			let prevDay = $(prevId);
			let events = $("#sm-events").find(".events");
			events.css("display", "none");
			events.children().not(".more").css("display", "none");
			events.detach().appendTo(prevDay);
			$("#sm-events .content-container").empty();
		}



		let numOfRows = $(".calendar").children(".day").length / 7;
		let total = $(window).height()-$("#nav").outerHeight(true)-$(".navbar").outerHeight(true)-$(".header").outerHeight(true);
		let gridHeight = total/numOfRows;
		$(".day").outerHeight(gridHeight);
		let limit = gridHeight - $(".day").children().first().height();
		$(".day").each(function() {
			let d = new Date($(this).attr("id") + " " + $("#year1").html());
			updateEllipsis("#" + $(this).attr("id"), d, limit);
			$(this).find(".events").slideDown('slow');
		});

	} else {
		$(".day").each(function() {
			$(this).find(".events").css("display", "none");
		});
		$(".day").outerHeight(35);
		$(".day").removeClass("zoom first-row");
		$(".day").children().css({
			marginTop: "auto",
			height: "auto"
		});
		let top = $("#nav").outerHeight();
		$("#calendar-wrapper").css({
			"position": "fixed",
			"left": "0",
			"top": top
		});
		let height = $("#calendar-wrapper").outerHeight();
		$("#sm-events .calendar-divider").height(height);
		$("#sm-events .events").children().not(".more").css("display", "block");
	}
});

$(".calendar, #sm-events").on("click", ".events div" , function() {
	let icon = $(this).find("i");
	let el = $(this).find("small");
	if (icon.hasClass("fa-square")) {
		let reminderid = $(this).data("id");
		let data = "id=" + reminderid + "&status=1&user=" + current_user_id;
		ajaxMarkReminder(data, function(results) {
			if (results == "true") {
				el.addClass("crossed");
				icon.removeClass("fa-square");
				icon.addClass("fa-check-square");
			}
		});
	} else if (icon.hasClass("fa-check-square")) {
		let reminderid = $(this).data("id");
		let data = "id=" + reminderid + "&status=0&user=" + current_user_id;
		ajaxMarkReminder(data, function(results) {
			if (results == "true") {
				el.removeClass("crossed");
				icon.removeClass("fa-check-square");
				icon.addClass("fa-square");
			}
		});
	} else if (!$(this).hasClass("more")) {
		let event = $(this);
		let form = $("#display-detail form");
		form.find("#dtitle").val(event.find(".event-title").text());
		form.find("#dlocation").val(event.data("location"));
		form.find("#dstart").val(event.data("start").replace(/\s/g, 'T'));
		form.find("#dend").val(event.data("end").replace(/\s/g, 'T'));
		form.find("#dnotes").val(event.data("notes"));
		form.find("#eventid").val(event.data("id"));

		let noteslen = $("#dnotes").val().length;
		$("#dnotes-char-num").text(noteslen);
		$("#dnotes-remain-char").text(Math.max(0, 1000 - noteslen));
		$("#dnotes-char-num").addClass("text-muted");
		$("#dnotes-char-num").prev().addClass("text-muted");
		$("#dnotes-char-num").removeClass("text-danger");
		$("#dnotes-char-num").prev().removeClass("text-danger");

		let titlelen = $("#dtitle").val().length;
		$("#dtitle-char-num").text(titlelen);
		$("#dtitle-remain-char").text(Math.max(0, 100 - titlelen));
		$("#dtitle-char-num").addClass("text-muted");
		$("#dtitle-char-num").prev().addClass("text-muted");
		$("#dtitle-char-num").removeClass("text-danger");
		$("#dtitle-char-num").prev().removeClass("text-danger");

		let loclen = $("#dlocation").val().length;
		$("#dlocation").next().children().text(loclen);
		$("#dlocation").next().addClass("text-muted");
		$("#dlocation").next().removeClass("text-danger");

		$("#display-detail").fadeIn("slow");
		$("body").css("overflow", "hidden");
	}
});

$("#dtitle").on("input", function() {
	let len = $(this).val().length;
	$("#dtitle-char-num").text(len);
	if (len > 100) {
		$("#dtitle-remain-char").text(0);
		$("#dtitle-char-num").addClass("text-danger");
		$("#dtitle-char-num").prev().addClass("text-danger");
		$("#dtitle-char-num").removeClass("text-muted");
		$("#dtitle-char-num").prev().removeClass("text-muted");
	} else {
		$("#dtitle-remain-char").text(100 - len);
		$("#dtitle-char-num").addClass("text-muted");
		$("#dtitle-char-num").prev().addClass("text-muted");
		$("#dtitle-char-num").removeClass("text-danger");
		$("#dtitle-char-num").prev().removeClass("text-danger");
	}
});

$("#dnotes").on("input", function() {
	let len = $(this).val().length;
	$("#dnotes-char-num").text(len);
	if (len > 1000) {
		$("#dnotes-remain-char").text(0);
		$("#dnotes-char-num").addClass("text-danger");
		$("#dnotes-char-num").prev().addClass("text-danger");
		$("#dnotes-char-num").removeClass("text-muted");
		$("#dnotes-char-num").prev().removeClass("text-muted");
	} else {
		$("#dnotes-remain-char").text(1000 - len);
		$("#dnotes-char-num").addClass("text-muted");
		$("#dnotes-char-num").prev().addClass("text-muted");
		$("#dnotes-char-num").removeClass("text-danger");
		$("#dnotes-char-num").prev().removeClass("text-danger");
	}
});

function ajaxMarkReminder(postData, returnFunction) {
	let xhr = new XMLHttpRequest();
	xhr.open("POST", "PHP/calendar_reminder.php", true);
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

$(".circle-add").on("click", function() {
	$("#notes-char-num").text($("#notes").val().length);
	$("#notes-remain-char").text(Math.max(0, 1000 - $("#notes").val().length));
	$("#title-char-num").text($("#title").val().length);
	$("#title-remain-char").text(Math.max(0, 100 - $("#title").val().length));
	$("#location + small span").text($("#location").val().length);

	$(".form-wrapper").fadeIn("slow");
	$("body").css("overflow", "hidden");
	$(".day").removeClass("zoom first-row");
	$(".day").children().css({
		marginTop: "auto",
		height: "auto"
	});
	$(".day").find(".more").nextAll().css("display", "none");
	if ( $("#start").val() == "" && $("#end").val() == "" ) {
		let start = new Date();
		start.setHours(start.getHours()+1);
		let end = new Date(start.getTime());
		end.setHours(end.getHours()+1);
		// format date
		let ye = new Intl.DateTimeFormat('en', { year: 'numeric' }).format(start);
		let mo = new Intl.DateTimeFormat('en', { month: '2-digit' }).format(start);
		let da = new Intl.DateTimeFormat('en', { day: '2-digit' }).format(start);
		let shr = new Intl.DateTimeFormat('en', { hour: '2-digit', hour12: false }).format(start);
		let ehr = new Intl.DateTimeFormat('en', { hour: '2-digit', hour12: false }).format(end);
		let startStr = `${ye}-${mo}-${da}T${shr}:00`;
		let endStr = `${ye}-${mo}-${da}T${ehr}:00`;
		$("#start").val(startStr);
		$("#end").val(endStr);
	}
});

$(".form-wrapper .close").on("click", function() {
	$(".form-wrapper").fadeOut("slow");
	$("body").css("overflow", "visible");
});

$("#display-detail .close").on("click", function() {
	$("#display-detail").fadeOut("slow");
	$("body").css("overflow", "visible");
});

$(".wrapper").on("click", function(event) {
	event.stopPropagation();
});

$(".form-wrapper").on("click", function() {
	$(".form-wrapper").fadeOut("slow");
	$("body").css("overflow", "visible");
});

$("#display-detail").on("click", function() {
	$("#display-detail").fadeOut("slow");
	$("body").css("overflow", "visible");
});

$("#title").on("input", function() {
	let len = $(this).val().length;
	$("#title-char-num").text(len);
	if (len > 100) {
		$("#title-remain-char").text(0);
		$("#title-char-num").addClass("text-danger");
		$("#title-char-num").prev().addClass("text-danger");
		$("#title-char-num").removeClass("text-muted");
		$("#title-char-num").prev().removeClass("text-muted");
	} else {
		$("#title-remain-char").text(100 - len);
		$("#title-char-num").addClass("text-muted");
		$("#title-char-num").prev().addClass("text-muted");
		$("#title-char-num").removeClass("text-danger");
		$("#title-char-num").prev().removeClass("text-danger");
	}
});

$("#location, #dlocation").on("input", function() {
	let len = $(this).val().length;
	let limit = $(this).next();
	limit.children().text(len);
	if (len > 256) {
		limit.addClass("text-danger");
		limit.removeClass("text-muted");
	} else {
		limit.addClass("text-muted");
		limit.removeClass("text-danger");
	}
});

$("#notes").on("input", function() {
	let len = $(this).val().length;
	$("#notes-char-num").text(len);
	if (len > 1000) {
		$("#notes-remain-char").text(0);
		$("#notes-char-num").addClass("text-danger");
		$("#notes-char-num").prev().addClass("text-danger");
		$("#notes-char-num").removeClass("text-muted");
		$("#notes-char-num").prev().removeClass("text-muted");
	} else {
		$("#notes-remain-char").text(1000 - len);
		$("#notes-char-num").addClass("text-muted");
		$("#notes-char-num").prev().addClass("text-muted");
		$("#notes-char-num").removeClass("text-danger");
		$("#notes-char-num").prev().removeClass("text-danger");
	}
});

$(".form-wrapper .wrapper form").on("submit", function(event) {
	event.preventDefault();
	let title = $.trim($("#title").val());
	$("#title").val(title);
	let location = $("#location").val();
	let start = $("#start").val();
	let end = $("#end").val();
	let notes = $("#notes").val();
	if (title.length == 0) {
		$("#title").next().next().css("display", "block");
	} else if (title.length > 100) {
		$("#title").next().next().css("display", "none");
		return;
	} else {
		$("#title").next().next().css("display", "none");
	}

	if (location.length > 256 || notes.length > 1000) {
		return;
	}
	
	let s = new Date(start);
	let e = new Date(end);
	if ( isNaN(s.getTime()) ) {
		$("#start + .error").css("display", "block");
	} else {
		$("#start + .error").css("display", "none");
	}
	if ( isNaN(e.getTime()) ) {
		$("#end + .error").css("display", "block");
	} else {
		$("#end + .error").css("display", "none");
	}
	if ( s.getTime() > e.getTime() ) {
		$("#start + .error").css("display", "block");
		$("#end + .error").css("display", "block");
	}
	if ( $("#title").next().next().css("display") == "none" && $("#start + .error").css("display") == "none" &&  $("#end + .error").css("display") == "none") {
		let data = "title=" + encodeURIComponent(title) + "&location=" + encodeURIComponent(location) + "&start=" + start + "&end=" + end + "&notes=" + encodeURIComponent(notes) + "&user=" + current_user_id;
		ajaxCreateEvent(data, function(results) {
			if (isJson(results)) {
				results = JSON.parse(results);
				if (results.success == true) {
					let startStr = $("#year1").html() + "-" + $(".day").first().attr("id");
					let monthStart = new Date(startStr + "T00:00:00");
					if ($("#month1").html() == "January" && monthStart.getMonth() == 11) {
						monthStart.setFullYear(monthStart.getFullYear() - 1);
					}
					let endStr = $("#year1").html() + "-" + $(".day").last().attr("id");
					let monthEnd = new Date(endStr + "T00:00:00");
					if ($("#month1").html() == "December" && monthEnd.getMonth() == 0) {
						monthEnd.setFullYear(monthEnd.getFullYear() + 1);
					}

					if ( (s.getTime() >= monthStart.getTime() && s.getTime() <= monthEnd.getTime()) || (e.getTime() >= monthStart.getTime() && e.getTime() <= monthEnd.getTime()) ) {
						let eventid = results.id;
						displayNewEvent(title, s, e, eventid, location, notes);
					}
					$("body").css("overflow", "visible");
					$(".form-wrapper").fadeOut("slow", function() {
						$(".form-wrapper .wrapper form")[0].reset();
					});
				}
			}
		});
	}
});


$("#display-detail .wrapper form").on("submit", function(event) {
	event.preventDefault();

	let title = $.trim($("#dtitle").val());
	$("#dtitle").val(title);
	let location = $("#dlocation").val();
	let start = $("#dstart").val();
	let end = $("#dend").val();
	let notes = $("#dnotes").val();
	if (title.length == 0) {
		$("#dtitle").next().next().css("display", "block");
	} else if (title.length > 100) {
		$("#dtitle").next().next().css("display", "none");
		return;
	} else {
		$("#dtitle").next().next().css("display", "none");
	}

	if (location.length > 256 || notes.length > 1000) {
		return;
	}
	
	let s = new Date(start);
	let e = new Date(end);
	if ( isNaN(s.getTime()) ) {
		$("#dstart + .error").css("display", "block");
	} else {
		$("#dstart + .error").css("display", "none");
	}
	if ( isNaN(e.getTime()) ) {
		$("#dend + .error").css("display", "block");
	} else {
		$("#dend + .error").css("display", "none");
	}
	if ( s.getTime() > e.getTime() ) {
		$("#dstart + .error").css("display", "block");
		$("#dend + .error").css("display", "block");
	}
	if ( $("#dtitle").next().next().css("display") == "none" && $("#dstart + .error").css("display") == "none" &&  $("#dend + .error").css("display") == "none") {
		let data = "title=" + encodeURIComponent(title) + "&location=" + encodeURIComponent(location) + "&start=" + start + "&end=" + end + "&notes=" + encodeURIComponent(notes) + "&user=" + current_user_id + "&eventid=" + $("#eventid").val();
		ajaxUpdateEvent(data, function(results) {
			if (results == "true") {
				$(".e" + $("#eventid").val()).remove();
				displayNewEvent(title, s, e, $("#eventid").val(), location, notes);
				$("#display-detail .wrapper form")[0].reset;
				$("body").css("overflow", "visible");
				$("#display-detail").fadeOut("slow");
			} else {
				if (results == "0") {
					console.log("Nothing changed!");
					$("#display-detail .wrapper form")[0].reset;
					$("body").css("overflow", "visible");
					$("#display-detail").fadeOut("slow");
				} else {
					alert("Error!");
				}
			}
		});
	}


});

function displayNewEvent(title, start, end, id, location, notes) {
	let now = new Date();
	let eventid = "e" + id;
	let startTime = start.getHours() + ":" + ("0" + start.getMinutes()).slice(-2);
	let sid = "#" + ( "0" + (start.getMonth() + 1) ).slice(-2) + "-" + ( "0" + start.getDate() ).slice(-2);

	let ye = new Intl.DateTimeFormat('en', { year: 'numeric' }).format(start);
	let mo = new Intl.DateTimeFormat('en', { month: '2-digit' }).format(start);
	let da = new Intl.DateTimeFormat('en', { day: '2-digit' }).format(start);
	let hr = new Intl.DateTimeFormat('en', { hour: '2-digit', hour12: false }).format(start);
	let mi = ( "0" + start.getMinutes() ).slice(-2);
	let startStr = `${ye}-${mo}-${da} ` + hr + ":" + mi + ":00";
	ye = new Intl.DateTimeFormat('en', { year: 'numeric' }).format(end);
	mo = new Intl.DateTimeFormat('en', { month: '2-digit' }).format(end);
	da = new Intl.DateTimeFormat('en', { day: '2-digit' }).format(end);
	hr = new Intl.DateTimeFormat('en', { hour: '2-digit', hour12: false }).format(end);
	mi = ( "0" + end.getMinutes() ).slice(-2);
	let endStr = `${ye}-${mo}-${da} ` + hr + ":" + mi + ":00";

	let limit = $(sid).height() - $(sid).children().first().height();
	let newElement = "<div class='" + eventid + "' data-id='" + id + "' data-location='" + location + "' data-notes='" + notes + "' data-start='" + startStr + "' data-end='" + endStr + "'><small class='d-flex flex-row justify-content-start'><div class='d-flex flex-column justify-content-center'><i class='far fa-circle'></i></div><span class='event-title'>" + title.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;') + "</span><span class='flex-grow-1'></span><span class='time'>" + startTime + "</span></small></div>";
	let position = null;
	if (end.getTime() > now.getTime()) {
		let events = $(sid).find(".events").children();
		if ($(sid).hasClass("small-onclick")) {
			events = $(".content-container").find(".events").children();
		}
		events.each(function() {
			let str = $(this).data("start");
			if ( str <= startStr || ($(this).find(".fa-square").length != 0 || $(this).find(".fa-check-square").length != 0) ) {
				position = $(this);
			}
		});

	} else {
		newElement = "<div class='" + eventid + " finished' data-id='" + id + "' data-location='" + location + "' data-notes='" + notes + "' data-start='" + startStr + "' data-end='" + endStr + "'><small class='d-flex flex-row justify-content-start'><div class='d-flex flex-column justify-content-center'><i class='fas fa-circle'></i></div><span class='event-title'>" + title.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;') + "</span><span class='flex-grow-1'></span><span class='time'>" + startTime + "</span></small></div>";
		let events = $(sid).find(".events").children();
		if ($(sid).hasClass("small-onclick")) {
			events = $(".content-container").find(".events").children();
		}
		events.each(function() {
			let str = $(this).data("start");
			if ( str <= startStr || ($(this).find(".fa-square").length != 0 || $(this).find(".fa-check-square").length != 0) ) {
				position = $(this);
			}
		});
	}
	if (position == null) {
		if ($(sid).hasClass("small-onclick")) {
			$(".content-container .events").prepend(newElement);
			$(".content-container .events").find("." + eventid).css("display", "block");
		} else {
			$(sid).find(".events").prepend(newElement);
		}
	} else {
		$(newElement).insertAfter(position);
		if ($(sid).hasClass("small-onclick")) {
			position.next().css("display", "block");
		}
	}

	updateEllipsis(sid, start, limit);
	if ( ( end.getMonth() > start.getMonth() ) || ( end.getDate() > start.getDate() && end.getMonth() == start.getMonth() ) ) {
		let temp = new Date(start.getTime());
		temp.setDate(temp.getDate() + 1);
		temp.setHours(0,0,0);
		while (temp.getTime() <= start.getTime()) {
			temp.setDate(temp.getDate() + 1);
		}
		while ( temp.getTime() <= end.getTime() ) {
			let dayid = "#" + ("0" + (temp.getMonth() + 1)).slice(-2) + "-" + ("0" + temp.getDate()).slice(-2);
			limit = $(dayid).height() - $(dayid).children().first().height();
			let div = "<div class='" + eventid + "'  data-id='" + id + "' data-location='" + location + "' data-notes='" + notes + "' data-start='" + startStr + "' data-end='" + endStr + "'><small class='d-flex flex-row justify-content-start'><div class='d-flex flex-column justify-content-center'><i class='far fa-circle'></i></div><span class='event-title'>" + title.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;') + "</span><span class='flex-grow-1'></span></small></div>";
			let newPosition = null;
			if (end.getTime() > now.getTime()) {
				let events = $(dayid).find(".events").children();
				if ($(dayid).hasClass("small-onclick")) {
					events = $(".content-container").find(".events").children();
				}
				events.each(function() {
					let str = $(this).data("start");
					if ( str <= startStr || ($(this).find(".fa-square").length != 0 || $(this).find(".fa-check-square").length != 0) ) {
						newPosition = $(this);
					}
				});
			} else {
				div = "<div class='" + eventid + " finished'  data-id='" + id + "' data-location='" + location + "' data-notes='" + notes + "' data-start='" + startStr + "' data-end='" + endStr + "'><small class='d-flex flex-row justify-content-start'><div class='d-flex flex-column justify-content-center'><i class='fas fa-circle'></i></div><span class='event-title'>" + title.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;') + "</span><span class='flex-grow-1'></span></small></div>";
				let events = $(dayid).find(".events").children();
				if ($(dayid).hasClass("small-onclick")) {
					events = $(".content-container").find(".events").children();
				}
				events.each(function() {
					let str = $(this).data("start");
					if ( str <= startStr || ($(this).find(".fa-square").length != 0 || $(this).find(".fa-check-square").length != 0) ) {
						newPosition = $(this);
					}
				});
			}
			if (newPosition == null) {
				if ($(dayid).hasClass("small-onclick")) {
					if ($(".content-container .events").find(".fa-square").length == 0 || $(".content-container .events").find(".fa-check-square").length == 0) {
						$(".content-container .events").prepend(div);
					} else {
						let pos = $(".content-container .events").children().first();
						$(".content-container .events").children().each(function() {
							if ($(this).hasClass(".fa-square") || $(this).hasClass(".fa-check-square")) {
								pos = $(this);
							} else {
								return false;
							}
						});
						pos.insertAfter(div);
					}
					$(".content-container .events").find("." + eventid).css("display", "block");
				} else {

					if ($(dayid).find(".fa-square").length == 0 || $(dayid).find(".fa-check-square").length == 0) {
						$(dayid).find(".events").prepend(div);
					} else {
						let pos = $(dayid).children().first();
						$(dayid).children().each(function() {
							if ($(this).hasClass(".fa-square") || $(this).hasClass(".fa-check-square")) {
								pos = $(this);
							} else {
								return false;
							}
						});
						pos.insertAfter(div);
					}
				}
			} else {
				$(div).insertAfter(newPosition);
				if ($(dayid).hasClass("small-onclick")) {
					newPosition.next().css("display", "block");
				}
			}
			let nlength = $(dayid).find(".events").children().length * $(dayid).find(".events").children().first().height();
			if (nlength < limit) {
				$(dayid).find(".events ." + eventid).css("display", "block");
			} else {
				updateEllipsis(dayid, temp, limit);
			}
			temp.setDate(temp.getDate() + 1);
		}
	}
}

$("#delete-btn").on("click", function() {
	let eventid = $("#eventid").val();
	let data = "title=&location=&start=&end=&notes=&user=" + current_user_id + "&eventid=" + eventid;
	ajaxUpdateEvent(data, function(results) {
		if (results == "true") {
			$(".e" + $("#eventid").val()).remove();
			$(".day").each(function() {
				let date = new Date($("#year1").html() + "-" + $(this).attr("id"));
				if ($("#month1").html() == "December" && date.getMonth() == 0) {
					date.setFullYear(date.getFullYear() + 1);
				}
				updateEllipsis("#" + $(this).attr("id"), date);
			});
			$("#display-detail .wrapper form")[0].reset;
			$("body").css("overflow", "visible");
			$("#display-detail").fadeOut("slow");
		} else {
			if (results == "0") {
				alert("Nothing deleted!");
				$("#display-detail .wrapper form")[0].reset;
				$("body").css("overflow", "visible");
				$("#display-detail").fadeOut("slow");
			} else {
				alert("Error!");
			}
		}
	});
});

function ajaxUpdateEvent(postData, returnFunction) {
	let xhr = new XMLHttpRequest();
	xhr.open("POST", "PHP/calendar_update_event.php", true);
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



function ajaxCreateEvent(postData, returnFunction) {
	let xhr = new XMLHttpRequest();
	xhr.open("POST", "PHP/calendar_create_event.php", true);
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

$(".calendar").on("click", ".more", function(event) {
	event.stopPropagation();
	$(".day").removeClass("zoom first-row");
	$(".day").children().css({
		marginTop: "auto",
		height: "auto"
	});
	$(".day").find(".more").nextAll().css("display", "none");
	let day = $(this).parent().parent();
	let total = day.height();
	let dateHeight = day.children().first().height();
	let eventHeight = day.find(".events").height();
	let gap = total - dateHeight - eventHeight;
	if (day.index() <= 13) {
		day.addClass("first-row");
	} else {
		day.addClass("zoom");
	}
	day.children().first().css('margin-top', 0 - dateHeight/4);
	day.find(".events").css({
		marginTop: 0 - dateHeight / 2 -  eventHeight / 2,
		height: total * 2 - dateHeight - gap
	});
	day.find(".more").nextAll().css("display", "block");

});
