<?php
/**
 * Elgg file download.
 * 
 * @package ElggFile
 */

$autoload_root = dirname(dirname(__DIR__));
if (!is_file("$autoload_root/vendor/autoload.php")) {
	$autoload_root = dirname(dirname(dirname($autoload_root)));
}
require_once "$autoload_root/vendor/autoload.php";

\Elgg\Application::start();

// Get the guid
$file_guid = get_input("file_guid");

forward("file/download/$file_guid");
