<?php
/**
 * Change the email address of an user
 *
 * @uses $vars['user_guid'] the GUID of the user to change
 */

$guid = (int) elgg_extract('user_guid', $vars);
$user = elgg_call(ELGG_SHOW_DISABLED_ENTITIES, function() use ($guid) {
	return get_user($guid);
});

if (!$user instanceof \ElggUser || !$user->canEdit()) {
	return;
}

echo elgg_view_title(elgg_echo('admin:users:unvalidated:change_email:user', [$user->getDisplayName()]), ['tag' => 'h2']);

echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'user_guid',
	'value' => $user->guid,
]);

echo elgg_view_field([
	'#type' => 'email',
	'#label' => elgg_echo('email:address:label'),
	'name' => 'email',
	'value' => $user->email,
	'required' => true,
]);

$footer = elgg_view_field([
	'#type' => 'submit',
	'text' => elgg_echo('save'),
]);

elgg_set_form_footer($footer);
