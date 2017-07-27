<?php
/**
 * This is a demo for using Elgg without its data or settings file.
 */

die('disabled');

use Symfony\Component\HttpFoundation\Session\Session;
use Elgg\Project\NoDataElgg;

date_default_timezone_set('UTC');

$autoload_path = __DIR__ . '/../vendor/autoload.php';
$autoload_available = include_once($autoload_path);
if (!$autoload_available) {
	die("Couldn't include '$autoload_path'. Did you run `composer install`?");
}

$elgg = new NoDataElgg((object) [
	'site_secret' => 'zThisIsABadSecretIMeanReallyBad',
	'symfony_session' => new Session(), // or use mock
	'site_name' => 'No-data Elgg',
	'site_description' => 'Demo of Elgg running without data sources',
	'__DIR__' => __DIR__,
]);
$elgg->setup();

// PLUGIN LOGIC /////////////////////////////////

// No core menus are defined
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

// Don't allow logins. TODO do this better.
elgg_register_plugin_hook_handler('view_vars', 'core/account/login_dropdown', function () {
	return ['__view_output' => ""];
});

// We must define most routes
elgg_register_page_handler('', function () {
	echo elgg_view_resource('index');
	return true;
});

// START ROUTING /////////////////////////////////

return $elgg->run();
