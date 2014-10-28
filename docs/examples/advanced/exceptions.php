<?php

/**
 * This is an optional script used to override Elgg's default handling of
 * uncaught exceptions.
 * This is defined in the global $CONFIG->exception_include in settings.php
 *
 * The script will have access to the following variables as part of the scope
 * global $CONFIG
 * $exception - the unhandled exception
 *
 * @warning - the database may not be available
 *
 */

// notify some important people that a problem has occurred
// remember we can't rely on the database being available so everything here
// should be hard coded
$emails = array(
	'admin@example.com',
	'expert@example.com'
);

$url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$subject = "Exception: $url";
$message = $exception->getMessage();

foreach ($emails as $email) {
	mail($email, $subject, $message);
}


// output a custom error page to match the theme or give a custom message
$html = <<<HTML
	<html>
		<body>
			Oops, a problem occurred.  The authorities have been notified.
			Sorry for the inconvenience.
		</body>
	</html>
HTML;

// any output will prevent the default views from rendering allowing
// this script to control the entire page output
echo $html;
