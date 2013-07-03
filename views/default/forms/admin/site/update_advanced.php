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

$is_simple_cache_on = (bool)elgg_get_config('simplecache_enabled');
$form_body .= '<fieldset class="elgg-fieldset">';
$form_body .= '<legend>' . elgg_echo('admin:legend:caching') . '</legend>';
$form_body .= "<div>" . elgg_echo('installation:simplecache:description') . "<br />";
$form_body .= elgg_view("input/checkbox", array(
	'label' => elgg_echo('installation:simplecache:label'),
	'name' => 'simplecache_enabled',
	'checked' => $is_simple_cache_on,
	'class' => 'elgg-toggle-input',
	'data-toggle-input' => json_encode(array('simplecache_minify_js', 'simplecache_minify_css')),
)) . "</div>";

$form_body .= "<div>" . elgg_echo('installation:minify:description') . "<br />";
$form_body .= elgg_view("input/checkbox", array(
	'label' => elgg_echo('installation:minify_js:label'),
	'name' => 'simplecache_minify_js',
	'checked' => (bool)elgg_get_config('simplecache_minify_js'),
	'disabled' => !$is_simple_cache_on,
)) . '<br />';
$form_body .= elgg_view("input/checkbox", array(
	'label' => elgg_echo('installation:minify_css:label'),
	'name' => 'simplecache_minify_css',
	'checked' => (bool)elgg_get_config('simplecache_minify_css'),
	'disabled' => !$is_simple_cache_on,
)) . "</div>";

$form_body .= "<div>" . elgg_echo('installation:systemcache:description') . "<br />";
$form_body .= elgg_view("input/checkbox", array(
	'label' => elgg_echo('installation:systemcache:label'),
	'name' => 'system_cache_enabled',
	'checked' => (bool)elgg_get_config('system_cache_enabled'),
)) . "</div>";
$form_body .= "</fieldset>";

$form_body .= '<fieldset class="elgg-fieldset">';
$form_body .= '<legend>' . elgg_echo('admin:legend:content_access') . '</legend>';
$form_body .= "<div>" . elgg_echo('admin:site:access:warning') . "<br />";
$form_body .= "<label>" . elgg_echo('installation:sitepermissions') . " </label>";
$form_body .= elgg_view('input/access', array(
	'options_values' => array(
		ACCESS_PRIVATE => elgg_echo("PRIVATE"),
		ACCESS_FRIENDS => elgg_echo("access:friends:label"),
		ACCESS_LOGGED_IN => elgg_echo("LOGGED_IN"),
		ACCESS_PUBLIC => elgg_echo("PUBLIC"),
	),
	'name' => 'default_access',
	'value' => elgg_get_config('default_access'),
)) . "</div>";

$form_body .= "<div>" . elgg_echo('installation:allow_user_default_access:description') . "<br />";

$form_body .= elgg_view('input/checkbox', array(
	'label' => elgg_echo('installation:allow_user_default_access:label'),
	'name' => 'allow_user_default_access',
	'checked' => (bool)elgg_get_config('allow_user_default_access'),
)) . "</div>";
$form_body .= "</fieldset>";

$form_body .= '<fieldset class="elgg-fieldset">';
$form_body .= '<legend>' . elgg_echo('admin:legend:site_access') . '</legend>';
// control new user registration
$form_body .= '<div>' . elgg_echo('installation:registration:description') . '<br />';
$form_body .= elgg_view('input/checkbox', array(
	'label' => elgg_echo('installation:registration:label'),
	'name' => 'allow_registration',
	'checked' => (bool)elgg_get_config('allow_registration'),
)) . '</div>';

// control walled garden
$form_body .= '<div>' . elgg_echo('installation:walled_garden:description') . '<br />';
$form_body .= elgg_view('input/checkbox', array(
	'label' => elgg_echo('installation:walled_garden:label'),
	'name' => 'walled_garden',
	'checked' => (bool)elgg_get_config('walled_garden'),
)) . '</div>';

$form_body .= "<div>" . elgg_echo('installation:httpslogin') . "<br />";
$form_body .= elgg_view("input/checkbox", array(
	'label' => elgg_echo('installation:httpslogin:label'),
	'name' => 'https_login',
	'checked' => (bool)elgg_get_config('https_login'),
)) . "</div>";
$form_body .= "</fieldset>";

$debug_options = array(
	'0' => elgg_echo('installation:debug:none'),
	'ERROR' => elgg_echo('installation:debug:error'),
	'WARNING' => elgg_echo('installation:debug:warning'),
	'NOTICE' => elgg_echo('installation:debug:notice'),
	'INFO' => elgg_echo('installation:debug:info'),
);
$form_body .= '<fieldset class="elgg-fieldset">';
$form_body .= '<legend>' . elgg_echo('admin:legend:debug') . '</legend>';
$form_body .= "<div>" . elgg_echo('installation:debug') . "<br />";
$form_body .= "<label>" . elgg_echo('installation:debug:label') . " </label>";
$form_body .= elgg_view('input/select', array(
	'options_values' => $debug_options,
	'name' => 'debug',
	'value' => elgg_get_config('debug'),
));
$form_body .= '</div>';
$form_body .= "</fieldset>";


$form_body .= elgg_view('input/hidden', array('name' => 'settings', 'value' => 'go'));

$form_body .= '<div class="elgg-foot">';
$form_body .= elgg_view('input/submit', array('value' => elgg_echo("save")));
$form_body .= '</div>';

echo $form_body;
