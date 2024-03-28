<?php
/**
 * Profile owner block
 *
 * @uses $vars['entity'] The user entity
 */

$user = elgg_extract('entity', $vars);
if (!$user instanceof \ElggUser) {
	return;
}

$href = null;
if ($user->canEdit()) {
	$href = elgg_generate_url('edit:user:avatar', ['username' => $user->username]);
}

$result = elgg_view_entity_icon($user, 'large', [
	'use_hover' => false,
	'use_link' => !empty($href),
	'href' => $href,
	'img_class' => 'photo u-photo',
]);

$result .= elgg_view_menu('owner_block', [
	'entity' => $user,
	'prepare_vertical' => true,
]);

if (elgg_is_admin_logged_in() && elgg_get_logged_in_user_guid() !== $user->guid) {
	$result .= elgg_view_menu('profile_admin', [
		'entity' => $user,
		'class' => ['elgg-menu-owner-block'],
		'prepare_vertical' => true,
	]);
}

echo elgg_format_element('div', ['id' => 'profile-owner-block'], $result);
