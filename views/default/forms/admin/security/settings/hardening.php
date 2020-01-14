<?php
/**
 * Security settings subview - misc hardening settings
 *
 * @since 3.2
 */

$hardening = '';

// protect upgrade.php
$protect_upgrade = (bool) elgg_get_config('security_protect_upgrade');
$hardening .= elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('admin:security:settings:protect_upgrade'),
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
	'#label' => elgg_echo('admin:security:settings:protect_cron'),
	'#help' => elgg_echo('admin:security:settings:protect_cron:help'),
	'name' => 'security_protect_cron',
	'default' => 0,
	'value' => 1,
	'switch' => true,
	'checked' => $protect_cron,
]);
if ($protect_cron) {
	$periods = _elgg_services()->cron->getConfiguredIntervals(true);
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
	'#label' => elgg_echo('admin:security:settings:disable_password_autocomplete'),
	'#help' => elgg_echo('admin:security:settings:disable_password_autocomplete:help'),
	'name' => 'security_disable_password_autocomplete',
	'default' => 0,
	'value' => 1,
	'switch' => true,
	'checked' => (bool) elgg_get_config('security_disable_password_autocomplete'),
]);

// session bound entity icons
$hardening .= elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('admin:security:settings:session_bound_entity_icons'),
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
