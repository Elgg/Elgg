<?php
/**
 * settings.php for unit testing
 */
global $CONFIG;
$CONFIG = new stdClass();

$defaults = include __DIR__ . '/_defaults.php';

foreach ($defaults as $key => $value) {
	$CONFIG->$key = $value;
}