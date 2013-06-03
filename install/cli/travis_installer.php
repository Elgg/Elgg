<?php
/**
 * Travis CI CLI installer script. It's designed for core automatic tests only.
 *
 * @access private
 * @package Elgg
 * @subpackage Test
 */

$enabled = getenv('TRAVIS') != '';//are we on Travis?

if (!$enabled) {
	echo "This script should be run only in Travis CI test environment.\n";
	exit(1);
}

if (PHP_SAPI !== 'cli') {
	echo "You must use the command line to run this script.\n";
	exit(2);
}

require_once(dirname(dirname(__FILE__)) . "/ElggInstaller.php");

$installer = new ElggInstaller();

// none of the following may be empty
$params = array(
	// database parameters
	'dbuser' => 'root',
	'dbpassword' => 'password',
	'dbname' => 'elgg',

	// site settings
	'sitename' => 'Elgg Travis Site',
	'siteemail' => 'no_reply@travis.elgg.org',
	'wwwroot' => 'http://travis.elgg.org/',
	'dataroot' => getenv('HOME') . '/elgg_data/',

	// admin account
	'displayname' => 'Administrator',
	'email' => 'admin@travis.elgg.org',
	'username' => 'admin',
	'password' => 'fancypassword',
);

// install and create the .htaccess file
$installer->batchInstall($params, TRUE);

// at this point installation has completed (otherwise an exception halted execution).
echo "Elgg CLI install successful. wwwroot: " . elgg_get_config('wwwroot') . "\n";

