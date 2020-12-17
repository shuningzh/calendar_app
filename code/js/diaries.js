$(window).on("load", function(event) {
	let date = new Date();
	$(".card").each(function(index, el) {
		let edit = new Date($(this).data("edit").replace(/\s/, 'T'));
		let diff = date.getFullYear() - edit.getFullYear();
		if (diff == 0) {
			diff = date.getMonth() - edit.getMonth();
			if (diff == 0) {
				diff = date.getDate() - edit.getDate();
				if (diff == 0) {
					diff = date.getHours() - edit.getHours();
					if (diff == 0) {
						diff = date.getMinutes() - edit.getMinutes();
						if (diff == 0) {
							diff = date.getSeconds() - edit.getSeconds() + " seconds ago";
						}  else {
							diff += " minutes ago";
						}
					} else {
						diff += " hours ago";
					}
				} else {
					diff += " days ago";
				}
			} else {
				diff += " months ago";
			}
		} else {
			diff += " years ago";
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
	});
	$("body").css("overflow", "auto")
});


$(".diary-form form").on("submit", function(event) {
	event.preventDefault();
	let title = $.trim($("#title").val());
	$("#title").val(title);
	if (title.length == 0) {
		$("#title + .error").css("display", "block");
	} else {
		$("#title + .error").css("display", "none");
		let content = $("#content").val();
		if (content.length == 0) {
			if ( !confirm("Are you sure to submit an empty diary?") ) {
				return;
			}
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


});

$("#edit-btn").on("click", function(event) {
	$("#detail-title").val($("#view-title").text());
	$("input[name=visibility2][value=" + onEdit.data("visibility") + "]").prop("checked", "checked");
	$("#detail-content").val($("#view-content").text());
	$("#view-edit").fadeOut("slow", function() {
		$("#edit-detail").fadeIn("slow");
	});
});

$("#edit-detail").on("submit", function(event) {
	event.preventDefault();
	let title = $.trim($("#detail-title").val());
	$("#detail-title").val(title);
	if (title.length == 0) {
		alert("Title cannot be empty");
	} else {
		let content = $("#detail-content").val();
		let vis = $("input:radio[name='visibility2']:checked").val();
		if (content.length == 0) {
			if ( !confirm("Are you sure to submit an empty diary?") ) {
				return;
			}
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
