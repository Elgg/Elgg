<?php
/**
 * User account settings.
 *
 * Plugins should extend this form to add to the settings.
 */

$user = elgg_get_page_owner_entity();
if (!$user instanceof ElggUser) {
	return;
}

if ($user) {
	// we need to include the user GUID so that admins can edit the settings of other users
	echo elgg_view_field([
		'#type' => 'hidden',
		'name' => 'guid',
		'value' => $user->guid,
	]);
}

$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('save'),
]);

elgg_set_form_footer($footer);
