<?php
/**
 * Sample cli installer script
 */

require_once(dirname(dirname(__FILE__)) . "/ElggInstaller.php");

$installer = new ElggInstaller();

$params = array(
	// database parameters
	'dbuser' => '',
	'dbpassword' => '',
	'dbname' => '',

	// site settings
	'sitename' => '',
	'wwwroot' => '',
	'dataroot' => '',

	// admin account
	'displayname' => '',
	'email' => '',
	'username' => '',
	'password' => '',
);

// install and create the .htaccess file
$installer->batchInstall($params, TRUE);
