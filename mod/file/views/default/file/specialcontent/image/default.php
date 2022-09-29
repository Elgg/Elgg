<?php
/**
 * Display an image
 *
 * @uses $vars['entity']
 */

if (!elgg_extract('full_view', $vars, false)) {
	return;
}

$file = elgg_extract('entity', $vars);

$img = elgg_format_element('img', [
	'class' => 'elgg-photo',
	'src' => $file->getIconURL('xlarge'),
]);
$a = elgg_format_element('a', [
	'href' => $file->canDownload() ? $file->getDownloadURL() : $file->getIconURL('xlarge'),
	'class' => 'elgg-lightbox-photo',
], $img);

echo elgg_format_element('div', ['class' => 'file-photo'], $a);
