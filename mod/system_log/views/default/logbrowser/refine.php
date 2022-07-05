<?php
/**
 * Log browser search form
 */

$form_vars = [
	'method' => 'get',
	'action' => 'admin/administer_utilities/logbrowser',
	'disable_security' => true,
];
$form = elgg_view_form('logbrowser/refine', $form_vars, $vars);

$toggle_link = elgg_view('output/url', [
	'href' => '#log-browser-search-form',
	'text' => elgg_echo('logbrowser:search'),
	'class' => 'elgg-toggle',
]);

$toggle_link = elgg_format_element('div', [], $toggle_link);
$module_options = ['id' => 'log-browser-search-form'];

$show_form_hidden = true;
foreach (['timeupper', 'timelower', 'ip_address', 'username', 'object_id'] as $field) {
	if (!elgg_is_empty(elgg_extract($field, $vars))) {
		$show_form_hidden = false;
		break;
	}
}

if ($show_form_hidden) {
	$module_options['class'] = 'hidden';
}

$module = elgg_view_module('inline', elgg_echo('logbrowser:search'), $form, $module_options);

echo elgg_format_element('div', ['id' => 'logbrowser-search-area', 'class' => 'mbm'], $toggle_link . $module);
