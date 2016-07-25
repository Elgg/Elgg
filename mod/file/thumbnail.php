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

elgg_deprecated_notice('mod/file/thumbnail.php is no longer in use and will be removed. Do not include or require it. Use elgg_get_inline_url() instead.', '2.2');

// Get file GUID
$file_guid = (int) get_input('file_guid', 0);

// Get file thumbnail size
$size = get_input('size', 'small');

$file = get_entity($file_guid);

if ($file) {
	$thumb = $file->getIcon($size);
	$thumb_url = elgg_get_inline_url($thumb, true);
	if ($thumb_url) {
		forward($thumb_url);
	}
}

forward('', '404');
