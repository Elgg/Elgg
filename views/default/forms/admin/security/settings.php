<?php

// hardening
$hardening = '';
// protect upgrade.php
$protect_upgrade = (bool) elgg_get_config('security_protect_upgrade');
$hardening .= elgg_view_field([
	'#type' => 'checkbox',
	'label' => elgg_echo('admin:security:settings:protect_upgrade'),
	'#help' => elgg_echo('admin:security:settings:protect_upgrade:help'),
	'name' => 'security_protect_upgrade',
	'default' => 0,
	'value' => 1,
	'switch' => true,
	'checked' => $protect_upgrade,
]);
if ($protect_upgrade) {
	$url = elgg_http_get_signed_url('upgrade.php');
	$url = elgg_format_element('pre', [], $url);
	
	$hardening .= elgg_format_element('div', [
		'class' => 'elgg-divide-left plm',
	], elgg_echo('admin:security:settings:protect_upgrade:token') . $url);
}

// protect /cron
$protect_cron = (bool) elgg_get_config('security_protect_cron');
$hardening .= elgg_view_field([
	'#type' => 'checkbox',
	'label' => elgg_echo('admin:security:settings:protect_cron'),
	'#help' => elgg_echo('admin:security:settings:protect_cron:help'),
	'name' => 'security_protect_cron',
	'default' => 0,
	'value' => 1,
	'switch' => true,
	'checked' => $protect_cron,
]);
if ($protect_cron) {
	$periods = elgg_get_config('elgg_cron_periods');
	$rows = [];
	
	// header for table
	$cells = [];
	$cells[] = elgg_format_element('th', [], elgg_echo('admin:cron:period'));
	$cells[] = elgg_format_element('th', [], 'URL');
	
	$rows[] = elgg_format_element('tr', [], implode('', $cells));
	
	// add inverval urls
	foreach ($periods as $period) {
		$cells = [];
		
		$cells[] = elgg_format_element('td', [], elgg_echo("interval:{$period}"));
		$cells[] = elgg_format_element('td', [], elgg_http_get_signed_url("cron/{$period}"));
		
		$rows[] = elgg_format_element('tr', [], implode('', $cells));
	}
	
	// cron url table
	$table = elgg_format_element('table', [
		'id' => 'security-cron-urls',
		'class' => 'elgg-table mvm hidden',
	], implode('', $rows));
	
	$content = elgg_echo('admin:security:settings:protect_cron:token');
	$content .= ' ' . elgg_view('output/url', [
		'text' => elgg_echo('admin:security:settings:protect_cron:toggle'),
		'href' => '#security-cron-urls',
		'rel' => 'toggle',
	]);
	$content .= $table;
	
	$hardening .= elgg_format_element('div', [
		'class' => 'elgg-divide-left plm mbm',
	], $content);
}

// disable autocomplete on password forms
$hardening .= elgg_view_field([
	'#type' => 'checkbox',
	'label' => elgg_echo('admin:security:settings:disable_password_autocomplete'),
	'#help' => elgg_echo('admin:security:settings:disable_password_autocomplete:help'),
	'name' => 'security_disable_password_autocomplete',
	'default' => 0,
	'value' => 1,
	'switch' => true,
	'checked' => (bool) elgg_get_config('security_disable_password_autocomplete'),
]);

// require password the changing email address
$hardening .= elgg_view_field([
	'#type' => 'checkbox',
	'label' => elgg_echo('admin:security:settings:email_require_password'),
	'#help' => elgg_echo('admin:security:settings:email_require_password:help'),
	'name' => 'security_email_require_password',
	'default' => 0,
	'value' => 1,
	'switch' => true,
	'checked' => (bool) elgg_get_config('security_email_require_password'),
]);

// session bound entity icons
$hardening .= elgg_view_field([
	'#type' => 'checkbox',
	'label' => elgg_echo('admin:security:settings:session_bound_entity_icons'),
	'#help' => elgg_echo('admin:security:settings:session_bound_entity_icons:help'),
	'name' => 'session_bound_entity_icons',
	'default' => 0,
	'value' => 1,
	'switch' => true,
	'checked' => (bool) elgg_get_config('session_bound_entity_icons'),
]);

// allow others to extend this section
$hardening .= elgg_view('admin/security/settings/extend/hardening');

echo elgg_view_module('info', elgg_echo('admin:security:settings:label:hardening'), $hardening);

// notifications
$notifications = '';
// notify admins about add/remove of another admin
$notifications .= elgg_view_field([
	'#type' => 'checkbox',
	'label' => elgg_echo('admin:security:settings:notify_admins'),
	'#help' => elgg_echo('admin:security:settings:notify_admins:help'),
	'name' => 'security_notify_admins',
	'default' => 0,
	'value' => 1,
	'switch' => true,
	'checked' => (bool) elgg_get_config('security_notify_admins'),
]);

// notify user about add/remove admin of his/her account
$notifications .= elgg_view_field([
	'#type' => 'checkbox',
	'label' => elgg_echo('admin:security:settings:notify_user_admin'),
	'#help' => elgg_echo('admin:security:settings:notify_user_admin:help'),
	'name' => 'security_notify_user_admin',
	'default' => 0,
	'value' => 1,
	'switch' => true,
	'checked' => (bool) elgg_get_config('security_notify_user_admin'),
]);

// notify user about (un)ban of his/her account
$notifications .= elgg_view_field([
	'#type' => 'checkbox',
	'label' => elgg_echo('admin:security:settings:notify_user_ban'),
	'#help' => elgg_echo('admin:security:settings:notify_user_ban:help'),
	'name' => 'security_notify_user_ban',
	'default' => 0,
	'value' => 1,
	'switch' => true,
	'checked' => (bool) elgg_get_config('security_notify_user_ban'),
]);

// allow others to extend this section
$notifications .= elgg_view('admin/security/settings/extend/notification');

echo elgg_view_module('info', elgg_echo('admin:security:settings:label:notifications'), $notifications);

// site secret
$strength = _elgg_get_site_secret_strength();
$current_strength = elgg_echo('site_secret:current_strength');
$strength_text = elgg_echo("site_secret:strength:$strength");
$strength_msg = elgg_echo("site_secret:strength_msg:$strength");

$site_secret = elgg_view('output/longtext', [
	'value' => elgg_echo('admin:security:settings:site_secret:intro'),
]);
$message_type = ($strength != 'strong') ? 'error' : 'success';
$site_secret .= elgg_view_message($message_type, $strength_msg, [
	'title' => "$current_strength: $strength_text",
]);

$site_secret_link = elgg_view('output/url', [
	'text' => elgg_echo('admin:security:settings:site_secret:regenerate'),
	'href' => 'action/admin/security/regenerate_site_secret',
	'confirm' => true,
	'class' => 'elgg-button elgg-button-action',
]);

$site_secret_link .= elgg_view('output/longtext', [
	'value' => elgg_echo('admin:security:settings:site_secret:regenerate:help'),
	'class' => 'elgg-subtext',
]);

$site_secret .= elgg_format_element('div', ['class' => 'mtm'], $site_secret_link);

echo elgg_view_module('info', elgg_echo('admin:security:settings:label:site_secret'), $site_secret);

// footer
$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('save'),
]);

elgg_set_form_footer($footer);
