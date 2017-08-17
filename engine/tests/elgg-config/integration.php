<?php
/**
 * settings.php for integration testing
 */
global $CONFIG;
$CONFIG = new stdClass();

$settings = \Elgg\Application::elggDir()->getPath('elgg-config/settings.php');
if (is_file($settings)) {
	include $settings;
}

$defaults = include __DIR__ . '/_defaults.php';

foreach ($defaults as $key => $value) {
	if (!isset($CONFIG->$key)) {
		$CONFIG->$key = $value;
	}
}
