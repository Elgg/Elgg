<?php
/**
 * Elgg file download.
 *
 * @package ElggFile
 */

// Get the guid
$file_guid = get_input("guid");

// Get the file
$file = get_entity($file_guid);
if (!elgg_instanceof($file, 'object', 'file')) {
	register_error(elgg_echo("file:downloadfailed"));
	forward();
}

$mime = $file->getMimeType();
if (!$mime) {
	$mime = "application/octet-stream";
}

$filename = $file->originalfilename;

// fix for IE https issue
header("Pragma: public");

header("Content-type: $mime");
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Content-Length: {$file->getSize()}");

while (ob_get_level()) {
    ob_end_clean();
}
flush();
readfile($file->getFilenameOnFilestore());
exit;
