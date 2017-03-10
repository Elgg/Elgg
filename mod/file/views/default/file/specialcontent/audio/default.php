<?php
/**
 * Outputs an audio player in full view
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

echo "<audio controls><source src='{$download_url}' type='{$mimetype}'></audio>";
