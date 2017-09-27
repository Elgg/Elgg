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
	'src' => $file->getIconURL('large'),
]);
$a = elgg_format_element([
	'#tag_name' => 'a',
	'#text' => $img,
	'href' => $file->canDownload() ? $file->getDownloadURL() : $file->getIconURL('large'),
	'class' => 'elgg-lightbox-photo',
]);

echo elgg_format_element('div', ['class' => 'file-photo'], $a);
