<?php
/**
 * Creates an upgrade file for Elgg.
 *
 * Run this from the command line:
 * 	php create_upgrade.php upgrade_name
 */

error_reporting(E_NOTICE);

// only allow from the command line.
if (php_sapi_name() != 'cli') {
	die('Upgrades can only be created from the command line.');
}

if (count($argv) < 2) {
	elgg_create_upgrade_show_usage('No upgrade name.');
}

$name = $argv[1];

if (strlen($name) > 24) {
	elgg_create_upgrade_show_usage('Upgrade names cannot be longer than 24 characters.');
}

require_once '../../../version.php';
require_once '../elgglib.php';
$upgrade_path = dirname(__FILE__);

$upgrade_name = strtolower($name);
$upgrade_name = str_replace(array(' ', '-'), '_', $upgrade_name);
$upgrade_release = str_replace(array(' ', '-'), '_', $release);
$time = time();
$upgrade_rnd = substr(md5($time), 0, 16);
$upgrade_date = date('Ymd', $time);

// determine the inc count
$upgrade_inc = 0;
$files = elgg_get_file_list($upgrade_path);
sort($files);

foreach ($files as $filename) {
	$filename = basename($filename);
	$date = (int)substr($filename, 0, 8);
	$inc = (int)substr($filename, 8, 2);

	if ($upgrade_date == $date) {
		if ($inc >= $upgrade_inc) {
			$upgrade_inc = $inc + 1;
		}
	}
}

// zero-pad
// if there are more than 10 upgrades in a day, someone needs talking to.
if ($upgrade_inc < 10) {
	$upgrade_inc = "0$upgrade_inc";
}

$upgrade_version = $upgrade_date . $upgrade_inc;

// make filename
if (substr($release, 0, 3) == '1.7') {
	// 1.7 upgrades are YYYYMMDDXX
	$upgrade_name = $upgrade_version . '.php';
} else {
	// 1.8+ upgrades are YYYYMMDDXX-release-friendly_name-rnd
	$upgrade_name = $upgrade_version . "-$upgrade_release-$name-$upgrade_rnd.php";
}

$upgrade_file = $upgrade_path . '/' . $upgrade_name;

if (is_file($upgrade_file)) {
	elgg_create_upgrade_show_usage("Upgrade file $upgrade_file already exists. This script has failed you.");
}

$upgrade_code = <<<___UPGRADE
<?php
/**
 * Elgg $release upgrade $upgrade_version
 * $name
 *
 * Description
 */

// upgrade code here.

___UPGRADE;

$h = fopen($upgrade_file, 'wb');

if (!$h) {
	die("Could not open file $upgrade_file");
}

if (!fputs($h, $upgrade_code)) {
	die("Could not write to $upgrade_file");
} else {
	elgg_set_version_dot_php_version($upgrade_version);
	echo <<<___MSG

Created upgrade file and updated version.php.

Upgrade file: $upgrade_name
Version:      $upgrade_version

___MSG;
}

fclose($h);


function elgg_set_version_dot_php_version($version) {
	$file = '../../../version.php';
	$h = fopen($file, 'r+b');

	if (!$h) {
		return false;
	}

	$out = '';

	while (($line = fgets($h)) !== false) {
		$find = "/\\\$version[ ]?=[ ]?[0-9]{10};/";
		$replace = "\$version = $version;";
		$out .= preg_replace($find, $replace, $line);
	}

	rewind($h);

	fputs($h, $out);
	fclose($h);
}

/**
 * Shows the usage for the create_upgrade script and dies().
 *
 * @param string $msg Optional message to display
 * @return void
 */
function elgg_create_upgrade_show_usage($msg = '') {
	$text = <<<___MSG
$msg

Example:
	php create_upgrade.php my_upgrade

___MSG;

	die($text);
}
