<?php
/**
 * settings.php for memcached testing
 */


global $CONFIG;
$CONFIG = new stdClass();

$settings = \Elgg\Application::elggDir()->getPath('elgg-config/settings.php');
include $settings;

$defaults = include __DIR__ . '/_defaults.php';

