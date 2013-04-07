<?php

/**
 * Provides common Elgg services.
 *
 * We extend the container because it allows us to document properties in the PhpDoc, which assists
 * IDEs to auto-complete properties and understand the types returned. Extension allows us to keep
 * the container generic.
 * 
 * @property-read Elgg_ActionsService       $actions
 * @property-read Elgg_AmdConfig            $amdConfig
 * @property-read ElggAutoP                 $autoP
 * @property-read Elgg_AutoloadManager      $autoloadManager
 * @property-read ElggDatabase              $db
 * @property-read ElggEventService          $events
 * @property-read ElggPluginHookService     $hooks
 * @property-read ElggLogger                $logger
 * @property-read ElggVolatileMetadataCache $metadataCache
 * @property-read Elgg_Request              $request
 * @property-read Elgg_Router               $router
 * @property-read ElggSession               $session
 * @property-read ElggViewService           $views
 * @property-read Elgg_WidgetsService       $widgets
 * 
 * @package Elgg.Core
 * @access private
 */
class Elgg_ServiceProvider extends Elgg_DIContainer {

	/**
	 * Get a value from service provider
	 * 
	 * @param string $name Name of the value
	 * @return mixed
	 * @throws RuntimeException
	 */
	public function __get($name) {
		if ($this->has($name)) {
			return $this->get($name);
		}
		throw new RuntimeException("Property '$name' does not exist");
	}

	/**
	 * Constructor
	 * 
	 * @param Elgg_AutoloadManager $autoload_manager Class autoloader
	 */
	public function __construct(Elgg_AutoloadManager $autoload_manager) {
		$this->setValue('autoloadManager', $autoload_manager);

		$this->setFactory('actions', array($this, 'getActions'));
		$this->setFactory('amdConfig', array($this, 'getAmdConfig'));
		$this->setFactory('autoP', array($this, 'getAutoP'));
		$this->setFactory('db', array($this, 'getDb'));
		$this->setFactory('events', array($this, 'getEvents'));
		$this->setFactory('hooks', array($this, 'getHooks'));
		$this->setFactory('logger', array($this, 'getLogger'));
		$this->setFactory('metadataCache', array($this, 'getMetadataCache'));
		$this->setFactory('request', array($this, 'getRequest'));
		$this->setFactory('router', array($this, 'getRouter'));
		$this->setFactory('session', array($this, 'getSession'));
		$this->setFactory('views', array($this, 'getViews'));
		$this->setFactory('widgets', array($this, 'getWidgets'));
	}

	/**
	 * Actions service factory
	 * 
	 * @param Elgg_DIContainer $c Dependency injection container
	 * @return Elgg_ActionsService
	 */
	protected function getActions(Elgg_DIContainer $c) {
		return new Elgg_ActionsService();
	}

	/**
	 * Hooks service factory
	 * 
	 * @param Elgg_DIContainer $c Dependency injection container
	 * @return ElggPluginHookService
	 */
	protected function getHooks(Elgg_DIContainer $c) {
		return new ElggPluginHookService();
	}

	/**
	 * Event service factory
	 * 
	 * @param Elgg_DIContainer $c Dependency injection container
	 * @return ElggEventService
	 */
	protected function getEvents(Elgg_DIContainer $c) {
		return new ElggEventService();
	}

	/**
	 * Widgets service factory
	 * 
	 * @param Elgg_DIContainer $c Dependency injection container
	 * @return Elgg_WidgetsService
	 */
	protected function getWidgets(Elgg_DIContainer $c) {
		return new Elgg_WidgetsService();
	}

	/**
	 * Metadata cache factory
	 * 
	 * @param Elgg_DIContainer $c Dependency injection container
	 * @return ElggVolatileMetadataCache
	 */
	protected function getMetadataCache(Elgg_DIContainer $c) {
		return new ElggVolatileMetadataCache();
	}

	/**
	 * Database factory
	 * 
	 * @param Elgg_DIContainer $c Dependency injection container
	 * @return ElggDatabase
	 */
	protected function getDb(Elgg_DIContainer $c) {
		return new ElggDatabase();
	}

	/**
	 * Logger factory
	 * 
	 * @param Elgg_DIContainer $c Dependency injection container
	 * @return ElggLogger
	 */
	protected function getLogger(Elgg_DIContainer $c) {
		return new ElggLogger($c->get('hooks'));
	}

	/**
	 * Views service factory
	 * 
	 * @param Elgg_DIContainer $c Dependency injection container
	 * @return ElggViewService
	 */
	protected function getViews(Elgg_DIContainer $c) {
		return new ElggViewService($c->hooks, $c->logger);
	}

	/**
	 * Paragraph formatter factory
	 * 
	 * @param Elgg_DIContainer $c Dependency injection container
	 * @return ElggAutoP
	 */
	protected function getAutoP(Elgg_DIContainer $c) {
		return new ElggAutoP();
	}

	/**
	 * AMD Config factory
	 * 
	 * @param Elgg_DIContainer $c Dependency injection container
	 * @return Elgg_AmdConfig
	 */
	protected function getAmdConfig(Elgg_DIContainer $c) {
		$obj = new Elgg_AmdConfig();
		$obj->setBaseUrl(_elgg_get_simplecache_root() . "js/");
		return $obj;
	}

	/**
	 * Session factory
	 * 
	 * @param Elgg_DIContainer $c Dependency injection container
	 * @return ElggSession
	 */
	protected function getSession(Elgg_DIContainer $c) {
		$handler = new Elgg_Http_DatabaseSessionHandler($c->db);
		$storage = new Elgg_Http_NativeSessionStorage($handler);
		$session = new ElggSession($storage);
		$session->setName('Elgg');
		return $session;
	}
	
	/**
	 * Request factory
	 * 
	 * @param Elgg_DIContainer $c Dependency injection container
	 * @return Elgg_Request
	 */
	protected function getRequest(Elgg_DIContainer $c) {
		return new Elgg_Request($c->hooks, $_SERVER, $_REQUEST);
	}
	
	/**
	 * Router factory
	 * 
	 * @param Elgg_DIContainer $c Dependency injection container
	 * @return Elgg_Router
	 */
	protected function getRouter(Elgg_DIContainer $c) {
		// TODO(evan): Init routes from plugins or cache
		return new Elgg_Router($c->hooks);
	}
}
