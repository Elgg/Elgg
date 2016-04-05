<?php
/**
 * Elgg file thumbnail
 *
 * @package ElggFile
 */

$autoload_root = dirname(dirname(__DIR__));
if (!is_file("$autoload_root/vendor/autoload.php")) {
	$autoload_root = dirname(dirname(dirname($autoload_root)));
}
require_once "$autoload_root/vendor/autoload.php";

\Elgg\Application::start();

// Get file GUID
$file_guid = (int) get_input('file_guid', 0);

// Get file thumbnail size
$size = get_input('size', 'small');

$file = get_entity($file_guid);
if (!elgg_instanceof($file, 'object', 'file')) {
	exit;
}
/* @var ElggFile $file */

$simpletype = $file->simpletype;
if ($simpletype == "image") {

	// Get file thumbnail
	switch ($size) {
		case "small":
			$thumbfile = $file->thumbnail;
			break;
		case "medium":
			$thumbfile = $file->smallthumb;
			break;
		case "large":
		default:
			$thumbfile = $file->largethumb;
			break;
	}

	// Grab the file
	if ($thumbfile && !empty($thumbfile)) {
		$readfile = new ElggFile();
		$readfile->owner_guid = $file->owner_guid;
		$readfile->setFilename($thumbfile);
		$mime = $readfile->detectMimeType();
		if ($mime === 'application/octet-stream') {
			$mime = 'image/jpeg';
		}
		$contents = $readfile->grabFile();

		// caching images for 10 days
		header("Content-type: $mime");
		header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', strtotime("+10 days")), true);
		header("Pragma: public", true);
		header("Cache-Control: public", true);
		header("Content-Length: " . strlen($contents));

		echo $contents;
		exit;
	}
}
