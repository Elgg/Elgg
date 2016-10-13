<?php
/**
 * User account settings.
 *
 * Plugins should extend "forms/account/settings" to add to the settings.
 */

$user = elgg_get_page_owner_entity();

echo elgg_view('forms/account/settings', $vars);

// we need to include the user GUID so that admins can edit the settings of other users
echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'guid',
	'value' => $user ? $user->guid : '',
]);

$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('save'),
]);

elgg_set_form_footer($footer);