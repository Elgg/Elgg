<?php
/**
 * Welcome widget for admins
 */

$results = elgg_format_element('p', [], elgg_echo('admin:widget:admin_welcome:intro'));

if (!elgg_get_config('allow_registration')) {
	// registration is disabled, tell the admin
	$results .= elgg_view_message('warning', elgg_echo('admin:widget:admin_welcome:registration', [
		elgg_view_url(elgg_generate_url('admin', ['segments' => 'site_settings']), elgg_echo('admin:site_settings')),
	]));
}

$results .= elgg_format_element('p', [], elgg_echo('admin:widget:admin_welcome:admin_overview'));

if (elgg_is_active_plugin('custom_index')) {
	$results .= elgg_view_message('notice', elgg_echo('admin:widget:admin_welcome:custom_index', [
		elgg_view_url(elgg_generate_url('admin:plugin_settings', ['plugin_id' => 'custom_index']), elgg_echo('admin:plugin_settings')),
	]));
}

$results .= elgg_format_element('p', [], elgg_echo('admin:widget:admin_welcome:outro'));

echo elgg_view('output/longtext', ['value' => $results, 'sanitize' => false]);
