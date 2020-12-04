let onClick = null;
let prevElement = null;
$("#dcards").on("click", ".card", function(event) {
	if ($(window).width() > 767) {
		if (onClick != null) {
			onClick.removeClass("onClick");
			onClick.find(".complete").css("display", "none");
			onClick.find(".shown").css("display", "block");
		}
		onClick = $(this);
		let first = $(".card").first();
		if (prevElement != null && !$.isEmptyObject(prevElement)) {
			first.insertAfter(prevElement);
			first = $(".card").first();
		}
		prevElement = $(this).prev();
		if ($(this) != first) {
			$(this).insertBefore(first);
		};
		$(this).addClass("onClick");
		$(this).find(".shown").css("display", "none");
		$(this).find(".complete").slideDown();
	} else {
		onClick = $(this);
		$(this).find(".shown").css("display", "none");
		$(this).css("height", "auto");
		$(this).find(".complete").slideDown("slow");
		let top = $(this).position().top - 55;
		$(window).scrollTop(top);
		prevElement = $(this).prev();
		$(this).find(".minimize").addClass("d-flex flex-column justify-content-center");
	}
});

$(window).resize(function(event) {
	if (onClick != null) {
		if ($(window).width() > 767) {
			$(".minimize.d-flex").each(function() {
				let card = $(this).parent().parent();
				card.find(".complete").css("display", "none");
				card.find(".shown").slideDown("slow");
			});
			$(".minimize").removeClass("d-flex flex-column justify-content-center");
			$(".minimize").css("display", "none");
			let first = $(".card").first();
			if (onClick != first) {
				onClick.insertBefore(first);
			}
			onClick.addClass("onClick");
		} else {
			onClick.removeClass("onClick");
			onClick.find(".minimize").addClass("d-flex flex-column justify-content-center");
			if (prevElement != null && !$.isEmptyObject(prevElement)) {
				onClick.insertAfter(prevElement);
			}
		}
	}
});

$("form").on("submit", function(event) {
	event.preventDefault();
	let term = $.trim($(this).find("input").val());
	let data = "term=" + encodeURIComponent(term) + "&visibility=" + vis;
	// simplify, no empty and adding new elements
	// just filter the current displaying cards
	// for new cards added on other devices, refresh the page
	ajaxDiaries(data, function(results) {
		if (isJson(results)) {
			results = JSON.parse(results);
			let searched = [];
			for (let i = 0; i < results.length; i++) {
				searched.push(parseInt(results[i].id), 10);
			}
			let cards = $("#dcards").children().children();
			cards.each(function() {
				if ( searched.includes($(this).data("id")) ) {
					$(this).css("display", "flex");
				} else {
					$(this).css("display", "none");
				}
			});

		} else {
			console.log("error when searching");
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


function ajaxDiaries(postData, returnFunction) {
	let xhr = new XMLHttpRequest();
	xhr.open("POST", "PHP/diaries_board.php", true);
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

$("#dcards").on("click", '.card-header .minimize', function(event) {
	event.stopPropagation();
	let card = $(this).parent().parent();
	onClick = null;
	card.find(".minimize").removeClass("d-flex flex-column justify-content-center");
	card.find(".minimize").css("display", "none");
	card.find(".complete").hide("fast", function() {
		card.find(".complete").css("display", "none");
		card.find(".shown").slideDown("slow", function() {
			card.find(".card-body").css("height", "auto");
		});
	});
	
});

$("#dcards").on("click", ".card-header .like", function(event) {
	event.stopPropagation();
	if (current_user_id != -1) {
		let card = $(this).parent().parent();
		let icon = $(this).find(".fa-heart");
		if (icon.hasClass("far")) {
			let data = "like=yes&id=" + card.data("id") + "&userid=" + current_user_id;
			ajaxDiaries(data, function(results) {
				console.log(results);
				if (isJson(results)) {
					results = JSON.parse(results);
					if (results["success"] == true) {
						icon.next().html(results["count"]);
						icon.removeClass("far");
						icon.addClass("fas");
					} else {
						console.log("error when like");
					}

				}
			});
		} else {
			let data = "like=no&id=" + card.data("id") + "&userid=" + current_user_id;
			ajaxDiaries(data, function(results) {
				console.log(results);
				if (isJson(results)) {
					results = JSON.parse(results);
					if (results["success"] == true) {
						icon.next().html(results["count"]);
						icon.removeClass("fas");
						icon.addClass("far");
					} else {
						console.log("error when unlike");
					}

				}
			});
		}	
	}
});