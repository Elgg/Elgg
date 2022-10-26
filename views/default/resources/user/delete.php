<?php

use Elgg\Exceptions\Http\EntityNotFoundException;

$user = get_user((int) elgg_extract('guid', $vars));
if (!$user instanceof \ElggUser) {
	throw new EntityNotFoundException();
}

$content = elgg_view_image_block(elgg_view_entity_icon($user, 'medium'), elgg_view('output/longtext', [
	'value' => elgg_echo('user:delete:description', [elgg_format_element('strong', [], $user->getDisplayName())]),
]), ['class' => 'mbl']);

$content .= elgg_view('core/settings/statistics', [
	'entity' => $user,
]);

$form_vars = [
	'action' => elgg_generate_action_url('admin/user/delete', [
		'forward_url' => elgg_get_site_url(),
	], false),
];

$body_vars = [
	'guid' => $user->guid,
	'entity' => $user,
];

$content .= elgg_view_form('user/delete', $form_vars, $body_vars);

echo elgg_view_page(elgg_echo('user:delete:title'), [
	'content' => $content,
]);
