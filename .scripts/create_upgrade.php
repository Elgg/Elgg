<?php
/**
 * Creates an upgrade file for Elgg.
 *
 * Run this from the command line:
 * 	php create_upgrade.php upgrade_name
 */

error_reporting(E_ALL);

// only allow from the command line.
if (php_sapi_name() != 'cli') {
	die('Upgrades can only be created from the command line.');
}

if (count($argv) < 2) {
	elgg_create_upgrade_show_usage('No upgrade name.');
}

$upgrade_name = $argv[1];

if (strlen($upgrade_name) > 24) {
	elgg_create_upgrade_show_usage('Upgrade names cannot be longer than 24 characters.');
}

require_once dirname(dirname(__FILE__)) . '/version.php';

$upgrade_path = 'classes/Elgg/Upgrades';
$upgrade_release = str_replace(array(' ', '-'), '_', $release);
$time = time();
$upgrade_rnd = substr(md5($time), 0, 16);
$upgrade_date = date('Ymd', $time);

// determine the inc count
$upgrade_inc = 0;
if (is_dir($upgrade_path)) {
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
} else {
	mkdir($upgrade_path, 0700, true);
}

// zero-pad
// if there are more than 10 upgrades in a day, someone needs talking to.
if ($upgrade_inc < 10) {
	$upgrade_inc = "0$upgrade_inc";
}

$upgrade_version = $upgrade_date . $upgrade_inc;

$class_name = "{$upgrade_name}{$time}";

$upgrade_file = "{$upgrade_path}/{$class_name}.php";

if (is_file($upgrade_file)) {
	elgg_create_upgrade_show_usage("Upgrade file $upgrade_file already exists. This script has failed you.");
}

$upgrade_code = <<<___UPGRADE
<?php

namespace Elgg\Upgrades;

/**
 * Elgg $release upgrade $class_name
 *
 * Add description here
 */
class $class_name implements Upgrade {

	public function isRequired() {
		// Check the database or the datadir to check if there is something
		// that this upgrade needs to process. Return true/false depending
		// on the result.
	}

	public function getTitle() {
		return 'Add user facing title here';
	}

	public function getDescription() {
		return 'Add user facing description here';
	}

	public function run() {
		// Do the actual upgrading here
	}

	public function getVersion() {
		// Do not modify this manually
		return $upgrade_version;
	}

	public function getRelease() {
		// Do not modify this manually
		return '$upgrade_release';
	}
}

___UPGRADE;

$h = fopen($upgrade_file, 'wb');

if (!$h) {
	die("Could not open file $upgrade_file");
}

if (!fwrite($h, $upgrade_code)) {
	die("Could not write to $upgrade_file");
} else {
	elgg_set_version_dot_php_version($upgrade_version);
	echo <<<___MSG

Created upgrade file and updated version.php.

Upgrade file: $class_name
Version:      $upgrade_version

___MSG;
}

fclose($h);

/**
 * Updates the version number in elgg/version.php
 *
 * @param string $version
 * @return boolean
 */
function elgg_set_version_dot_php_version($version) {
	$file = dirname(dirname(__FILE__)) . '/version.php';
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

	fwrite($h, $out);
	fclose($h);
	return true;
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

/**
 * C&P'd helpers from core to avoid pulling in everything.
 */

/**
 * Returns a list of files in $directory.
 *
 * Only returns files.  Does not recurse into subdirs.
 *
 * @param string $directory  Directory to look in
 * @param array  $exceptions Array of filenames to ignore
 * @param array  $list       Array of files to append to
 * @param mixed  $extensions Array of extensions to allow, NULL for all. Use a dot: array('.php').
 *
 * @return array Filenames in $directory, in the form $directory/filename.
 */
function elgg_get_file_list($directory, $exceptions = array(), $list = array(),
$extensions = NULL) {

	$directory = sanitise_filepath($directory);
	if ($handle = opendir($directory)) {
		while (($file = readdir($handle)) !== FALSE) {
			if (!is_file($directory . $file) || in_array($file, $exceptions)) {
				continue;
			}

			if (is_array($extensions)) {
				if (in_array(strrchr($file, '.'), $extensions)) {
					$list[] = $directory . $file;
				}
			} else {
				$list[] = $directory . $file;
			}
		}
		closedir($handle);
	}

	return $list;
}

/**
 * Sanitise file paths ensuring that they begin and end with slashes etc.
 *
 * @param string $path         The path
 * @param bool   $append_slash Add tailing slash
 *
 * @return string
 */
function sanitise_filepath($path, $append_slash = TRUE) {
	// Convert to correct UNIX paths
	$path = str_replace('\\', '/', $path);
	$path = str_replace('../', '/', $path);
	// replace // with / except when preceeded by :
	$path = preg_replace("/([^:])\/\//", "$1/", $path);

	// Sort trailing slash
	$path = trim($path);
	// rtrim defaults plus /
	$path = rtrim($path, " \n\t\0\x0B/");

	if ($append_slash) {
		$path = $path . '/';
	}

	return $path;
}