<?php

/**
 * Sample cli installer script
 */

// change to true to run this script. Change back to false when done.
$enabled = false;

// none of the following may be empty
$params = [
	// database parameters
	'dbuser' => '',
	'dbpassword' => '',
	'dbname' => '',

	// site settings
	'sitename' => '',
	'siteemail' => '',
	'wwwroot' => '',
	'dataroot' => '',

	// admin account
	'displayname' => '',
	'email' => '',
	'username' => '',
	'password' => '',
];


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

require_once __DIR__ . "/../../autoloader.php";

$installer = new ElggInstaller();

// install and create the .htaccess file
$installer->batchInstall($params, true);

// at this point installation has completed (otherwise an exception halted execution).
// try to rewrite the script to disable it.
if (is_writable(__FILE__)) {
	$code = file_get_contents(__FILE__);
	if (preg_match('~\\$enabled\\s*=\\s*(true|1)\\s*;~i', $code)) {
		// looks safe to rewrite
		$code = preg_replace('~\\$enabled\\s*=\\s*(true|1)\\s*;~i', '$enabled = false;', $code);
		file_put_contents(__FILE__, $code);

		echo "\nNote: This script has been disabled for your safety.\n";
		exit;
	}
}

echo "\nWarning: You *must* disable this script by setting \$enabled = false;.\n";
echo "Leaving this script enabled could endanger your installation.\n";
