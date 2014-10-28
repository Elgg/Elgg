<?php

/**
 * Provides common Elgg services.
 *
 * We extend the container because it allows us to document properties in the PhpDoc, which assists
 * IDEs to auto-complete properties and understand the types returned. Extension allows us to keep
 * the container generic.
 *
 * @property-read Elgg_ActionsService                     $actions
 * @property-read Elgg_Amd_Config                         $amdConfig
 * @property-read ElggAutoP                               $autoP
 * @property-read Elgg_AutoloadManager                    $autoloadManager
 * @property-read ElggCrypto                              $crypto
 * @property-read Elgg_Database                           $db
 * @property-read Elgg_EventsService                      $events
 * @property-read Elgg_PluginHooksService                 $hooks
 * @property-read Elgg_Logger                             $logger
 * @property-read ElggVolatileMetadataCache               $metadataCache
 * @property-read Elgg_Notifications_NotificationsService $notifications
 * @property-read Elgg_PersistentLoginService             $persistentLogin
 * @property-read Elgg_Database_QueryCounter              $queryCounter
 * @property-read Elgg_Http_Request                       $request
 * @property-read Elgg_Router                             $router
 * @property-read ElggSession                             $session
 * @property-read Elgg_ViewsService                       $views
 * @property-read Elgg_WidgetsService                     $widgets
 *
 * @package Elgg.Core
 * @access private
 */
class Elgg_Di_ServiceProvider extends Elgg_Di_DiContainer {

	/**
	 * Constructor
	 *
	 * @param Elgg_AutoloadManager $autoload_manager Class autoloader
	 */
	public function __construct(Elgg_AutoloadManager $autoload_manager) {
		$this->setValue('autoloadManager', $autoload_manager);

		$this->setClassName('actions', 'Elgg_ActionsService');
		$this->setFactory('amdConfig', array($this, 'getAmdConfig'));
		$this->setClassName('autoP', 'ElggAutoP');
		$this->setClassName('crypto', 'ElggCrypto');
		$this->setFactory('db', array($this, 'getDatabase'));
		$this->setFactory('events', array($this, 'getEvents'));
		$this->setFactory('hooks', array($this, 'getHooks'));
		$this->setFactory('logger', array($this, 'getLogger'));
		$this->setClassName('metadataCache', 'ElggVolatileMetadataCache');
		$this->setFactory('persistentLogin', array($this, 'getPersistentLogin'));
		$this->setFactory('queryCounter', array($this, 'getQueryCounter'), false);
		$this->setFactory('request', array($this, 'getRequest'));
		$this->setFactory('router', array($this, 'getRouter'));
		$this->setFactory('session', array($this, 'getSession'));
		$this->setFactory('views', array($this, 'getViews'));
		$this->setClassName('widgets', 'Elgg_WidgetsService');
		$this->setFactory('notifications', array($this, 'getNotifications'));
	}

	/**
	 * Database factory
	 *
	 * @param Elgg_Di_ServiceProvider $c Dependency injection container
	 * @return Elgg_Database
	 */
	protected function getDatabase(Elgg_Di_ServiceProvider $c) {
		global $CONFIG;
		return new Elgg_Database(new Elgg_Database_Config($CONFIG), $c->logger);
	}

	/**
	 * Events service factory
	 *
	 * @param Elgg_Di_ServiceProvider $c Dependency injection container
	 * @return Elgg_EventsService
	 */
	protected function getEvents(Elgg_Di_ServiceProvider $c) {
		return $this->resolveLoggerDependencies('events');
	}

	/**
	 * Logger factory
	 *
	 * @param Elgg_Di_ServiceProvider $c Dependency injection container
	 * @return Elgg_Logger
	 */
	protected function getLogger(Elgg_Di_ServiceProvider $c) {
		return $this->resolveLoggerDependencies('logger');
	}

	/**
	 * Plugin hooks service factory
	 *
	 * @param Elgg_Di_ServiceProvider $c Dependency injection container
	 * @return Elgg_PluginHooksService
	 */
	protected function getHooks(Elgg_Di_ServiceProvider $c) {
		return $this->resolveLoggerDependencies('hooks');
	}

	/**
	 * Returns the first requested service of the logger, events, and hooks. It sets the
	 * hooks and events up in the right order to prevent circular dependency.
	 *
	 * @param string $service_needed The service requested first
	 * @return mixed
	 */
	protected function resolveLoggerDependencies($service_needed) {
		$svcs['hooks'] = new Elgg_PluginHooksService();
		$svcs['logger'] = new Elgg_Logger($svcs['hooks']);
		$svcs['hooks']->setLogger($svcs['logger']);
		$svcs['events'] = new Elgg_EventsService();
		$svcs['events']->setLogger($svcs['logger']);

		foreach ($svcs as $key => $service) {
			$this->setValue($key, $service);
		}
		return $svcs[$service_needed];
	}

	/**
	 * Views service factory
	 *
	 * @param Elgg_Di_ServiceProvider $c Dependency injection container
	 * @return Elgg_ViewsService
	 */
	protected function getViews(Elgg_Di_ServiceProvider $c) {
		return new Elgg_ViewsService($c->hooks, $c->logger);
	}

	/**
	 * AMD Config factory
	 *
	 * @param Elgg_Di_ServiceProvider $c Dependency injection container
	 * @return Elgg_Amd_Config
	 */
	protected function getAmdConfig(Elgg_Di_ServiceProvider $c) {
		$obj = new Elgg_Amd_Config();
		$obj->setBaseUrl(_elgg_get_simplecache_root() . "js/");
		return $obj;
	}

	/**
	 * Session factory
	 *
	 * @param Elgg_Di_ServiceProvider $c Dependency injection container
	 * @return ElggSession
	 */
	protected function getSession(Elgg_Di_ServiceProvider $c) {
		global $CONFIG;

		// account for difference of session_get_cookie_params() and ini key names
		$params = $CONFIG->cookies['session'];
		foreach ($params as $key => $value) {
			if (in_array($key, array('path', 'domain', 'secure', 'httponly'))) {
				$params["cookie_$key"] = $value;
				unset($params[$key]);
			}
		}

		$handler = new Elgg_Http_DatabaseSessionHandler($c->db);
		$storage = new Elgg_Http_NativeSessionStorage($params, $handler);
		$session = new ElggSession($storage);

		return $session;
	}

	/**
	 * Request factory
	 *
	 * @param Elgg_Di_ServiceProvider $c Dependency injection container
	 * @return Elgg_Http_Request
	 */
	protected function getRequest(Elgg_Di_ServiceProvider $c) {
		return Elgg_Http_Request::createFromGlobals();
	}

	/**
	 * Router factory
	 *
	 * @param Elgg_Di_ServiceProvider $c Dependency injection container
	 * @return Elgg_Router
	 */
	protected function getRouter(Elgg_Di_ServiceProvider $c) {
		// TODO(evan): Init routes from plugins or cache
		return new Elgg_Router($c->hooks);
	}

	/**
	 * Notification service factory
	 *
	 * @param Elgg_Di_ServiceProvider $c Dependency injection container
	 * @return Elgg_Notifications_NotificationsService
	 */
	protected function getNotifications(Elgg_Di_ServiceProvider $c) {
		// @todo move queue in service provider
		$queue = new Elgg_Queue_DatabaseQueue(Elgg_Notifications_NotificationsService::QUEUE_NAME, $c->db);
		$sub = new Elgg_Notifications_SubscriptionsService($c->db);
		$access = elgg_get_access_object();
		return new Elgg_Notifications_NotificationsService($sub, $queue, $c->hooks, $access);
	}

	/**
	 * Persistent login service factory
	 *
	 * @param Elgg_Di_ServiceProvider $c Dependency injection container
	 * @return Elgg_PersistentLoginService
	 */
	protected function getPersistentLogin(Elgg_Di_ServiceProvider $c) {
		$cookies_config = elgg_get_config('cookies');
		$remember_me_cookies_config = $cookies_config['remember_me'];
		$cookie_name = $remember_me_cookies_config['name'];
		$cookie_token = $c->request->cookies->get($cookie_name, '');
		return new Elgg_PersistentLoginService($c->db, $c->session, $c->crypto, $remember_me_cookies_config, $cookie_token);
	}

	/**
	 * Query counter factory
	 *
	 * @param Elgg_Di_ServiceProvider $c Dependency injection container
	 * @return Elgg_Database_QueryCounter
	 */
	protected function getQueryCounter(Elgg_Di_ServiceProvider $c) {
		return new Elgg_Database_QueryCounter($c->db);
	}
}
