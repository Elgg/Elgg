<?php
/**
 * We handle here two possible locations of composer-generated autoload file. One is directly in Elgg directory
 * when not using as dependency, other is in the main project dir, parent to this one.
 */

$autoload_path_local = __DIR__ . '/vendor/autoload.php';
$autoload_path_parent = __DIR__ . '/../../../vendor/autoload.php';

if (
	(!file_exists($autoload_path_local) || (!$autoloader = include($autoload_path_local)))
	&& (!file_exists($autoload_path_parent) || (!$autoloader = include($autoload_path_parent)))
) {
	die("Couldn't include '$autoload_path_local' or '$autoload_path_parent'.\n" .
		"You must set up the project dependencies, run the following commands:\n" .
		"curl -s http://getcomposer.org/installer | php\n" .
		"php composer.phar install");
}
return $autoloader;
