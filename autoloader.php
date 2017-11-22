<?php
/**
 * We handle here two possible locations of composer-generated autoload file. One is directly in Elgg directory
 * when not using as dependency, other is in the main project dir, parent to this one.
 */

$paths = [
	__DIR__ . '/vendor/autoload.php',
	__DIR__ . '/../../../vendor/autoload.php',
];

foreach ($paths as $path) {
	if (!is_file($path)) {
		continue;
	}

	if (!is_readable($path)) {
		echo "'$path' exists but is not readable by your webserver.\n";
		break;
	}

	$autoloader = (include $path);
	if (!$autoloader) {
		echo "'$path' was present but did not return a autoloader.\n";
		break;
	}

	return $autoloader;
}

echo "You must set up the project dependencies. Run the following commands:\n" .
	"curl -s http://getcomposer.org/installer | php\n" .
	"php composer.phar install";
exit(1); // report a generic error
