<?php
/**
 * Display an image
 *
 * @uses $vars['entity']
 */

if (empty($vars['full_view'])) {
	return;
}

$file = $vars['entity'];

$img = elgg_format_element('img', [
	'class' => 'elgg-photo',
	'src' => $file->getIconURL('large'),
]);
$a = elgg_format_element([
	'#tag_name' => 'a',
	'#text' => $img,
	'href' => elgg_get_download_url($file),
	'class' => 'elgg-lightbox-photo',
]);

?><div class="file-photo"><?= $a ?></div>
