<?php
/**
 * Provide a way of setting your default access
 *
 * @uses $vars['entity'] the user to set settings for
 */

if (!elgg_get_config('allow_user_default_access')) {
	return;
}

$user = elgg_extract('entity', $vars, elgg_get_page_owner_entity());
if (!$user instanceof ElggUser) {
	return;
}

$default_access = $user->getPrivateSetting('elgg_default_access');
if ($default_access === null) {
	$default_access = elgg_get_config('default_access');
}

$title = elgg_echo('default_access:settings');
$content = elgg_view_field([
	'#type' => 'access',
	'#label' => elgg_echo('default_access:label'),
	'name' => 'default_access',
	'value' => $default_access,
]);

echo elgg_view_module('info', $title, $content);
