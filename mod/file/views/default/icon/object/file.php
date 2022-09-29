<?php
/**
 * File icon view
 *
 * @uses $vars['entity']     The entity the icon represents - uses getIconURL() method
 * @uses $vars['size']       topbar, tiny, small, medium (default), large, master
 * @uses $vars['use_link']   Hyperlink the icon
 * @uses $vars['href']       Optional override for link
 * @uses $vars['img_class']  Optional CSS class added to img
 * @uses $vars['link_class'] Optional CSS class added to link
 */
$entity = elgg_extract('entity', $vars);
if (!$entity instanceof ElggFile) {
	return;
}

$size = elgg_extract('size', $vars, 'medium');

if (elgg_extract('use_link', $vars, true)) {
	$url = elgg_extract('href', $vars, $entity->getURL());
}

$class = [];
if (isset($vars['img_class'])) {
	$class[] = $vars['img_class'];
}

if ($entity->hasIcon($size)) {
	$class[] = 'elgg-photo';
}

if (elgg_in_context('gallery') && !$entity->hasIcon($size)) {
	$icon_mapping = [
		'application/excel' => 'file-excel',
		'application/msword' => 'file-word',
		'application/ogg' => 'file-audio',
		'application/pdf' => 'file-pdf',
		'application/powerpoint' => 'file-powerpoint',
		'application/vnd.ms-excel' => 'file-excel',
		'application/vnd.ms-powerpoint' => 'file-powerpoint',
		'application/vnd.oasis.opendocument.text' => 'file-alt',
		'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'file-word',
		'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'file-excel',
		'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'file-powerpoint',
		'application/x-gzip' => 'file-archive',
		'application/x-rar-compressed' => 'file-archive',
		'application/x-stuffit' => 'file-archive',
		'application/zip' => 'file-archive',
		'text/directory' => 'id-card',
		'text/v-card' => 'id-card',
		'application' => 'save',
		'audio' => 'file-audio',
		'image' => 'file-image',
		'document' => 'file-alt',
		'video' => 'file-video',
	];
	
	$mime = $entity->getMimeType();
	$icon_name = 'file';
	if (!empty($mime)) {
		$icon_name = elgg_extract($mime, $icon_mapping, elgg_extract($entity->getSimpleType(), $icon_mapping, 'file'));
	}

	$img = elgg_view_icon($icon_name . '-regular');
} else {
	$img = elgg_view('output/img', [
		'class' => $class,
		'alt' => $entity->getDisplayName(),
		'src' => $entity->getIconURL($size),
	]);
}

if (!$url) {
	echo $img;
	return;
}

echo elgg_view('output/url', [
	'href' => $url,
	'text' => $img,
	'is_trusted' => true,
	'class' => elgg_extract('link_class', $vars),
]);
