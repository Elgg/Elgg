<?php
/**
 * CI CLI installer script. It's designed for core automatic tests only.
 *
 * @access private
 */

$enabled = getenv('CI') != ''; //are we on an CI environment?

if (!$enabled) {
	echo "This script should be run only in CI test environment.\n";
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
	'dbhost' => '127.0.0.1',
	
	// We use a wonky dbprefix to catch any cases where folks hardcode "elgg_"
	// instead of using config->dbprefix
	'dbprefix' => 'c_i_elgg_',
	
	// site settings
	'sitename' => 'Elgg CI Site',
	'siteemail' => 'no_reply@ci.elgg.org',
	'wwwroot' => 'http://localhost:8888/',
	'dataroot' => getenv('HOME') . '/elgg_data/',
	
	// admin account
	'displayname' => 'Administrator',
	'email' => 'admin@ci.elgg.org',
	'username' => 'admin',
	'password' => 'fancypassword',
	
	// timezone
	'timezone' => 'UTC'
);

// install and create the .htaccess file
$installer->batchInstall($params, true);

\Elgg\Application::start();

$version = elgg_get_version(true);

// at this point installation has completed (otherwise an exception halted execution).
echo "Elgg $version install successful" . PHP_EOL;
echo "wwwroot: " . elgg_get_config('wwwroot') . PHP_EOL;
