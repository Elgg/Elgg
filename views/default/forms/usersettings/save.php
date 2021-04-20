<?php
/**
 * User account settings.
 *
 * Plugins should extend this form to add to the settings.
 *
 * @uses $vars['entity'] the user to set settings for
 */

$user = elgg_extract('entity', $vars, elgg_get_page_owner_entity());
if (!$user instanceof ElggUser) {
	return;
}

// we need to include the user GUID so that admins can edit the settings of other users
echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'guid',
	'value' => $user->guid,
]);

// form footer
$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('save'),
]);

elgg_set_form_footer($footer);
