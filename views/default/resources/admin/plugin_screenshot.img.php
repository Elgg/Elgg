<?php
$pages = $vars['segments'];

// only admins can use this for security
elgg_admin_gatekeeper();

$plugin_id = elgg_extract(0, $pages);
// only thumbnail or full.
$size = elgg_extract(1, $pages, 'thumbnail');

// the rest of the string is the filename
$filename_parts = array_slice($pages, 2);
$filename = implode('/', $filename_parts);
$filename = sanitise_filepath($filename, false);

$plugin = elgg_get_plugin_from_id($plugin_id);
if (!$plugin) {
	$file = elgg_get_root_path() . '_graphics/icons/default/medium.png';
} else {
	$file = $plugin->getPath() . $filename;
	if (!file_exists($file)) {
		$file = elgg_get_root_path() . '_graphics/icons/default/medium.png';
	}
}

// TODO(ewinslow): This says jpeg, but the defaults are png... -_-
header("Content-type: image/jpeg");

// resize to 100x100 for thumbnails
switch ($size) {
	case 'thumbnail':
		echo get_resized_image_from_existing_file($file, 100, 100, true);
		break;

	case 'full':
	default:
		echo file_get_contents($file);
		break;
}