$(window).on("load", function(event) {
	let date = new Date();
	$(".card").each(function(index, el) {
		let edit = new Date($(this).data("edit").replace(/\s/, 'T'));
		// diff in seconds
		let diff = Math.floor( (date.getTime() - edit.getTime()) / 1000 );
		if ( (diff / 60) >= 1 ) {
			diff = Math.floor(diff / 60);  // in min
			if ( (diff / 60) >= 1 ) {
				diff = Math.floor(diff / 60);  // in hours
				if ( (diff / 24) >= 1 ) {
					diff = Math.floor(diff / 24);  // in days
					if ( (diff / 30.436875) >= 1 ) {
						diff = Math.floor(diff / 30.436875); // in months
						if ( (diff / 12) >= 1 ) {
							diff = Math.floor(diff / 12);  // in years
							diff += " years ago";
						} else {
							diff += " months ago";
						}
					} else {
						diff += " days ago";
					}
				} else {
					diff += " hours ago";
				}
			} else {
				diff += " minutes ago";
			}
		} else {
			diff += " seconds ago";
		}
		
		$(this).find("small").first().html("Last updated: " + diff);
	});
});

$("#new-btn").on("click", function(event) {
	$(".new-diary-wrapper").fadeIn("slow");
	$("body").css("overflow", "hidden");
});

$(".close").on("click", function(event) {
	$(".new-diary-wrapper").fadeOut("slow", function() {
		$("#title").val("");
		$("#content").val("");
		$("#title").next().next().css("display", "none");

		$("#title-char").text(0);
		$("#title-remain").text(100);
		$("#title-char").addClass("text-muted");
		$("#title-char").prev().addClass("text-muted");
		$("#title-char").removeClass("text-danger");
		$("#title-char").prev().removeClass("text-danger");

		$("#content-char").text(0);
		$("#content-remain").text(4000);
		$("#content-char").addClass("text-muted");
		$("#content-char").prev().addClass("text-muted");
		$("#content-char").removeClass("text-danger");
		$("#content-char").prev().removeClass("text-danger");
	});
	$("body").css("overflow", "auto")
});

$("#title").on("input", function() {
	let len = $(this).val().length;
	$("#title-char").text(len);
	if (len != 0) {
		$("#title").next().next().css("display", "none");
	}
	if (len > 100) {
		$("#title-remain").parent().addClass("text-danger");
		$("#title-char").addClass("text-danger");
		$("#title-remain").parent().removeClass("text-muted");
		$("#title-char").removeClass("text-muted");
		$("#title-remain").text(0);
	} else {
		$("#title-remain").parent().addClass("text-muted");
		$("#title-char").addClass("text-muted");
		$("#title-remain").parent().removeClass("text-danger");
		$("#title-char").removeClass("text-danger");
		$("#title-remain").text(100 - len);
	}
});

$("#content").on("input", function() {
	let len = $(this).val().length;
	$("#content-char").text(len);
	if (len > 4000) {
		$("#content-remain").parent().addClass("text-danger");
		$("#content-char").addClass("text-danger");
		$("#content-remain").parent().removeClass("text-muted");
		$("#content-char").removeClass("text-muted");
		$("#content-remain").text(0);
	} else {
		$("#content-remain").parent().addClass("text-muted");
		$("#content-char").addClass("text-muted");
		$("#content-remain").parent().removeClass("text-danger");
		$("#content-char").removeClass("text-danger");
		$("#content-remain").text(4000 - len);
	}
});


$(".diary-form form").on("submit", function(event) {
	event.preventDefault();
	let title = $.trim($("#title").val());
	$("#title").val(title);
	if (title.length == 0) {
		$("#title").next().next().css("display", "block");
	} else if (title.length > 100) {
		return;
	} else {
		$("#title").next().next().css("display", "none");
		let content = $("#content").val();
		if (content.length == 0) {
			if ( !confirm("Are you sure to submit an empty diary?") ) {
				return;
			}
		} else if (content.length > 4000) {
			return;
		}
		let vis = $("input:radio[name='visibility']:checked").val();
		let d = new Date();
		let ye = new Intl.DateTimeFormat('en', { year: 'numeric' }).format(d);
		let mo = new Intl.DateTimeFormat('en', { month: '2-digit' }).format(d);
		let da = new Intl.DateTimeFormat('en', { day: '2-digit' }).format(d);
		let hr = ("0" + d.getHours()).slice(-2);
		let mi = ("0" + d.getMinutes()).slice(-2);
		let se = ("0" + d.getSeconds()).slice(-2);
		let created = `${ye}-${mo}-${da}T` + hr + ":" + mi + ":" + se;

		let data = "order=insert&title=" + encodeURIComponent(title) + "&content=" + encodeURIComponent(content) + "&visibility=" + vis + "&submit=" + created + "&user=" + current_user_id;
		ajaxDiaries(data, function(results) {
			if (isJson(results)) {
				results = JSON.parse(results);
				if (results.success) {
					mo = new Intl.DateTimeFormat('en', { month: 'long' }).format(d);
					da = new Intl.DateTimeFormat('en', { day: 'numeric' }).format(d);
					let display = `${mo} ${da}, ${ye}`;
					let newDiary = "<div class='card' data-id='" + results.id + "' data-visibility='" + vis + "' data-edit='" + created + "'><img src='images/diary.jpg' class='card-img' alt='diaryImg'><div class='card-img-overlay d-flex flex-column justify-content-center'><h5 class='card-title text-center'>" + title.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;') + "</h5><p class='card-text'>" + content.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;') + "</p><div class='d-flex flex-row'><small class='card-text text-muted flex-grow-1'>Last updated: 0 seconds ago</small><small class='card-text text-muted'>Created: " + display + "</small></div><div class='d-flex flex-row button-group align-bottom'><a href='#' type='button' class='card-link btn btn-outline-info'>View Detail</a><a href='#' type='button' class='card-link btn btn-outline-danger'>Delete</a></div></div></div>";
					$(".wrapper").prepend(newDiary);
					$(".card").css("display", "flex");
					$(".dsearch input").val("");
					$("#title").val("");
					$("#content").val("");
					$(".new-diary-wrapper").fadeOut("slow");
					$("body").css("overflow", "auto");
					$("input[name=visibility]").prop("checked", false);
					$("input[name=visibility][value='0']").prop("checked", true);
					$("#title-char").text(0);
					$("#title-remain").text(100);
					$("#title-char").addClass("text-muted");
					$("#title-char").prev().addClass("text-muted");
					$("#title-char").removeClass("text-danger");
					$("#title-char").prev().removeClass("text-danger");
					$("#content-char").text(0);
					$("#content-remain").text(4000);
					$("#content-char").addClass("text-muted");
					$("#content-char").prev().addClass("text-muted");
					$("#content-char").removeClass("text-danger");
					$("#content-char").prev().removeClass("text-danger");
				} else {
					alert("error");
				}
			}
		});

	}
	
});

function isJson(json) {
	try {
		JSON.parse(json);
	} catch(e) {
		return false;
	}
	return true;
}

let onEdit = null;
$(".wrapper").on("click", ".card .btn-outline-info", function(event) {
	$("body").css("overflow", "hidden");
	onEdit = $(this).parent().parent().parent();
	$("#view-title").text(onEdit.find(".card-title").text());
	$("#view-content").text(onEdit.find(".card-title + .card-text").text());
	if (onEdit.data("visibility") == "0") {
		$("#view-visibility").html("Public");
	} else if (onEdit.data("visibility") == "1") {
		$("#view-visibility").html("Protected");
	} else {
		$("#view-visibility").html("Private");
	}
	if ($(window).width() < 767) {
		$("#diary-detail").animate({height:'toggle'}, "slow");
	} else {
		$("#diary-detail").animate({height:'toggle'}, 900);
	}
});

$(".wrapper").on("click", ".card .btn-outline-danger", function(event) {
	let card = $(this).parent().parent().parent();
	let data = "order=delete&id=" + card.data("id") + "&user=" + current_user_id;
	ajaxDiaries(data, function(results) {
		if (results == "true") {
			card.fadeOut('slow', function() {
				card.remove();
			});
		} else {
			console.log(results);
		}
	});
});

$(".cancel-btn").on("click", function(event) {
	$("body").css("overflow", "auto");
	$("#diary-detail").animate({height:'toggle'}, "slow", function() {
		$("#view-edit").show();
		$("#edit-detail").hide();
		$("#title-detail-remain").parent().addClass("text-muted");
		$("#title-detail-char").addClass("text-muted");
		$("#title-detail-remain").parent().removeClass("text-danger");
		$("#title-detail-char").removeClass("text-danger");
	});


});

$("#edit-btn").on("click", function(event) {
	$("#detail-title").val($("#view-title").text());
	$("input[name=visibility2][value=" + onEdit.data("visibility") + "]").prop("checked", "checked");
	$("#detail-content").val($("#view-content").text());
	$("#title-detail-char").text($("#view-title").text().length);
	$("#title-detail-remain").text(100 - $("#view-title").text().length);

	$("#remain-char").text(4000 - $("#view-content").text().length);
	$("#char-num").text($("#view-content").text().length);
	$("#detail-content").next().children().addClass("text-muted");
	$("#detail-content").next().children().removeClass("text-danger");
	$("#view-edit").fadeOut("slow", function() {
		$("#edit-detail").fadeIn("slow");
	});
});

$("#detail-title").on("input", function() {
	let len = $(this).val().length;
	$("#title-detail-char").text(len);
	if (len > 100) {
		$("#title-detail-remain").parent().addClass("text-danger");
		$("#title-detail-char").addClass("text-danger");
		$("#title-detail-remain").parent().removeClass("text-muted");
		$("#title-detail-char").removeClass("text-muted");
		$("#title-detail-remain").text(0);
	} else {
		$("#title-detail-remain").parent().addClass("text-muted");
		$("#title-detail-char").addClass("text-muted");
		$("#title-detail-remain").parent().removeClass("text-danger");
		$("#title-detail-char").removeClass("text-danger");
		$("#title-detail-remain").text(100 - len);
	}
});

$("#edit-detail").on("submit", function(event) {
	event.preventDefault();
	let title = $.trim($("#detail-title").val());
	$("#detail-title").val(title);
	if (title.length == 0) {
		alert("Title cannot be empty");
	} else if (title.length > 100) {
		return;
	} else {
		let content = $("#detail-content").val();
		let vis = $("input:radio[name='visibility2']:checked").val();
		if (content.length == 0) {
			if ( !confirm("Are you sure to submit an empty diary?") ) {
				return;
			}
		} else if (content.length > 4000) {
			return;
		}
		let date = new Date();
		let ye = new Intl.DateTimeFormat('en', { year: 'numeric' }).format(date);
		let mo = new Intl.DateTimeFormat('en', { month: '2-digit' }).format(date);
		let da = new Intl.DateTimeFormat('en', { day: '2-digit' }).format(date);
		let hr = ("0" + date.getHours()).slice(-2);
		let mi = ("0" + date.getMinutes()).slice(-2);
		let se = ("0" + date.getSeconds()).slice(-2);
		let time = `${ye}-${mo}-${da}T` + hr + ":" + mi + ":" + se;
		let data = "order=update&title=" + encodeURIComponent(title) + "&content=" + encodeURIComponent(content) + "&visibility=" + vis + "&id=" + onEdit.data("id") + "&edit=" + time + "&user=" + current_user_id;
		ajaxDiaries(data, function(results) {
			if (results == "true") {
				onEdit.find(".card-title").text(title);
				onEdit.find(".card-title + .card-text").text(content);
				onEdit.data("visibility", vis);
				onEdit.data("edit", time);
				onEdit.find("small").first().html("Last updated: 0 seconds ago");
				$("body").css("overflow", "auto");
				if ($(window).width() < 767) {
					$("#diary-detail").animate({height:'toggle'}, "slow", function() {
						$("#view-edit").show();
						$("#edit-detail").hide();
					});
				} else {
					$("#diary-detail").animate({height:'toggle'}, 900, function() {
						$("#view-edit").show();
						$("#edit-detail").hide();
					});
				}
			}
		});
	}
});

$(".dsearch").on("submit", function(event) {
	event.preventDefault();
	let input = $.trim($(this).find("input").val());
	if (input.length == 0) {
		$(".card").css("display", "flex");
	} else {
		let data = "order=search&term=" + encodeURIComponent(input) + "&user=" + current_user_id;
		ajaxDiaries(data, function(results) {
			if (isJson(results)) {
				results = JSON.parse(results);
				$(".card").each(function(index, el) {
					let id = $(this).data("id");
					if (results.includes(id)) {
						$(this).css("display", "flex");
					} else {
						$(this).css("display", "none");
					}
				});
			} else {
				console.log("searching error: " + results);
			}
		});
	}
});

function ajaxDiaries(postData, returnFunction) {
	let xhr = new XMLHttpRequest();
	xhr.open("POST", "PHP/diaries.php", true);
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

$("#detail-content").on("input", function() {
	$("#char-num").text($("#detail-content").val().length);
	let len = 4000 - $("#detail-content").val().length;
	let smallElem = $(this).next().children().first();
	if (len >= 0 ) {
		$("#remain-char").text(len);
		smallElem.addClass("text-muted");
		$("#char-num").addClass("text-muted");
		smallElem.removeClass("text-danger");
		$("#char-num").removeClass("text-danger");
	} else {
		$("#remain-char").text(0);
		smallElem.addClass("text-danger");
		$("#char-num").addClass("text-danger");
		smallElem.removeClass("text-muted");
		$("#char-num").removeClass("text-muted");
	}
	
});
