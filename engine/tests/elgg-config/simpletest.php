<?php
/**
 * settings.php for simpletest runner
 */
global $CONFIG;
$CONFIG = new stdClass();

$settings = \Elgg\Application::elggDir()->getPath('elgg-config/settings.php');
if (is_file($settings)) {
	include $settings;
}

// Other settings
$defaults = [

];

foreach ($defaults as $key => $value) {
	if (!isset($CONFIG->$key)) {
		$CONFIG->$key = $value;
	}
}
