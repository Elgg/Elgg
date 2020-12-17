<?php

/**
 * Configuration array for Elgg installation on CI
 */
return [

	// database settings
	'dbuser' => getenv('ELGG_DB_USER'),
	'dbpassword' => getenv('ELGG_DB_PASS'),
	'dbname' => getenv('ELGG_DB_NAME'),
	'dbprefix' => getenv('ELGG_DB_PREFIX'),
	'dbencoding' => getenv('ELGG_DB_ENCODING') ? : 'utf8mb4',
	'dbport' => getenv('ELGG_DB_PORT') ? : '3306',
	'dbhost' => getenv('ELGG_DB_HOST') ? : 'localhost',

	// site settings
	'sitename' => 'Elgg CI Site',
	'siteemail' => 'no_reply@ci.elgg.org',
	'wwwroot' => getenv('ELGG_WWWROOT') ? : 'http://localhost/',
	'dataroot' => getenv('HOME') . '/engine/tests/test_files/dataroot/',

	// admin account
	'displayname' => 'Administrator',
	'email' => 'admin@ci.elgg.org',
	'username' => 'admin',
	'password' => 'fancypassword',

	// timezone
	'timezone' => 'UTC'
];
