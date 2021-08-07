<?php
/*
- create a database to hold transaction records and users
- loads in a list of users from JSON: https://randomuser.me/api/?results=500&nat=us&exc=login,id,nat
	- add unique users to a database table
	- as users are loaded, generate a transaction record tied to the user
	- create a User class to work with the data
		- use the class to format birthdate to the following format: January 1, 1990
		- write a method to generate a transaction for the user
			- in addition to storing user details, transactions should have:
				- a unique ID
				- timestamp
				- amount
				- status
				- payment method (Visa, Mastercard, Discover, American Express, eCheck, or any other new payment method that pops up)
		- anything else you think would be helpful for working with the data

- display a table of transactions with data displayed
- format transactions in the following format: MM/DD/YYYY
- default sorted by transaction ID
- display a table of users with data displayed
- clicking the user ID takes you to a view that displays that users details and associated transactions
- note: logic should be built without utilizing PHP libraries

- bonus: add dynamic table sorting
- bonus: add pagination
*/

/*
If you need help setting up a development environment:
	Download and run mamp from https://www.mamp.info/en/

	After installation, run the MAMP app (should not need pro)
	On Mac, located at /Applications/MAMP/MAMP
	"Start Servers"

	Your dev stack should be up and running locally. You can access the database through phpMyAdmin at http://localhost:8888/phpMyAdmin/?lang=en

	Php and html should live in /Applications/MAMP/htdocs/
*/

require_once 'config.php';
require_once 'load.php';

GW\Setup::init();

$query_args = array(
	'pg' => isset( $_GET['pg'] ) && is_numeric( $_GET['pg'] ) ? (int) $_GET['pg'] : 1,
	'ob' => isset( $_GET['ob'] ) ? $_GET['ob'] : 'ln',
	'o'  => isset( $_GET['o'] ) ? $_GET['o'] : 'a',
);

$uid = isset( $_GET['uid'] ) && is_numeric( $_GET['uid'] ) ? (int) $_GET['uid'] : false;
?>
<!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width" />

	<title>Qgiv Engineering Exercise</title>
	<link rel="stylesheet" type="text/css" href="https://secure.qgiv.com/resources/admin/css/application.css" />
	<link rel="stylesheet" type="text/css" href="assets/style.css" />

	<style type="text/css">
		.container{ max-width: 1200px; margin: 0 auto; }

		.logo-header{ padding: 2em; }
		.logo{ margin: 0 auto; min-height: 80px; }
	</style>
</head>

<body>
	<section class="container">
		<div class="logo-header">
			<img class="logo" src="https://secure.qgiv.com/resources/core/images/logo-qgiv.svg" alt="Qgiv logo" />
		</div>

		<?php
		if ( $uid ) {
			require_once 'view/user-detail.php';
		} else {
			require_once 'view/users.php';
		}
		?>
	</section>
</body>
</html>
