<?php
namespace Elgg\Di;

/**
 * Provides common Elgg services.
 *
 * We extend the container because it allows us to document properties in the PhpDoc, which assists
 * IDEs to auto-complete properties and understand the types returned. Extension allows us to keep
 * the container generic.
 *
 * @property-read \Elgg\Access                             $access
 * @property-read \Elgg\Database\AccessCollections         $accessCollections
 * @property-read \ElggStaticVariableCache                 $accessCache
 * @property-read \Elgg\ActionsService                     $actions
 * @property-read \Elgg\Database\AdminNotices              $adminNotices
 * @property-read \Elgg\Amd\Config                         $amdConfig
 * @property-read \Elgg\Database\Annotations               $annotations
 * @property-read \ElggAutoP                               $autoP
 * @property-read \Elgg\AutoloadManager                    $autoloadManager
 * @property-read \ElggCrypto                              $crypto
 * @property-read \Elgg\Config                             $config
 * @property-read \Elgg\Database\ConfigTable               $configTable
 * @property-read \Elgg\Database\Datalist                  $datalist
 * @property-read \Elgg\Database                           $db
 * @property-read \Elgg\Database\EntityTable               $entityTable
 * @property-read \Elgg\EventsService                      $events
 * @property-read \Elgg\Assets\ExternalFiles               $externalFiles
 * @property-read \Elgg\PluginHooksService                 $hooks
 * @property-read \Elgg\Http\Input                         $input
 * @property-read \Elgg\Logger                             $logger
 * @property-read \ElggVolatileMetadataCache               $metadataCache
 * @property-read \Elgg\Database\MetadataTable             $metadataTable
 * @property-read \Elgg\Database\MetastringsTable          $metastringsTable
 * @property-read \Elgg\Notifications\NotificationsService $notifications
 * @property-read \Elgg\EntityPreloader                    $ownerPreloader
 * @property-read \Elgg\PersistentLoginService             $persistentLogin
 * @property-read \Elgg\Database\Plugins                   $plugins
 * @property-read \Elgg\Database\QueryCounter              $queryCounter
 * @property-read \Elgg\Http\Request                       $request
 * @property-read \Elgg\Database\RelationshipsTable        $relationshipsTable
 * @property-read \Elgg\Router                             $router
 * @property-read \ElggSession                             $session
 * @property-read \Elgg\Cache\SimpleCache                  $simpleCache
 * @property-read \Elgg\Database\SiteSecret                $siteSecret
 * @property-read \Elgg\Forms\StickyForms                  $stickyForms
 * @property-read \Elgg\Database\SubtypeTable              $subtypeTable
 * @property-read \Elgg\Cache\SystemCache                  $systemCache
 * @property-read \Elgg\I18n\Translator                    $translator
 * @property-read \Elgg\Database\UsersTable                $usersTable
 * @property-read \Elgg\ViewsService                       $views
 * @property-read \Elgg\WidgetsService                     $widgets
 * 
 * @package Elgg.Core
 * @access private
 */
class ServiceProvider extends \Elgg\Di\DiContainer {

	/**
	 * Constructor
	 * 
	 * @param \Elgg\AutoloadManager $autoload_manager Class autoloader
	 */
	public function __construct(\Elgg\AutoloadManager $autoload_manager) {
		$this->setValue('autoloadManager', $autoload_manager);

		$this->setClassName('access', '\Elgg\Access');

		$this->setFactory('accessCache', function(ServiceProvider $c) {
			return new \ElggStaticVariableCache('access');
		});

		$this->setFactory('accessCollections', function(ServiceProvider $c) {
			return new \Elgg\Database\AccessCollections($c->config->get('site_guid'));
		});

		$this->setClassName('actions', '\Elgg\ActionsService');

		$this->setClassName('adminNotices', '\Elgg\Database\AdminNotices');

		$this->setFactory('amdConfig', function(\Elgg\Di\ServiceProvider $c) {
			$obj = new \Elgg\Amd\Config();
			$obj->setBaseUrl($c->simpleCache->getRoot() . "js/");
			return $obj;
		});

		$this->setClassName('annotations', '\Elgg\Database\Annotations');

		$this->setClassName('autoP', '\ElggAutoP');

		$this->setClassName('config', '\Elgg\Config');

		$this->setClassName('configTable', '\Elgg\Database\ConfigTable');

		$this->setClassName('context', '\Elgg\Context');

		$this->setClassName('crypto', '\ElggCrypto');

		$this->setFactory('datalist', function(\Elgg\Di\ServiceProvider $c) {
			// TODO(ewinslow): Add back memcached support
			$dbprefix = $c->config->get('dbprefix');
			return new \Elgg\Database\Datalist(
				new \Elgg\Cache\MemoryPool(), $c->db, $c->logger, "{$dbprefix}datalists");
		});

		$this->setFactory('db', function(\Elgg\Di\ServiceProvider $c) {
			global $CONFIG;
			return new \Elgg\Database(new \Elgg\Database\Config($CONFIG), $c->logger);
		});

		$this->setClassName('entityTable', '\Elgg\Database\EntityTable');

		$this->setClassName('externalFiles', '\Elgg\Assets\ExternalFiles');

		$this->setFactory('events', function(\Elgg\Di\ServiceProvider $c) {
			return $this->resolveLoggerDependencies('events');
		});

		$this->setFactory('hooks', function(\Elgg\Di\ServiceProvider $c) {
			return $this->resolveLoggerDependencies('hooks');
		});

		$this->setClassName('input', 'Elgg\Http\Input');

		$this->setFactory('logger', function(\Elgg\Di\ServiceProvider $c) {
			return $this->resolveLoggerDependencies('logger');
		});

		$this->setClassName('metadataCache', '\ElggVolatileMetadataCache');

		$this->setFactory('metadataTable', function(ServiceProvider $c) {
			// TODO(ewinslow): Use Elgg\Cache\Pool instead of MetadataCache
			return new \Elgg\Database\MetadataTable(
				$c->metadataCache, $c->db, $c->entityTable,
				$c->events, $c->metastringsTable, $c->session);
		});

		$this->setFactory('metastringsTable', function(ServiceProvider $c) {
			// TODO(ewinslow): Use memcache-based Pool if available...
			return new \Elgg\Database\MetastringsTable(
				new \Elgg\Cache\MemoryPool(), $c->db);
		});

		$this->setFactory('notifications', function(\Elgg\Di\ServiceProvider $c) {
			// @todo move queue in service provider
			$queue = new \Elgg\Queue\DatabaseQueue(\Elgg\Notifications\NotificationsService::QUEUE_NAME, $c->db);
			$sub = new \Elgg\Notifications\SubscriptionsService($c->db);
			$access = elgg_get_access_object();
			return new \Elgg\Notifications\NotificationsService($sub, $queue, $c->hooks, $access);
		});

		$this->setFactory('ownerPreloader', function(\Elgg\Di\ServiceProvider $c) {
			return new \Elgg\EntityPreloader(array('owner_guid'));
		});

		$this->setFactory('persistentLogin', function(\Elgg\Di\ServiceProvider $c) {
			$cookies_config = _elgg_services()->config->get('cookies');
			$remember_me_cookies_config = $cookies_config['remember_me'];
			$cookie_name = $remember_me_cookies_config['name'];
			$cookie_token = $c->request->cookies->get($cookie_name, '');
			return new \Elgg\PersistentLoginService($c->db, $c->session, $c->crypto, $remember_me_cookies_config, $cookie_token);
		});

		$this->setClassName('plugins', '\Elgg\Database\Plugins');

		$this->setFactory('queryCounter', function(\Elgg\Di\ServiceProvider $c) {
			return new \Elgg\Database\QueryCounter($c->db);
		}, false);

		$this->setClassName('relationshipsTable', '\Elgg\Database\RelationshipsTable');

		$this->setFactory('request', '\Elgg\Http\Request::createFromGlobals');

		$this->setFactory('router', function(\Elgg\Di\ServiceProvider $c) {
			// TODO(evan): Init routes from plugins or cache
			return new \Elgg\Router($c->hooks);
		});

		$this->setFactory('session', function(\Elgg\Di\ServiceProvider $c) {
			global $CONFIG;

			// account for difference of session_get_cookie_params() and ini key names
			$params = $CONFIG->cookies['session'];
			foreach ($params as $key => $value) {
				if (in_array($key, array('path', 'domain', 'secure', 'httponly'))) {
					$params["cookie_$key"] = $value;
					unset($params[$key]);
				}
			}

			$handler = new \Elgg\Http\DatabaseSessionHandler($c->db);
			$storage = new \Elgg\Http\NativeSessionStorage($params, $handler);
			$session = new \ElggSession($storage);

			return $session;
		});

		$this->setClassName('simpleCache', '\Elgg\Cache\SimpleCache');

		$this->setClassName('siteSecret', '\Elgg\Database\SiteSecret');

		$this->setClassName('stickyForms', 'Elgg\Forms\StickyForms');

		$this->setClassName('subtypeTable', '\Elgg\Database\SubtypeTable');

		$this->setClassName('systemCache', '\Elgg\Cache\SystemCache');

		$this->setClassName('translator', '\Elgg\I18n\Translator');

		$this->setClassName('usersTable', '\Elgg\Database\UsersTable');

		$this->setFactory('views', function(\Elgg\Di\ServiceProvider $c) {
			return new \Elgg\ViewsService($c->hooks, $c->logger);
		});

		$this->setClassName('widgets', '\Elgg\WidgetsService');

	}

	/**
	 * Returns the first requested service of the logger, events, and hooks. It sets the
	 * hooks and events up in the right order to prevent circular dependency.
	 *
	 * @param string $service_needed The service requested first
	 * @return mixed
	 */
	protected function resolveLoggerDependencies($service_needed) {
		$svcs['hooks'] = new \Elgg\PluginHooksService();
		$svcs['logger'] = new \Elgg\Logger($svcs['hooks']);
		$svcs['hooks']->setLogger($svcs['logger']);
		$svcs['events'] = new \Elgg\EventsService();
		$svcs['events']->setLogger($svcs['logger']);

		foreach ($svcs as $key => $service) {
			$this->setValue($key, $service);
		}
		return $svcs[$service_needed];
	}
}
