let apikey = "";  /*your own api key*/

$(window).on("load", function(event) {
	if (current_user_id != -1 && cityid != 0) {
		updateWeather(cityid);
	}
});

function updateWeather(cityId) {
	$.ajax({
		method: "GET",
		url: "https://api.openweathermap.org/data/2.5/weather",
		data: {
			units: "metric",
			id: cityId,
			appid: apikey
		}
	})
	.done(function(results) {
		let temp = results.main.feels_like + "°C";
		$("#weather p").html(temp);
		$("#weather").css("padding-right", "10px");
		let icon = results.weather[0].icon;
		$("#weather .weathericon").empty();
		if (icon == "01d" || icon == "01n") { // clear sky
			$("#weather .weathericon").prepend("<i class='fas fa-sun'></i>");
		} else if (icon == "02d" || icon == "02n") { // few clouds
			$("#weather .weathericon").prepend("<i class='fas fa-cloud-sun'></i>");
		} else if (icon == "03d" || icon == "03n") { // scattered clouds
			$("#weather .weathericon").prepend("<i class='fas fa-cloud'></i>");
		} else if (icon == "04d" || icon == "04n") { // broken clouds
			$("#weather .weathericon").prepend("<img src='images/weather/clouds.png' height='30'>");
		} else if (icon == "09d" || icon == "09n") { // shower rain
			$("#weather .weathericon").prepend("<i class='fas fa-cloud-rain'></i>");
		} else if (icon == "10d" || icon == "10n") { // rain
			$("#weather .weathericon").prepend("<i class='fas fa-cloud-showers-heavy'></i>");
		} else if (icon == "11d" || icon == "11n") { // thunderstorm
			$("#weather .weathericon").prepend("<img src='images/weather/thunder.png' height='30'>");
		} else if (icon == "13d" || icon == "13n") { // snow
			$("#weather .weathericon").prepend("<i class='far fa-snowflake'></i>");
		} else if (icon == "50d" || icon == "50n") { // mist
			$("#weather .weathericon").prepend("<img src='images/weather/mist.png' height='30'>");
		}

	})
	.fail(function() {
		console.log("ERROR");
	});
}