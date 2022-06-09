<?php
/**
 * Outputs a video player in full view
 *
 * @uses $vars['entity']
 */

$file = elgg_extract('entity', $vars);
if (!$file) {
	return;
}

if (!elgg_extract('full_view', $vars, false)) {
	return;
}

$download_url = $file->getDownloadURL();
$mimetype = $file->getMimeType();

echo "<video width='100%' preload='metadata' controls><source src='{$download_url}' type='{$mimetype}'></video>";
