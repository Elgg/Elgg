<?php
/**
 * Displays file type info
 *
 * @uses $vars['entity'] The entity to show the info for
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \ElggFile) {
	return;
}

$type_mapping = [
	'application/excel' => 'excel',
	'application/msword' => 'word',
	'application/ogg' => 'music',
	'application/pdf' => 'pdf',
	'application/powerpoint' => 'ppt',
	'application/vnd.ms-excel' => 'excel',
	'application/vnd.ms-powerpoint' => 'ppt',
	'application/vnd.oasis.opendocument.text' => 'openoffice',
	'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'word',
	'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'excel',
	'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'ppt',
	'application/x-gzip' => 'archive',
	'application/x-rar-compressed' => 'archive',
	'application/x-stuffit' => 'archive',
	'application/zip' => 'archive',
	'text/directory' => 'vcard',
	'text/v-card' => 'vcard',
	'application' => 'application',
	'audio' => 'music',
	'image' => 'image',
	'document' => 'text',
	'video' => 'video',
];

$mime = $entity->getMimeType();

$type = 'unknown';
if (!empty($mime)) {
	$type = elgg_extract($mime, $type_mapping, elgg_extract($entity->getSimpleType(), $type_mapping, 'unknown'));
}

if (elgg_language_key_exists("item:object:file:{$type}")) {
	$content = elgg_echo("item:object:file:{$type}");
} else {
	$content = elgg_echo('unknown');
}
	
$icon_mapping = [
	'excel' => 'file-excel',
	'word' => 'file-word',
	'music' => 'file-audio',
	'ppt' => 'file-powerpoint',
	'pdf' => 'file-pdf',
	'openoffice' => 'file-alt',
	'archive' => 'file-archive',
	'vcard' => 'id-card',
	'application' => 'save',
	'image' => 'file-image',
	'text' => 'file-alt',
	'video' => 'file-video',
];

echo elgg_view('object/elements/imprint/element', [
	'icon_name' => elgg_extract($type, $icon_mapping, 'file'),
	'content' => $content,
]);
