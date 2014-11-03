<?php
namespace Elgg;

use DI;
use DI\Container;
use Elgg\Queue\DatabaseQueue;
use Elgg\Notifications\NotificationsService;

return array(
	'access' => DI\link('Elgg\Access'),
	'autoloadManager' => DI\link('Elgg\AutoloadManager'),
	'actions' => DI\link('Elgg\ActionsService'),
	'amdConfig' => DI\link('Elgg\Amd\Config'),
	'autoP' => DI\link('ElggAutoP'),
	'config' => DI\factory(function() {
		global $CONFIG;
		if (!isset($CONFIG)) {
			$CONFIG = new \stdClass();
		}
		return $CONFIG;
	}),
	'context' => DI\link('Elgg\Context'),
	'cookies' => DI\factory(function() {
		return \elgg_get_config('cookies');
	}),
	'crypto' => DI\link('ElggCrypto'),
	'dataroot' => DI\factory(function(Container $c) {
		return $c->get('config')->dataroot;
	}),
	'db' => DI\link('Elgg\Database'),
	'events' => DI\link('Elgg\EventsService'),
	'hooks' => DI\link('Elgg\PluginHooksService'),
	'jsroot' => DI\factory(function(Container $c) {
		return \_elgg_get_simplecache_root() . "js/";
	}),
	'logger' => DI\link('Elgg\Logger'),
	'metadataCache' => DI\link('ElggVolatileMetadataCache'),
	'notifications' => DI\link('Elgg\Notifications\NotificationsService'),
	'ownerPreloader' => DI\object('Elgg\EntityPreloader')->constructor(array('owner_guid')),
	'persistentLogin' => DI\link('Elgg\PersistentLoginService'),
	'queryCounter' => DI\link('Elgg\Database\QueryCounter'),
	'request' => DI\link('Elgg\Http\Request'),
	'router' => DI\link('Elgg\Router'),
	'session' => DI\link('ElggSession'),
	'server' => DI\factory(function(Container $c) {
		return $c->get('request')->server;
	}),
	'systemCache' => DI\factory(function(Container $c) {
		$systemCache = new \ElggFileCache($c->get('dataroot') . "system_cache/");

		// TODO(ewinslow): Move this to the autoloadManager factory once timing issues are resolved
		$manager = $c->get('Elgg\AutoloadManager');
		$manager->setStorage($systemCache);
		$manager->loadCache();

		return $systemCache;
	}),
	'views' => DI\link('Elgg\ViewsService'),
	'widgets' => DI\link('Elgg\WidgetsService'),
	
	
	'Elgg\AmdConfig' => DI\object()->method('setBaseUrl', DI\link('jsroot')),
	'Elgg\ClassLoader' => DI\object()->method('register'),
	'Elgg\Database\Config' => DI\object()->constructor(DI\link('config')),
	'Elgg\Http\Request' => DI\factory(function() {
		return Http\Request::createFromGlobals();
	}),
	'Elgg\Http\SessionStorage' => DI\factory(function(Container $c) {
		$cookies = $c->get('cookies');
		
		// account for difference of session_get_cookie_params() and ini key names
		$params = $cookies['session'];
		foreach ($params as $key => $value) {
			if (in_array($key, array('path', 'domain', 'secure', 'httponly'))) {
				$params["cookie_$key"] = $value;
				unset($params[$key]);
			}
		}

		return new Http\NativeSessionStorage($params, $c->get('Elgg\Http\DatabaseSessionHandler'));
	}),
	'Elgg\PersistentLoginService' => DI\factory(function(Container $c) {
		$cookies_config = $c->get('cookies');
		$remember_me_cookies_config = $cookies_config['remember_me'];
		$cookie_name = $remember_me_cookies_config['name'];
		$cookie_token = $c->get('request')->cookies->get($cookie_name, '');
		return new PersistentLoginService($c->get('db'), $c->get('session'), $c->get('crypto'), $remember_me_cookies_config, $cookie_token);
	}),
	'Elgg\Queue\Queue' => DI\factory(function(Container $c) {
		return new DatabaseQueue(NotificationsService::QUEUE_NAME, $c->get('Elgg\Database'));
	}),
);