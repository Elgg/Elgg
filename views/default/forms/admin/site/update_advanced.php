<?php 
/**
 * @todo cleanup
 */
$form_body = "";

foreach (array('wwwroot', 'path', 'dataroot') as $field) {
	$form_body .= "<div>";
	$form_body .= elgg_echo('installation:' . $field) . "<br />";
	$warning = elgg_echo('installation:warning:' . $field);
	if ($warning != 'installation:warning:' . $field) {
		echo "<b>" . $warning . "</b><br />";
	}
	$value = elgg_get_config($field);
	$form_body .= elgg_view("input/text",array('name' => $field, 'value' => $value));
	$form_body .= "</div>";
}

$form_body .= "<div>" . elgg_echo('admin:site:access:warning') . "<br />";
$form_body .= "<label>" . elgg_echo('installation:sitepermissions') . "</label>";
$form_body .= elgg_view('input/access', array(
	'options_values' => array(
		ACCESS_PRIVATE => elgg_echo("PRIVATE"),
		ACCESS_FRIENDS => elgg_echo("access:friends:label"),
		ACCESS_LOGGED_IN => elgg_echo("LOGGED_IN"),
		ACCESS_PUBLIC => elgg_echo("PUBLIC")
	),
	'name' => 'default_access',
	'value' => elgg_get_config('default_access'),
)) . "</div>";
$form_body .= "<div>" . elgg_echo('installation:allow_user_default_access:description') . "<br />";
$form_body .= elgg_view("input/checkboxes", array(
	'options' => array(elgg_echo('installation:allow_user_default_access:label') => 1),
	'name' => 'allow_user_default_access',
	'value' => (elgg_get_config('allow_user_default_access') ? 1 : 0),
)) . "</div>";
$form_body .= "<div>" . elgg_echo('installation:simplecache:description') . "<br />";
$form_body .= elgg_view("input/checkboxes", array(
	'options' => array(elgg_echo('installation:simplecache:label') => 1),
	'name' => 'simplecache_enabled',
	'value' => (elgg_get_config('simplecache_enabled') ? 1 : 0),
)) . "</div>";
$form_body .= "<div>" . elgg_echo('installation:systemcache:description') . "<br />";
$form_body .= elgg_view("input/checkboxes", array(
	'options' => array(elgg_echo('installation:systemcache:label') => 1),
	'name' => 'system_cache_enabled',
	'value' => (elgg_get_config('system_cache_enabled') ? 1 : 0),
)) . "</div>";

$debug_options = array('0' => elgg_echo('installation:debug:none'), 'ERROR' => elgg_echo('installation:debug:error'), 'WARNING' => elgg_echo('installation:debug:warning'), 'NOTICE' => elgg_echo('installation:debug:notice'));
$form_body .= "<div>" . elgg_echo('installation:debug');
$form_body .= elgg_view('input/dropdown', array(
	'options_values' => $debug_options,
	'name' => 'debug',
	'value' => elgg_get_config('debug'),
));
$form_body .= '</div>';

// control new user registration
$options = array(
	'options' => array(elgg_echo('installation:registration:label') => 1),
	'name' => 'allow_registration',
	'value' => elgg_get_config('allow_registration') ? 1 : 0,
);
$form_body .= '<div>' . elgg_echo('installation:registration:description');
$form_body .= '<br />' .elgg_view('input/checkboxes', $options) . '</div>';

// control walled garden
$walled_garden = elgg_get_config(walled_garden);
$options = array(
	'options' => array(elgg_echo('installation:walled_garden:label') => 1),
	'name' => 'walled_garden',
	'value' => $walled_garden ? 1 : 0,
);
$form_body .= '<div>' . elgg_echo('installation:walled_garden:description');
$form_body .= '<br />' . elgg_view('input/checkboxes', $options) . '</div>';

$form_body .= "<div>" . elgg_echo('installation:httpslogin') . "<br />";
$form_body .= elgg_view("input/checkboxes", array(
	'options' => array(elgg_echo('installation:httpslogin:label') => 1),
	'name' => 'https_login',
	'value' => (elgg_get_config('https_login') ? 1 : 0)
)) . "</div>";

$form_body .= "<div>" . elgg_echo('installation:disableapi') . "<br />";
$disable_api = elgg_get_config('disable_api');
$on = $disable_api ?  0 : 1;
$form_body .= elgg_view("input/checkboxes", array(
	'options' => array(elgg_echo('installation:disableapi:label') => 1),
	'name' => 'api',
	'value' => $on,
));
$form_body .= "</div>";

$form_body .= elgg_view('input/hidden', array('name' => 'settings', 'value' => 'go'));

$form_body .= '<div class="elgg-foot">';
$form_body .= elgg_view('input/submit', array('value' => elgg_echo("save")));
$form_body .= '</div>';

echo $form_body;
