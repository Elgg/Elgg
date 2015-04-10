<?php
/**
 * Outputs an audio player in full view
 *
 * @uses $vars['entity']
 */

$file = $vars['entity'];
if (!$vars['full_view']) {
	return;
}

$download_url = elgg_get_site_url() . "file/download/{$file->getGUID()}";
$mimetype = $file->mimetype;

echo "<audio controls><source src='{$download_url}' type='{$mimetype}'></audio>";
