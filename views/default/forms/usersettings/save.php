<?php
/**
 * User account settings.
 *
 * Plugins should extend "forms/account/settings" to add to the settings.
 */

$user = elgg_get_page_owner_entity();

$form_body = elgg_view('forms/account/settings', $vars);

$submit = elgg_view('input/submit', ['value' => elgg_echo('save')]);
$hidden = '';

if ($user) {
	// we need to include the user GUID so that admins can edit the settings of other users
	$hidden = elgg_view('input/hidden', ['name' => 'guid', 'value' => $user->guid]);
}

$form_body .= elgg_format_element('div', ['class' => 'elgg-foot'], $submit . $hidden);

echo $form_body;