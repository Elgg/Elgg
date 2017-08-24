<?php

/**
 * Configuration array for Elgg installation on Travis
 */
return [

	// database settings
	'dbuser' => getenv('ELGG_DB_USER'),
	'dbpassword' => getenv('ELGG_DB_PASS'),
	'dbname' => getenv('ELGG_DB_NAME'),
	'dbprefix' => getenv('ELGG_DB_PREFIX'),
	'dbencoding' => getenv('ELGG_DB_ENCODING'),

	// site settings
	'sitename' => 'Elgg Travis Site',
	'siteemail' => 'no_reply@travis.elgg.org',
	'wwwroot' => getenv('ELGG_WWWROOT') ? : 'http://localhost/',
	'dataroot' => getenv('HOME') . '/engine/tests/test_files/dataroot/',

	// admin account
	'displayname' => 'Administrator',
	'email' => 'admin@travis.elgg.org',
	'username' => 'admin',
	'password' => 'fancypassword',

	// timezone
	'timezone' => 'UTC'
];