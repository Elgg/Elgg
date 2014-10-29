<?php
namespace Elgg\Di;

use Elgg\I18n\Locale;
use Elgg\I18n\Translator;
use Elgg\I18n\DefaultTranslator;
use Elgg\I18n\NullTranslator;


/**
 * Provides common Elgg services.
 *
 * We extend the container because it allows us to document properties in the PhpDoc, which assists
 * IDEs to auto-complete properties and understand the types returned. Extension allows us to keep
 * the container generic.
 * 
 * @property-read \Elgg\ActionsService                     $actions
 * @property-read \Elgg\Amd\Config                         $amdConfig
 * @property-read \ElggAutoP                               $autoP
 * @property-read \Elgg\AutoloadManager                    $autoloadManager
 * @property-read \ElggCrypto                              $crypto
 * @property-read \Elgg\Database                           $db
 * @property-read \Elgg\EventsService                      $events
 * @property-read \Elgg\PluginHooksService                 $hooks
 * @property-read Locale                                   $locale
 * @property-read \Elgg\Logger                             $logger
 * @property-read \ElggVolatileMetadataCache               $metadataCache
 * @property-read \Elgg\Notifications\NotificationsService $notifications
 * @property-read \Elgg\EntityPreloader                    $ownerPreloader
 * @property-read \Elgg\PersistentLoginService             $persistentLogin
 * @property-read \Elgg\Database\QueryCounter              $queryCounter
 * @property-read \Elgg\Http\Request                       $request
 * @property-read \Elgg\Router                             $router
 * @property-read Translator                               $translator
 * @property-read \ElggSession                             $session
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

		$this->setClassName('actions', '\Elgg\ActionsService');
		$this->setFactory('amdConfig', array($this, 'getAmdConfig'));
		$this->setClassName('autoP', '\ElggAutoP');
		$this->setValue('context', new \Elgg\Context());
		$this->setClassName('crypto', '\ElggCrypto');
		$this->setFactory('db', array($this, 'getDatabase'));
		$this->setFactory('events', array($this, 'getEvents'));
		$this->setFactory('hooks', array($this, 'getHooks'));
		$this->setFactory('locale', array($this, 'getLocale'));
		$this->setFactory('logger', array($this, 'getLogger'));
		$this->setClassName('metadataCache', '\ElggVolatileMetadataCache');
		$this->setFactory('persistentLogin', array($this, 'getPersistentLogin'));
		$this->setFactory('ownerPreloader', array($this, 'getOwnerPreloader'));
		$this->setFactory('queryCounter', array($this, 'getQueryCounter'), false);
		$this->setFactory('request', array($this, 'getRequest'));
		$this->setFactory('router', array($this, 'getRouter'));
		$this->setFactory('session', array($this, 'getSession'));
		$this->setFactory('translator', array($this, 'getTranslator'));
		$this->setFactory('views', array($this, 'getViews'));
		$this->setClassName('widgets', '\Elgg\WidgetsService');
		$this->setFactory('notifications', array($this, 'getNotifications'));
	}

	/**
	 * Database factory
	 *
	 * @param \Elgg\Di\ServiceProvider $c Dependency injection container
	 * @return \Elgg\Database
	 */
	protected function getDatabase(\Elgg\Di\ServiceProvider $c) {
		global $CONFIG;
		return new \Elgg\Database(new \Elgg\Database\Config($CONFIG), $c->logger);
	}

	/**
	 * Events service factory
	 *
	 * @param \Elgg\Di\ServiceProvider $c Dependency injection container
	 * @return \Elgg\EventsService
	 */
	protected function getEvents(\Elgg\Di\ServiceProvider $c) {
		return $this->resolveLoggerDependencies('events');
	}

	/**
	 * Locale factory
	 * 
	 * @param \Elgg\Di\ServiceProvider $c Dependency Injection container
	 * @return Locale
	 */
	protected function getLocale(\Elgg\Di\ServiceProvider $c) {
		try {
			$locale = Locale::parse(get_language());
		} catch (\Exception $e) {}
		
		return isset($locale) ? $locale : Locale::parse('en');
	}

	/**
	 * Logger factory
	 * 
	 * @param \Elgg\Di\ServiceProvider $c Dependency injection container
	 * @return \Elgg\Logger
	 */
	protected function getLogger(\Elgg\Di\ServiceProvider $c) {
		return $this->resolveLoggerDependencies('logger');
	}

	/**
	 * Plugin hooks service factory
	 *
	 * @param \Elgg\Di\ServiceProvider $c Dependency injection container
	 * @return \Elgg\PluginHooksService
	 */
	protected function getHooks(\Elgg\Di\ServiceProvider $c) {
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

	/**
	 * Views service factory
	 * 
	 * @param \Elgg\Di\ServiceProvider $c Dependency injection container
	 * @return \Elgg\ViewsService
	 */
	protected function getViews(\Elgg\Di\ServiceProvider $c) {
		return new \Elgg\ViewsService($c->hooks, $c->logger);
	}

	/**
	 * AMD Config factory
	 * 
	 * @param \Elgg\Di\ServiceProvider $c Dependency injection container
	 * @return \Elgg\Amd\Config
	 */
	protected function getAmdConfig(\Elgg\Di\ServiceProvider $c) {
		$obj = new \Elgg\Amd\Config();
		$obj->setBaseUrl(_elgg_get_simplecache_root() . "js/");
		return $obj;
	}

	/**
	 * Session factory
	 * 
	 * @param \Elgg\Di\ServiceProvider $c Dependency injection container
	 * @return \ElggSession
	 */
	protected function getSession(\Elgg\Di\ServiceProvider $c) {
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
	}

	/**
	 * Translator factory
	 * 
	 * @param \Elgg\Di\ServiceProvider $c Dependency injection container
	 * @return Translator
	 */
	protected function getTranslator(\Elgg\Di\ServiceProvider $c) {
		$translator = new DefaultTranslator($c->translationLoader, $c->logger);
		$translator->setSiteLocale();
		$translator->setUserLocale();
		
		return $translator;
	}

	/**
	 * Request factory
	 * 
	 * @param \Elgg\Di\ServiceProvider $c Dependency injection container
	 * @return \Elgg\Http\Request
	 */
	protected function getRequest(\Elgg\Di\ServiceProvider $c) {
		return \Elgg\Http\Request::createFromGlobals();
	}

	/**
	 * Router factory
	 * 
	 * @param \Elgg\Di\ServiceProvider $c Dependency injection container
	 * @return \Elgg\Router
	 */
	protected function getRouter(\Elgg\Di\ServiceProvider $c) {
		// TODO(evan): Init routes from plugins or cache
		return new \Elgg\Router($c->hooks);
	}

	/**
	 * Notification service factory
	 * 
	 * @param \Elgg\Di\ServiceProvider $c Dependency injection container
	 * @return \Elgg\Notifications\NotificationsService
	 */
	protected function getNotifications(\Elgg\Di\ServiceProvider $c) {
		// @todo move queue in service provider
		$queue = new \Elgg\Queue\DatabaseQueue(\Elgg\Notifications\NotificationsService::QUEUE_NAME, $c->db);
		$sub = new \Elgg\Notifications\SubscriptionsService($c->db);
		$access = elgg_get_access_object();
		return new \Elgg\Notifications\NotificationsService($sub, $queue, $c->hooks, $access);
	}

	/**
	 * Persistent login service factory
	 *
	 * @param \Elgg\Di\ServiceProvider $c Dependency injection container
	 * @return \Elgg\PersistentLoginService
	 */
	protected function getPersistentLogin(\Elgg\Di\ServiceProvider $c) {
		$cookies_config = elgg_get_config('cookies');
		$remember_me_cookies_config = $cookies_config['remember_me'];
		$cookie_name = $remember_me_cookies_config['name'];
		$cookie_token = $c->request->cookies->get($cookie_name, '');
		return new \Elgg\PersistentLoginService($c->db, $c->session, $c->crypto, $remember_me_cookies_config, $cookie_token);
	}

	/**
	 * Owner preloader factory
	 *
	 * @param \Elgg\Di\ServiceProvider $c Dependency injection container
	 * @return \Elgg\EntityPreloader
	 */
	protected function getOwnerPreloader(\Elgg\Di\ServiceProvider $c) {
		return new \Elgg\EntityPreloader(array('owner_guid'));
	}

	/**
	 * Query counter factory
	 *
	 * @param \Elgg\Di\ServiceProvider $c Dependency injection container
	 * @return \Elgg\Database\QueryCounter
	 */
	protected function getQueryCounter(\Elgg\Di\ServiceProvider $c) {
		return new \Elgg\Database\QueryCounter($c->db);
	}
}

