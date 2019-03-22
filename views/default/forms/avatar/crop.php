<?php
/**
 * Avatar crop form
 *
 * @uses $vars['entity']
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \ElggUser) {
	return;
}

elgg_load_js('jquery.imgareaselect');
elgg_load_js('elgg.avatar_cropper');
elgg_load_css('jquery.imgareaselect');

echo elgg_view('output/img', [
	'src' => $entity->getIconUrl('master'),
	'alt' => elgg_echo('avatar'),
	'class' => 'mrl',
	'id' => 'user-avatar-cropper',
	'width' => '550',
]);

$coords = ['x1', 'x2', 'y1', 'y2'];
foreach ($coords as $coord) {
	echo elgg_view_field([
		'#type' => 'hidden',
		'name' => $coord,
		'value' => $entity->$coord,
	]);
}

echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'guid',
	'value' => $entity->guid,
]);


$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('avatar:create'),
]);

elgg_set_form_footer($footer);
