<?php
/**
 * Elgg administration site advanced settings
 *
 * @package Elgg
 * @subpackage Core
 */

$action = elgg_get_site_url() . "action/admin/site/update_advanced";

$form_body = "";

foreach(array('wwwroot', 'path', 'dataroot', 'view') as $field) {
	$form_body .= "<p>";
	$form_body .= elgg_echo('installation:' . $field) . "<br />";
	$warning = elgg_echo('installation:warning:' . $field);
	if ($warning != 'installation:warning:' . $field) echo "<b>" . $warning . "</b><br />";
	$value = $vars['config']->$field;
	$form_body .= elgg_view("input/text",array('internalname' => $field, 'value' => $value));
	$form_body .= "</p>";
}

$form_body .= "<p>" . elgg_echo('admin:site:access:warning') . "<br />";
$form_body .= elgg_echo('installation:sitepermissions') . elgg_view('input/access', array('internalname' => 'default_access','value' => $vars['config']->default_access)) . "</p>";
$form_body .= "<p>" . elgg_echo('installation:allow_user_default_access:description') . "<br />" .elgg_view("input/checkboxes", array('options' => array(elgg_echo('installation:allow_user_default_access:label')), 'internalname' => 'allow_user_default_access', 'value' => ($vars['config']->allow_user_default_access ? elgg_echo('installation:allow_user_default_access:label') : "") )) . "</p>";
$form_body .= "<p>" . elgg_echo('installation:simplecache:description') . "<br />" .elgg_view("input/checkboxes", array('options' => array(elgg_echo('installation:simplecache:label')), 'internalname' => 'simplecache_enabled', 'value' => ($vars['config']->simplecache_enabled ? elgg_echo('installation:simplecache:label') : "") )) . "</p>";
$form_body .= "<p>" . elgg_echo('installation:viewpathcache:description') . "<br />" .elgg_view("input/checkboxes", array('options' => array(elgg_echo('installation:viewpathcache:label')), 'internalname' => 'viewpath_cache_enabled', 'value' => (($vars['config']->viewpath_cache_enabled) ? elgg_echo('installation:viewpathcache:label') : "") )) . "</p>";

$debug_options = array('0' => elgg_echo('installation:debug:none'), 'ERROR' => elgg_echo('installation:debug:error'), 'WARNING' => elgg_echo('installation:debug:warning'), 'NOTICE' => elgg_echo('installation:debug:notice'));
$form_body .= "<p>" . elgg_echo('installation:debug');
$form_body .= elgg_view('input/pulldown', array('options_values' => $debug_options, 'internalname' => 'debug', 'value' => $vars['config']->debug));
$form_body .= '</p>';

// control new user registration
$options = array(
	'options' => array(elgg_echo('installation:registration:label')),
	'internalname' => 'allow_registration',
	'value' => $vars['config']->allow_registration ? elgg_echo('installation:registration:label') : '',
);
$form_body .= '<p>' . elgg_echo('installation:registration:description');
$form_body .= '<br />' .elgg_view('input/checkboxes', $options) . '</p>';

// control walled garden
$options = array(
	'options' => array(elgg_echo('installation:walled_garden:label')),
	'internalname' => 'walled_garden',
	'value' => $vars['config']->walled_garden ? elgg_echo('installation:walled_garden:label') : '',
);
$form_body .= '<p>' . elgg_echo('installation:walled_garden:description');
$form_body .= '<br />' . elgg_view('input/checkboxes', $options) . '</p>';

$form_body .= "<p>" . elgg_echo('installation:httpslogin') . "<br />" .elgg_view("input/checkboxes", array('options' => array(elgg_echo('installation:httpslogin:label')), 'internalname' => 'https_login', 'value' => ($vars['config']->https_login ? elgg_echo('installation:httpslogin:label') : "") )) . "</p>";

$form_body .= "<p>" . elgg_echo('installation:disableapi') . "<br />";
$on = elgg_echo('installation:disableapi:label');
if ((isset($CONFIG->disable_api)) && ($CONFIG->disable_api == true)) {
	$on = ($vars['config']->disable_api ?  "" : elgg_echo('installation:disableapi:label'));
}
$form_body .= elgg_view("input/checkboxes", array('options' => array(elgg_echo('installation:disableapi:label')), 'internalname' => 'api', 'value' => $on ));
$form_body .= "</p>";

$form_body .= elgg_view('input/hidden', array('internalname' => 'settings', 'value' => 'go'));

$form_body .= "<div class='divider'></div>".elgg_view('input/submit', array('value' => elgg_echo("save")));
$form_body = "<div class='admin_settings site_admin margin-top'>".$form_body."</div>";
echo elgg_view('input/form', array('action' => $action, 'body' => $form_body));
