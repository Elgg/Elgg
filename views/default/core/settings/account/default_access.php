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
if (!$user instanceof \ElggUser) {
	return;
}

echo elgg_view_field([
	'#type' => 'access',
	'#label' => elgg_echo('default_access:label'),
	'name' => 'default_access',
	'value' => $user->elgg_default_access ?? elgg_get_config('default_access'),
]);
