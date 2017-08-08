<?php

/**
 * Clears entities created using seeder
 *
 * @access private
 */

$root = dirname(dirname(__DIR__));
require_once "$root/autoloader.php";

if (!class_exists('\Faker\Generator')) {
	fwrite(STDERR, 'This is a developer tool currently intended for testing purposes only. Please refrain from using it.');
}

\Elgg\Application::start();

set_time_limit(0);

_elgg_services()->setValue('mailer', new \Zend\Mail\Transport\InMemory());

_elgg_services()->seeder->unseed();