<?php
/**
 * Elgg file download.
 *
 * @package ElggFile
 * @deprecated since version 3.0
 */

elgg_deprecated_notice('/file/download resource view has been deprecated and will be removed. Use elgg_get_download_url() to build download URLs', '2.2');

// Get the guid
$file_guid = elgg_extract("guid", $vars);

// Get the file
$file = get_entity($file_guid);
if (!elgg_instanceof($file, 'object', 'file')) {
	register_error(elgg_echo("file:downloadfailed"));
	forward();
}

forward(elgg_get_download_url($file));
