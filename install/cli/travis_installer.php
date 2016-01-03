<?php
/**
 * Travis CI CLI installer script. It's designed for core automatic tests only.
 *
 * @access private
 */

$enabled = getenv('TRAVIS') != ''; //are we on Travis?

if (!$enabled) {
	echo "This script should be run only in Travis CI test environment.\n";
	exit(1);
}

if (PHP_SAPI !== 'cli') {
	echo "You must use the command line to run this script.\n";
	exit(2);
}

require_once __DIR__ . "/../../autoloader.php";

$installer = new ElggInstaller();

// none of the following may be empty
$params = array(
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
);

// install and create the .htaccess file
$installer->batchInstall($params, TRUE);

// at this point installation has completed (otherwise an exception halted execution).
echo "Elgg CLI install successful. wwwroot: " . elgg_get_config('wwwroot') . "\n";

