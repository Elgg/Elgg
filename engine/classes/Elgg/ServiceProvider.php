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
	 * Constructor
	 * 
	 * @param Elgg_AutoloadManager $autoload_manager Class autoloader
	 */
	public function __construct(Elgg_AutoloadManager $autoload_manager) {
		$this->setValue('autoloadManager', $autoload_manager);

		$classNames = array();
		
		$classNames['actions'] = 'Elgg_ActionsService';
		$this->setFactory('amdConfig', array($this, 'getAmdConfig'));
		$classNames['autoP'] = 'ElggAutoP';
		$classNames['db'] = 'ElggDatabase';
		$classNames['events'] = 'ElggEventService';
		$classNames['hooks'] = 'ElggPluginHookService';
		$this->setFactory('logger', array($this, 'getLogger'));
		$classNames['metadataCache'] = 'ElggVolatileMetadataCache';
		$this->setFactory('request', array($this, 'getRequest'));
		$this->setFactory('router', array($this, 'getRouter'));
		$this->setFactory('session', array($this, 'getSession'));
		$this->setFactory('views', array($this, 'getViews'));
		$classNames['widgets'] = 'Elgg_WidgetsService';
		
		$this->setClassNames($classNames);
	}

	/**
	 * Logger factory
	 * 
	 * @param Elgg_ServiceProvider $c Dependency injection container
	 * @return ElggLogger
	 */
	protected function getLogger(Elgg_ServiceProvider $c) {
		return new ElggLogger($c->hooks);
	}

	/**
	 * Views service factory
	 * 
	 * @param Elgg_ServiceProvider $c Dependency injection container
	 * @return ElggViewService
	 */
	protected function getViews(Elgg_ServiceProvider $c) {
		return new ElggViewService($c->hooks, $c->logger);
	}

	/**
	 * AMD Config factory
	 * 
	 * @param Elgg_ServiceProvider $c Dependency injection container
	 * @return Elgg_AmdConfig
	 */
	protected function getAmdConfig(Elgg_ServiceProvider $c) {
		$obj = new Elgg_AmdConfig();
		$obj->setBaseUrl(_elgg_get_simplecache_root() . "js/");
		return $obj;
	}

	/**
	 * Session factory
	 * 
	 * @param Elgg_ServiceProvider $c Dependency injection container
	 * @return ElggSession
	 */
	protected function getSession(Elgg_ServiceProvider $c) {
		$handler = new Elgg_Http_DatabaseSessionHandler($c->db);
		$storage = new Elgg_Http_NativeSessionStorage($handler);
		$session = new ElggSession($storage);
		$session->setName('Elgg');
		return $session;
	}
	
	/**
	 * Request factory
	 * 
	 * @param Elgg_ServiceProvider $c Dependency injection container
	 * @return Elgg_Request
	 */
	protected function getRequest(Elgg_ServiceProvider $c) {
		return new Elgg_Request($c->hooks, $_SERVER, $_REQUEST);
	}
	
	/**
	 * Router factory
	 * 
	 * @param Elgg_ServiceProvider $c Dependency injection container
	 * @return Elgg_Router
	 */
	protected function getRouter(Elgg_ServiceProvider $c) {
		// TODO(evan): Init routes from plugins or cache
		return new Elgg_Router($c->hooks);
	}
}
