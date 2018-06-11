<?php
/**
 * Creates an upgrade file for Elgg.
 *
 * Run this from the command line:
 * 	php create_upgrade.php upgrade_name
 */

error_reporting(E_ALL);

// only allow from the command line.
if (!\Elgg\Application::isCli()) {
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
 * Upgrade script will always run after ALL database migrations are complete.
 * 
 * Do not use upgrade scripts for database schema migrations, use phinx instead. See docs for instructions.
 * 
 * Do not use upgrade script for long-running scripts, use async upgrades instead. See docs for instructions. 
 */

// upgrade code here.

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

Upgrade file: $upgrade_name
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

	$directory = \Elgg\Project\Paths::sanitize($directory);
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
function \Elgg\Project\Paths::sanitize($path, $append_slash = TRUE) {
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