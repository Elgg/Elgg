<?php
/**
 * Admin security information page
 * Lists general security recommendations
 */

use Elgg\Project\Paths;

$params = $vars;
$params['selected'] = 'information';
echo elgg_view('admin/security/tabs', $params);

echo elgg_view('output/longtext', [
	'value' => elgg_echo('admin:security:information:description'),
]);

$icon_ok = elgg_view_icon('check');
$icon_warning = elgg_view_icon('exclamation-triangle');
$icon_error = elgg_view_icon('times');

$view_module = function($icon, $title, $value = '', $subtext = '') {
	$body = elgg_format_element('strong', [], $title);
	if (!elgg_is_empty($value)) {
		$body .= elgg_format_element('span', ['class' => 'mlm'], $value);
	}
	
	if (!elgg_is_empty($subtext)) {
		$body .= elgg_format_element('div', ['class' => 'elgg-subtext'], $subtext);
	}
	
	return elgg_view_image_block($icon, $body, ['class' => 'elgg-admin-information-row']);
};

// https
$icon = $icon_ok;
$title = elgg_echo('admin:security:information:https');
$value = elgg_echo('option:yes');
$subtext = '';

if (parse_url(elgg_get_site_url(), PHP_URL_SCHEME) !== 'https') {
	$icon = $icon_warning;
	$value = elgg_echo('option:no');
	$subtext = elgg_echo('admin:security:information:https:warning');
}

echo $view_module($icon, $title, $value, $subtext);

// wwwroot writeable
$icon = $icon_ok;
$title = elgg_echo('admin:security:information:wwwroot');
$value = elgg_echo('option:no');
$subtext = '';

if (is_writable(Paths::project())) {
	$icon = $icon_error;
	$value = elgg_echo('option:yes');
	$subtext = elgg_echo('admin:security:information:wwwroot:error');
}

echo $view_module($icon, $title, $value, $subtext);

// hooks on 'validate', 'input' (eg htmlawed)
$icon = $icon_ok;
$title = elgg_echo('admin:security:information:validate_input');
$value = elgg_echo('status:enabled');
$subtext = '';

if (!(bool) elgg()->hooks->getOrderedHandlers('validate', 'input')) {
	$icon = $icon_error;
	$value = elgg_echo('status:disabled');
	$subtext = elgg_echo('admin:security:information:validate_input:error');
}

echo $view_module($icon, $title, $value, $subtext);

// password length
$icon = $icon_ok;
$title = elgg_echo('admin:security:information:password_length');
$value = elgg_get_config('min_password_length');
$subtext = '';

if ($value < 6) {
	$icon = $icon_warning;
	$subtext = elgg_echo('admin:security:information:password_length:warning');
}

echo $view_module($icon, $title, $value, $subtext);

// username length
$icon = $icon_ok;
$title = elgg_echo('admin:security:information:username_length');
$value = elgg_get_config('minusername');
$subtext = '';

if ($value < 4) {
	$icon = $icon_warning;
	$subtext = elgg_echo('admin:security:information:username_length:warning');
}

echo $view_module($icon, $title, $value, $subtext);

// site secret
$icon = $icon_ok;
$title = elgg_view('output/url', [
	'text' => elgg_echo('admin:security:settings:label:site_secret'),
	'href' => elgg_generate_url('admin', [
		'segments' => 'security',
	]) . '#admin-security-site-secret',
	'is_trusted' => true,
]);
$subtext = '';

$strength = _elgg_get_site_secret_strength();
$value = elgg_echo("site_secret:strength:$strength");

if ($strength !== 'strong') {
	$icon = $icon_error;
	
	$subtext = elgg_echo("site_secret:strength_msg:$strength");
}

echo $view_module($icon, $title, $value, $subtext);

// php session garbage collection
$icon = $icon_error;
$title = elgg_echo('admin:security:information:php:session_gc');
$value = elgg_echo('status:disabled');
$subtext = elgg_echo('admin:security:information:php:session_gc:error');

$probability = ini_get('session.gc_probability');
$divisor = ini_get('session.gc_divisor');
$maxlifetime = ini_get('session.gc_maxlifetime');

if ($probability > 0 && $divisor > 0) {
	$icon = $icon_ok;
	$value = elgg_echo('status:enabled');
	
	$chance = $probability / $divisor;
	$subtext = elgg_echo('admin:security:information:php:session_gc:chance', [$chance]);
	$subtext .= ' ' . elgg_echo('admin:security:information:php:session_gc:lifetime', [$maxlifetime]);
}

echo $view_module($icon, $title, $value, $subtext);

// Check for .htaccess hardening
$icon = $icon_warning;
$title = elgg_echo('admin:security:information:htaccess:hardening');
$value = elgg_echo('status:disabled');
$subtext = elgg_echo('admin:security:information:htaccess:hardening:help');

$curl = curl_init(elgg_normalize_site_url('vendor/autoload.php'));
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_exec($curl);

if (curl_getinfo($curl, CURLINFO_HTTP_CODE) === 403) {
	// hardening enabled
	$icon = $icon_ok;
	$value = elgg_echo('status:enabled');
}

echo $view_module($icon, $title, $value, $subtext);
