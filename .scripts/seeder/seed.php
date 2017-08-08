<?php

/**
 * Seeds the database with testing entities and other rows
 *
 * @access private
 */

$root = dirname(dirname(__DIR__));
require_once "$root/autoloader.php";

if (!class_exists('\Faker\Generator')) {
	fwrite(STDERR, 'This is a developer tool currently intended for testing purposes only. Please refrain from using it.');
}

\Elgg\Application::start();

elgg_set_config('debug', 'NOTICE');

set_time_limit(0);

if (elgg_is_logged_in()) {
	throw new LogicException("Seeds should not be run with a logged in user");
}

_elgg_services()->setValue('mailer', new \Zend\Mail\Transport\InMemory());

_elgg_services()->seeder->seed();