<?php

/**
 * Configuration array for Elgg installation on Travis
 */
return [
	// database parameters
	'dbuser' => 'root',
	'dbpassword' => 'password',
	'dbname' => 'elgg',

	// We use a wonky dbprefix to catch any cases where folks hardcode "elgg_"
	// instead of using config->dbprefix
	'dbprefix' => 't_i_elgg_',

	// site settings
	'sitename' => 'Elgg Travis Site',
	'siteemail' => 'no_reply@travis.elgg.org',
	'wwwroot' => 'http://localhost:8888/',
	'dataroot' => getenv('HOME') . '/elgg_data/',

	// admin account
	'displayname' => 'Administrator',
	'email' => 'admin@travis.elgg.org',
	'username' => 'admin',
	'password' => 'fancypassword',

	// timezone
	'timezone' => 'UTC'
];