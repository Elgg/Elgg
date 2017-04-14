<?php

use Symfony\Component\HttpFoundation\Session\Session;
use Elgg\Project\NoDataBoot;

date_default_timezone_set('UTC');

$autoload_path = __DIR__ . '/../vendor/autoload.php';
$autoload_available = include_once($autoload_path);
if (!$autoload_available) {
	die("Couldn't include '$autoload_path'. Did you run `composer install`?");
}

$boot = new NoDataBoot();
$boot->boot((object) [
	'site_secret' => 'zsHNgN6vQQtG6xUxBwh6srOFR3NB_L1',
	'symfony_session' => new Session(), // or use mock
	'wwwroot' => '', // sniff from request
	'site_name' => 'No-data Elgg',
	'site_description' => 'Demo of Elgg running without data sources',
	'__DIR__' => __DIR__,
]);

// PLUGIN LOGIC /////////////////////////////////

// no core menus are defined
elgg_register_menu_item('site', [
	'name' => 'en',
	'href' => '/',
	'text' => elgg_echo('en'),
]);
elgg_register_menu_item('site', [
	'name' => 'es',
	'href' => '/?hl=es',
	'text' => elgg_echo('es'),
]);

// no logins please
elgg_register_plugin_hook_handler('view_vars', 'core/account/login_dropdown', function () {
	return ['__view_output' => ""];
});

// only a few core routes are defined
elgg_register_page_handler('', function () {
	echo elgg_view_resource('index');
	return true;
});

// START ROUTING /////////////////////////////////

$boot->route();
