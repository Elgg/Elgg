<?php
/**
 * An overview of the server requirements and if they are met
 * similar to how it's checked during installation
 *
 * @see ElggInstaller::checkPHP()
 */

use Elgg\Http\Request;

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

// php version
$icon = $icon_ok;
$title = elgg_echo('admin:server:label:php_version');
$value = PHP_VERSION;
$subtext = '';

if (version_compare(PHP_VERSION, '7.1.0', '<')) {
	$icon = $icon_error;
	$subtext = elgg_echo('admin:server:label:php_version:required');
}

echo $view_module($icon, $title, $value, $subtext);

// php required extensions
$extensions = get_loaded_extensions();
$requiredExtensions = [
	'pdo_mysql',
	'json',
	'xml',
	'gd',
];
foreach ($requiredExtensions as $extension) {
	$icon = $icon_ok;
	$title = elgg_echo('admin:server:requirements:php_extension', [$extension]);
	$value = elgg_echo('status:enabled');
	$subtext = '';
	
	if (!in_array($extension, $extensions)) {
		$icon = $icon_error;
		$value = elgg_echo('status:unavailable');
		$subtext = elgg_echo('admin:server:requirements:php_extension:required');
	}
	
	echo $view_module($icon, $title, $value, $subtext);
}

// php recommended extensions
$recommendedExtensions = [
	'mbstring',
];
foreach ($recommendedExtensions as $extension) {
	$icon = $icon_ok;
	$title = elgg_echo('admin:server:requirements:php_extension', [$extension]);
	$value = elgg_echo('status:enabled');
	$subtext = '';
	
	if (!in_array($extension, $extensions)) {
		$icon = $icon_warning;
		$value = elgg_echo('status:unavailable');
		$subtext = elgg_echo('admin:server:requirements:php_extension:recommended');
	}
	
	echo $view_module($icon, $title, $value, $subtext);
}

// db server information
$db = elgg()->db->getConnection('read');
$version = $db->executeQuery('select version()')->fetchOne();
$server = $db->getDatabasePlatform()->getName();
$subtext = '';
$icon = $icon_ok;

if ($server !== 'mysql' || version_compare($version, '5.5.3', '<')) {
	$subtext = elgg_echo('admin:server:requirements:database:server:required');
	$icon = $icon_error;
}

echo $view_module($icon, elgg_echo('admin:server:requirements:database:server'), "{$server} v{$version}", $subtext);

// db client information
$client_parts = explode('\\', get_class($db->getDriver()));
$client_parts = array_slice($client_parts, 3);
$client = implode(' ', $client_parts);

$subtext = '';
$icon = $icon_ok;

if ($client !== 'PDO MySQL Driver') {
	$subtext = elgg_echo('admin:server:requirements:database:client:required');
	$icon = $icon_error;
}
echo $view_module($icon, elgg_echo('admin:server:requirements:database:client'), $client, $subtext);

// rewrite test
$url = elgg_http_add_url_query_elements(Request::REWRITE_TEST_TOKEN, [
	Request::REWRITE_TEST_TOKEN => 1,
]);
$url = elgg_normalize_site_url($url);

$tester = new ElggRewriteTester();

$icon = $icon_ok;
$title = elgg_echo('admin:server:requirements:rewrite');
$value = elgg_echo('ok');
$subtext = '';

if (!$tester->runRewriteTest($url)) {
	$icon = $icon_error;
	$value = elgg_echo('error');
	$subtext = elgg_echo('admin:server:requirements:rewrite:fail');
}

echo $view_module($icon, $title, $value, $subtext);
