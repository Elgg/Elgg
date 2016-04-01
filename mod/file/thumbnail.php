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

$file_guid = get_input('file_guid');
elgg_entity_gatekeeper($file_guid);

// Get file thumbnail size
$size = get_input('size', 'small');
$file = get_entity($file_guid);

$thumbnail = elgg_get_thumbnail($file, $size);
$thumb_url = elgg_get_inline_url($thumbnail, true);

if ($thumb_url) {
	forward($thumb_url);
}

forward('', '404');
