# Software Requirements
1. [MAMP](https://www.mamp.info/en/downloads/)
2. [MySQL Workbench](https://dev.mysql.com/downloads/workbench/)
3. Web Browser

# Instructions
1. Import MySQL database from `db.sql` file.
	1. If the city you want for locations is not inserted in `db.sql`, you can try to find the corresponding cityid in `cities.json` and run this SQL statement: `INSERT INTO locations (cityid, cityname, state, country) VALUES (?, ?, ?, ?)`.
	2. If you cannot find your city in `cities.json`, check out [this](http://bulk.openweathermap.org/sample/) from [OpenWeather API](https://openweathermap.org/current#cityid).
2. Modify `code/js/weather.js`.
	1. Register an account and generate an api key at [OpenWeather API](https://home.openweathermap.org/users/sign_up).
	2. Paste your own api key inside the quotation marks.
	3. If the city you want is not inserted in step 1, and you don't want to insert this to the database, you can find the cityid of the city you want and replace `cityid` on line 5 in `weather.js` with the id you find.
2. Modify `code/config/config.php`
	1. For Windows, check out [this page](https://stackoverflow.com/questions/50026939/php-mysqli-connect-authentication-method-unknown-to-the-client-caching-sha2-pa)
		1. rerun the MySQL Installer
		2. select "Reconfigure" next to MySQL Server (the top item)
		3. click "Next" until you get to "Authentication Method"
		4. change "Use Strong Password Encryption for Authentication (RECOMMENDED)" to "Use Legacy Authentication Method (Retain MySQL 5.X Compatibility)
		5. click "Next"
		6. enter your Root Account Password in Accounts and Roles, and click "Check"
		7. click "Next"
		8. keep clicking "Next" until you get to "Apply Configuration"
		9. click "Execute"
		10. The Installer will make all the configuration changes needed for you.

## Try on [Website](https://skkkzhang.com/calendar/home.php)
