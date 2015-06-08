<?php

// only admins can use this for security
elgg_admin_gatekeeper();

$plugin_id = get_input('plugin_id');
$size = get_input('size');
$filename = get_input('filename');

$plugin = elgg_get_plugin_from_id($plugin_id);
if (!$plugin) {
	$file = elgg_get_root_path() . 'views/default/icons/default/medium.png';
} else {
	$file = $plugin->getPath() . $filename;
	if (!file_exists($file)) {
		$file = elgg_get_root_path() . 'views/default/icons/default/medium.png';
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