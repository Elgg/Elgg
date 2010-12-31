<?php
/**
 * Elgg file thumbnail
 *
 * @package ElggFile
 */

// Get engine
require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

// Get file GUID
$file_guid = (int) get_input('file_guid', 0);

// Get file thumbnail size
$size = get_input('size', 'small');

$file = get_entity($file_guid);
if (!$file || $file->getSubtype() != "file") {
	exit;
}

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
		$mime = $file->getMimeType();
		$contents = $readfile->grabFile();

		// caching images for 10 days
		header("Content-type: $mime");
		header('Expires: ' . date('r',time() + 864000));
		header("Pragma: public", true);
		header("Cache-Control: public", true);
		header("Content-Length: " . strlen($contents));

		echo $contents;
		exit;
	}
}
