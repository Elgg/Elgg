<?php
/**
 * Admin security settings
 */

echo elgg_view('forms/admin/security/settings/hardening', $vars);
echo elgg_view('forms/admin/security/settings/account', $vars);
echo elgg_view('forms/admin/security/settings/notifications', $vars);
echo elgg_view('forms/admin/security/settings/site_secret', $vars);

// footer
$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('save'),
]);

elgg_set_form_footer($footer);
