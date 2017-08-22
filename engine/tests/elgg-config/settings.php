<?php
/**
 * settings.php
 */
global $CONFIG;
$CONFIG = new stdClass();

$settings = \Elgg\Application::elggDir()->getPath('elgg-config/settings.php');
if (is_file($settings)) {
	include $settings;
}

$defaults = include __DIR__ . '/_overrides.php';

foreach ($defaults as $key => $value) {
	$CONFIG->$key = $value;
}
