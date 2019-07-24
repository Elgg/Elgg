<?php
/**
 * Show warning about size constraints when uploading/cropping an icon
 *
 * @uses $vars['cropper_show_messages'] Show messages (default: true for icon_type = 'icon', false otherwise)
 * @uses $vars['cropper_min_width']     The minimal width of the cropped image
 * @uses $vars['cropper_min_height']    The minimal height of the cropped image
 */

$icon_type = elgg_extract('icon_type', $vars, 'icon');
$show_icon_cropper_messages = (bool) elgg_extract('cropper_show_messages', $vars, $icon_type === 'icon');
if (!$show_icon_cropper_messages) {
	return;
}

// placeholder for messages
$errors = [];

$min_width = (int) elgg_extract('cropper_min_width', $vars);
if ($min_width > 0) {
	$errors[] = elgg_format_element('span', [
		'class' => [
			'elgg-entity-edit-icon-crop-error-width',
			'hidden',
		],
		'data-min-width' => $min_width,
	], elgg_echo('entity:edit:icon:crop_messages:width', [$min_width]) . '&nbsp;');
}
$min_height = (int) elgg_extract('cropper_min_height', $vars);
if ($min_height > 0) {
	$errors[] = elgg_format_element('span', [
		'class' => [
			'elgg-entity-edit-icon-crop-error-height',
			'hidden',
		],
		'data-min-height' => $min_height,
	], elgg_echo('entity:edit:icon:crop_messages:height', [$min_height]));
}

if (empty($errors)) {
	return;
}

// add generic message
array_unshift($errors, elgg_format_element('div', [
	'class' => [
		'elgg-entity-edit-icon-crop-error-generic',
		'hidden',
	]
], elgg_echo('entity:edit:icon:crop_messages:generic')));

// build output
$message = elgg_view('output/longtext', [
	'value' => implode('', $errors),
	'sanitize' => false,
]);
$message_vars = [
	'class' => [
		'elgg-entity-edit-icon-crop-messages',
		'hidden',
	],
];

echo elgg_view_message('warning', $message, $message_vars);
