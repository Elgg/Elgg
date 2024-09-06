<?php
/**
 * Provide a way of setting your full name.
 *
 * @uses $vars['entity'] the user to set settings for
 */

$user = elgg_extract('entity', $vars, elgg_get_page_owner_entity());
if (!$user instanceof \ElggUser) {
	return;
}

echo elgg_view_field([
	'#type' => 'text',
	'#label' => elgg_echo('name'),
	'name' => 'name',
	'value' => $user->name,
]);
