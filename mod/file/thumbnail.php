<?php
/**
 * Elgg file thumbnail
 *
 * @package ElggFile
 */

elgg_deprecated_notice('thumbnail.php is no longer in use and will be removed. Do not include or require it. Use elgg_get_inline_url() instead.', '3.0');

$autoload_root = dirname(dirname(__DIR__));
if (!is_file("$autoload_root/vendor/autoload.php")) {
	$autoload_root = dirname(dirname(dirname($autoload_root)));
}
require_once "$autoload_root/vendor/autoload.php";

\Elgg\Application::start();

elgg_deprecated_notice('mod/file/thumbnail.php is no longer in use and will be removed. Do not include or require it. Use elgg_get_inline_url() instead.', '2.2');

// Get file GUID
$file_guid = (int) get_input('file_guid', 0);

// Get file thumbnail size
$size = get_input('size', 'small');

$file = get_entity($file_guid);

$thumb_url = false;

// thumbnails get first priority
if ($file && $file->thumbnail) {

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

	if (!empty($thumbfile)) {
		$readfile = new ElggFile();
		$readfile->owner_guid = $file->owner_guid;
		$readfile->setFilename($thumbfile);
		$thumb_url = elgg_get_inline_url($readfile, true);
	}
}

if ($thumb_url) {
	forward($thumb_url);
}

header('HTTP/1.1 404 Not found');
exit;