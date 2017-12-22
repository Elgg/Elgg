<?php
/**
 * Log browser search form
 *
 * @package ElggLogBrowser
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
	'rel' => 'toggle',
]);

$toggle_link = elgg_format_element('div', [], $toggle_link);

$module_options = ['id' => 'log-browser-search-form'];
if (!isset($vars['user_guid']) && !isset($vars['username'])) {
	$module_options['class'] = 'hidden';
}

$module = elgg_view_module('inline', elgg_echo('logbrowser:search'), $form, $module_options);
?>
<div id="logbrowser-search-area" class="mbm">
	<?php echo $toggle_link . $module; ?>
</div>
