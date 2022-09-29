<?php
/**
 * Link to download the file
 *
 * @uses $vars['entity']
 */

$file = elgg_extract('entity', $vars);
if (!$file instanceof \ElggFile || !$file->canDownload()) {
	return;
}

echo elgg_format_element('enclosure', [
	'url' => $file->getDownloadURL(),
	'length' => $file->getSize(),
	'type' => $file->getMimeType(),
], '', [
	'is_void' => true,
	'is_xml' => true,
]);
