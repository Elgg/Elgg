<?php
/**
 * Sample cli installer script
 */

$enabled = getenv('TRAVIS')!='';//are we on Travis? Need sth safer here...

// Do not edit below this line. //////////////////////////////


if (!$enabled) {
	echo "To enable this script, change \$enabled to true.\n";
	echo "You *must* disable this script after a successful installation.\n";
	exit;
}

if (PHP_SAPI !== 'cli') {
	echo "You must use the command line to run this script.";
	exit;
}

require_once(dirname(dirname(__FILE__)) . "/ElggInstaller.php");

$installer = new ElggInstaller();

// none of the following may be empty
$params = array(
	// database parameters
	'dbuser' => 'root',
	'dbpassword' => 'password',
	'dbname' => 'elgg_phpunit',

	// site settings
	'sitename' => 'Elgg Travis Site',
	'siteemail' => 'no_reply@travis.elgg.org',
	'wwwroot' => 'http://travis.elgg.org',
	'dataroot' => getenv('HOME') . '/elgg_data/',

	// admin account
	'displayname' => 'Administrator',
	'email' => 'admin@travis.elgg.org',
	'username' => 'admin',
	'password' => 'fancypassword',
);

ini_set('display_errors', 0);

// install and create the .htaccess file
$installer->batchInstall($params, TRUE);

ini_set('display_errors', 1);

// at this point installation has completed (otherwise an exception halted execution).
echo "Elgg CLI install successful. wwwroot: {$CONFIG->wwwroot}\n";

