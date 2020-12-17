let loaded = false;
$(window).on("load", function(event) {
	let date = new Date();
	let ye = new Intl.DateTimeFormat('en', { year: 'numeric' }).format(date);
	let mo = new Intl.DateTimeFormat('en', { month: 'long' }).format(date);
	$("#month1").html(mo);
	$("#month2").html(mo);
	$("#year1").html(ye);
	$("#year2").html(ye);
	$("#lg-month, #sm-month").css("visibility", "visible");
	let arr = displayAll(new Date(mo + " 1, " + ye + " 00:00:00"));
	if($(window).width() > 767) {
		let numOfRows = $(".calendar").children(".day").length / 7;
		let total = $(window).height()-$("#nav").outerHeight(true)-$(".navbar").outerHeight(true)-$(".header").outerHeight(true);
		$(".day").outerHeight(total/numOfRows);
		$("#calendar-wrapper").css({
			"position": "static"
		});
	} else {
		$(".day").outerHeight(35);
		let top = $("#nav").outerHeight();
		$("#calendar-wrapper").css({
			"position": "fixed",
			"left": "0",
			"top": top
		});
		let height = $("#calendar-wrapper").outerHeight();
		$("#sm-events .calendar-divider").height(height);
	}
	let h = $(".navbar-bottom .nav-divider").height();
	$(".navbar-bottom .nav-divider").height(h);
	getReminders(arr[0], arr[1]);
	setInterval(fetchdata, 30000);
});

function fetchdata() {
	let now = new Date();
	let ye = new Intl.DateTimeFormat('en', { year: 'numeric' }).format(now);
	let mo = new Intl.DateTimeFormat('en', { month: '2-digit' }).format(now);
	let da = new Intl.DateTimeFormat('en', { day: '2-digit' }).format(now);
	let hr = ("0" + now.getHours()).slice(-2);
	let mi = ("0" + now.getMinutes()).slice(-2);
	let end = `${ye}-${mo}-${da}T` + hr + ":" + mi + ":00";
	let data = "start=&end=" + end + "&user=" + current_user_id;
	ajaxEvents(data, function(results) {
			if (isJson(results)) {
				results = JSON.parse(results);
				for (let i = 0; i < results.length; i++) {
					$(".e" + results[i].id).addClass("finished");
					let classAttr = ".e" + results[i].id + " .fa-circle";
					$(classAttr).removeClass("far");
					$(classAttr).addClass("fas");
				}
			}

	});
}

function displayAll(date) {
	let startDate = new Date(date.getTime());
	while (startDate.getDay() != 0) {
		startDate.setDate(startDate.getDate() - 1);
	}
	let lastDate = new Date(date.getTime());
	lastDate.setMonth(lastDate.getMonth() + 1);
	lastDate.setDate(lastDate.getDate() - 1);
	while(lastDate.getDay() != 6) {
		lastDate.setDate(lastDate.getDate() + 1);
	}


	let start = new Date(startDate.getTime());

	$(".calendar").children(".day").remove();
	let rowNum = 1;
	let colNum = 1;
	while (startDate.getTime() <= lastDate.getTime()) {
		let row = "row"+rowNum;
		let col = "col"+colNum;
		if (colNum == 7) {
			colNum = 1;
			if (rowNum == 2) {
				rowNum = 1;
			} else {
				rowNum = 2;
			}
		} else {
			colNum++;
		}
		let inactive = "";
		if (startDate.getMonth() != date.getMonth()) {
			inactive = " inactive-day";
		}
		let month = ( "0" + (startDate.getMonth()+1) ).slice(-2);
		let day = ( "0" + (startDate.getDate()) ).slice(-2);
		let newDay = "<div class='"+ row +" "+ col + inactive +" day' id='" + month + "-" + day + "'><div>"+ startDate.getDate() +"</div><div class='events'></div></div>";
		$(".calendar").append(newDay);
		startDate.setDate(startDate.getDate() + 1);
	}
	let arr = [start, lastDate];
	return arr;
}

function isJson(json) {
	try {
		JSON.parse(json);
	} catch(e) {
		return false;
	}
	return true;
}

$(".left").on("click", function(event) {
	loaded = false;
	$("#sm-events").css("display", "none");
	let month = $("#month1").html();
	let year = $("#year1").html();
	let temp = month + " 1, " + year + " 00:00:00";
	let date = new Date(temp);
	date.setMonth(date.getMonth() - 1);
	let arr = displayAll(date);
	let ye = new Intl.DateTimeFormat('en', { year: 'numeric' }).format(date);
	let mo = new Intl.DateTimeFormat('en', { month: 'long' }).format(date);
	$("#month1").html(mo);
	$("#month2").html(mo);
	$("#year1").html(ye);
	$("#year2").html(ye);
	if($(window).width() > 767) {
		let numOfRows = $(".calendar").children(".day").length / 7;
		let total = $(window).height()-$("#nav").outerHeight(true)-$(".navbar").outerHeight(true)-$(".header").outerHeight(true);
		$(".day").outerHeight(total/numOfRows);
	} else {
		$(".day").outerHeight(35);
		let top = $("#nav").outerHeight();
		$("#calendar-wrapper").css({
			"position": "fixed",
			"left": "0",
			"top": top
		});
		let height = $("#calendar-wrapper").outerHeight();
		$("#sm-events .calendar-divider").height(height);
	}
	getReminders(arr[0], arr[1]);
});

$(".right").on("click", function(event) {
	loaded = false;
	$("#sm-events").css("display", "none");
	let month = $("#month1").html();
	let year = $("#year1").html();
	let temp = month + " 1, " + year + " 00:00:00";
	let date = new Date(temp);
	date.setMonth(date.getMonth() + 1);
	let arr = displayAll(date);
	let ye = new Intl.DateTimeFormat('en', { year: 'numeric' }).format(date);
	let mo = new Intl.DateTimeFormat('en', { month: 'long' }).format(date);
	$("#month1").html(mo);
	$("#month2").html(mo);
	$("#year1").html(ye);
	$("#year2").html(ye);
	if($(window).width() > 767) {
		let numOfRows = $(".calendar").children(".day").length / 7;
		let total = $(window).height()-$("#nav").outerHeight(true)-$(".navbar").outerHeight(true)-$(".header").outerHeight(true);
		$(".day").outerHeight(total/numOfRows)
	} else {
		$(".day").outerHeight(35);
		let top = $("#nav").outerHeight();
		$("#calendar-wrapper").css({
			"position": "fixed",
			"left": "0",
			"top": top
		});
		let height = $("#calendar-wrapper").outerHeight();
		$("#sm-events .calendar-divider").height(height);
	}
	getReminders(arr[0], arr[1]);
});

function getReminders(start, end) {
	let ye = new Intl.DateTimeFormat('en', { year: 'numeric' }).format(start);
	let mo = new Intl.DateTimeFormat('en', { month: '2-digit' }).format(start);
	let da = new Intl.DateTimeFormat('en', { day: '2-digit' }).format(start);
	let s = `${ye}-${mo}-${da}`;
	ye = new Intl.DateTimeFormat('en', { year: 'numeric' }).format(end);
	mo = new Intl.DateTimeFormat('en', { month: '2-digit' }).format(end);
	da = new Intl.DateTimeFormat('en', { day: '2-digit' }).format(end);
	let e = `${ye}-${mo}-${da}`;
	let data = "start=" + s + "&end=" + e + "&user=" + current_user_id;
	ajaxReminders(data, function(results) {
		if (isJson(results)) {
			results = JSON.parse(results);
			for (let i = 0; i < results.length; i++) {
				let date = new Date(results[i].deadline + "T00:00:00");
				let title = results[i].title;
				let reminderid = results[i].id;
				let id = "#" + ("0" + (date.getMonth() + 1)).slice(-2) + "-" + ("0" + date.getDate()).slice(-2);
				if (results[i].finished == "0") {
					$(id).find(".events").append("<div class='r" + reminderid + "' data-id='" + reminderid + "'><small class='d-flex flex-row justify-content-start'><div class='d-flex flex-column justify-content-center'><i class='far fa-square'></i></div><span class='event-title'>" + title.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;') + "</span></small></div>");
				} else {
					$(id).find(".events").append("<div class='r" + reminderid + "' data-id='" + reminderid + "'><small class='crossed d-flex flex-row justify-content-start'><div class='d-flex flex-column justify-content-center'><i class='far fa-check-square'></i></div><span class='event-title'>" + title.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;') + "</span></small></div>");
				}

				updateEllipsis(id, date);
			}
		}
		getEvents(start, end);
	});
}



function ajaxReminders(postData, returnFunction) {
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

function updateEllipsis(id, date) {
	let numOfRows = $(".calendar").children(".day").length / 7;
	let total = $(window).height()-$("#nav").outerHeight(true)-$(".navbar").outerHeight(true)-$(".header").outerHeight(true);
	let limit = total / numOfRows - $(id).children().first().height();
	let num = Math.floor(limit / 19.2);
	if ($(id).find(".events").children().not(".more").length > num) {
		let more = $(id).find(".more");
		let now = new Date();
		if (more.length == 0) {
			$(id).find(".events").append("<div class='more'><small class='d-flex flex-row justify-content-start'><div class='d-flex flex-column justify-content-center'><i class='fas fa-ellipsis-h'></i></div><span></span>&nbspMore</small></div>")
			more = $(id).find(".more");
		}
		if (num <= 1 && $(id).find(".events").children().not(".more").length >= 2) {
			$(id).find(".events").prepend(more.detach());
			more.nextAll().css("display", "none");
			more.find("span").html(more.nextAll().length);
		} else {
			let str = ".events > div:nth-child(" + (num - 1) + ")";
			let last = $(id).find(str);
			if (last.is(more)) {
				last = more.next();
			}
			more.detach().insertAfter(last);
			let count = more.nextAll().length;
			if ( count > 1 ) {
				more.find("span").html(count);
				more.prevAll().css("display", "block");
				more.nextAll().css("display", "none");
				if (date.getTime() < now.getTime()) {
					$(id).find(".more").addClass("finished");
				}
			} else {
				more.remove();
				$(id).find(".events").children().not(".more").css("display", "block");
			}
		}


	} else {
		$(id).find(".events .more").remove();
		$(id).find(".events").children().not(".more").css("display", "block");
	}
}

function getEvents(start, end) {
	let ye = new Intl.DateTimeFormat('en', { year: 'numeric' }).format(start);
	let mo = new Intl.DateTimeFormat('en', { month: '2-digit' }).format(start);
	let da = new Intl.DateTimeFormat('en', { day: '2-digit' }).format(start);
	let s = `${ye}-${mo}-${da}T00:00:00`;
	ye = new Intl.DateTimeFormat('en', { year: 'numeric' }).format(end);
	mo = new Intl.DateTimeFormat('en', { month: '2-digit' }).format(end);
	da = new Intl.DateTimeFormat('en', { day: '2-digit' }).format(end);
	let e = `${ye}-${mo}-${da}T23:59:59`;
	let data = "start=" + s + "&end=" + e + "&user=" + current_user_id;
	ajaxEvents(data, function(results) {
		if (isJson(results)) {
			results = JSON.parse(results);
			let now = new Date();
			for (let i = 0; i < results.length; i++) {
				let sdate = new Date(results[i].start_time.replace(/\s/, 'T'));
				let edate = new Date(results[i].end_time.replace(/\s/, 'T'));
				let title = results[i].title;
				let eventid = "e" + results[i].id;
				let startTime = sdate.getHours() + ":" + ("0" + sdate.getMinutes()).slice(-2);
				let sid = "#" + ( "0" +  (sdate.getMonth() + 1) ).slice(-2) + "-" + ( "0" + sdate.getDate() ).slice(-2);
				let limit = $(sid).height() - $(sid).children().first().height();
				if (edate.getTime() > now.getTime()) {
					$(sid).find(".events").append("<div class='" + eventid + "'data-id='" + results[i].id + "' data-start='" + results[i].start_time + "' data-end='" + results[i].end_time + "' data-location='" + results[i].location + "' data-notes='" + results[i].notes + "'><small class='d-flex flex-row justify-content-start'><div class='d-flex flex-column justify-content-center'><i class='far fa-circle'></i></div><span class='event-title'>" + title.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;') + "</span><span class='flex-grow-1'></span><span class='time'>" + startTime + "</span></small></div>");
				} else {
					$(sid).find(".events").append("<div class='" + eventid + " finished' data-id='" + results[i].id + "' data-start='" + results[i].start_time + "' data-end='" + results[i].end_time + "' data-location='" + results[i].location + "' data-notes='" + results[i].notes + "'><small class='d-flex flex-row justify-content-start'><div class='d-flex flex-column justify-content-center'><i class='fas fa-circle'></i></div><span class='event-title'>" + title.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;') + "</span><span class='flex-grow-1'></span><span class='time'>" + startTime + "</span></small></div>");
				}
				if ($(window).width() > 767) {
					updateEllipsis(sid, sdate);
				}
				if ( ( edate.getMonth() > sdate.getMonth() ) || ( edate.getDate() > sdate.getDate() && edate.getMonth() == sdate.getMonth() ) ) {
					let temp = new Date(sdate.getTime());
					temp.setDate(temp.getDate() + 1);
					temp.setHours(0,0,0);
					while (temp.getTime() < start.getTime()) {
						temp.setDate(temp.getDate() + 1);
					}
					while ( temp.getTime() <= end.getTime() && temp.getTime() <= edate.getTime() ) {
						let id = "#" + ( "0" + (temp.getMonth() + 1) ).slice(-2) + "-" + ( "0" + temp.getDate() ).slice(-2);
						limit = $(id).height() - $(id).children().first().height();
						if (edate.getTime() > now.getTime()) {
							$(id).find(".events").append("<div class='" + eventid + "' data-id='" + results[i].id + "' data-start='" + results[i].start_time + "' data-end='" + results[i].end_time + "' data-location='" + results[i].location + "' data-notes='" + results[i].notes + "'><small class='d-flex flex-row justify-content-start'><div class='d-flex flex-column justify-content-center'><i class='far fa-circle'></i></div><span class='event-title'>" + title.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;') + "</span><span class='flex-grow-1'></span></small></div>");
						} else {
							$(id).find(".events").append("<div class='" + eventid + " finished' data-id='" + results[i].id + "' data-start='" + results[i].start_time + "' data-end='" + results[i].end_time + "' data-location='" + results[i].location + "' data-notes='" + results[i].notes + "'><small class='d-flex flex-row justify-content-start'><div class='d-flex flex-column justify-content-center'><i class='fas fa-circle'></i></div><span class='event-title'>" + title.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;') + "</span><span class='flex-grow-1'></span></small></div>");
						}
						updateEllipsis(id, temp);
						temp.setDate(temp.getDate() + 1);
					}
				}
			}
		}
		loaded = true;
		
	});
}

function ajaxEvents(postData, returnFunction) {
	let xhr = new XMLHttpRequest();
	xhr.open("POST", "PHP/calendar_event.php", true);
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