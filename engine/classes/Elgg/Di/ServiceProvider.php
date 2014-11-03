<?php
namespace Elgg\Di;

use \DI\Container;

/**
 * Provides common Elgg services.
 *
 * We inject the container because it allows us to document properties in the PhpDoc, which assists
 * IDEs to auto-complete properties and understand the types returned.
 * 
 * @property-read \Elgg\ActionsService                     $actions
 * @property-read \Elgg\Amd\Config                         $amdConfig
 * @property-read \Elgg\AutoloadManager                    $autoloadManager
 * @property-read \ElggAutoP                               $autoP
 * @property-read \ElggCrypto                              $crypto
 * @property-read \Elgg\Database                           $db
 * @property-read \Elgg\EventsService                      $events
 * @property-read \Elgg\PluginHooksService                 $hooks
 * @property-read \Elgg\Logger                             $logger
 * @property-read \ElggVolatileMetadataCache               $metadataCache
 * @property-read \Elgg\Notifications\NotificationsService $notifications
 * @property-read \Elgg\EntityPreloader                    $ownerPreloader
 * @property-read \Elgg\PersistentLoginService             $persistentLogin
 * @property-read \Elgg\Database\QueryCounter              $queryCounter
 * @property-read \Elgg\Http\Request                       $request
 * @property-read \Elgg\Router                             $router
 * @property-read \ElggSession                             $session
 * @property-read \ElggFileCache                           $systemCache
 * @property-read \Elgg\ViewsService                       $views
 * @property-read \Elgg\WidgetsService                     $widgets
 * 
 * @package Elgg.Core
 * @access private
 */
class ServiceProvider {

	/** @var Container */
	private $container;

	/**
	 * Constructor
	 * 
	 * @param Container $c The PHP-DI container
	 */
	public function __construct(Container $c) {
		$this->container = $c;
	}
	
	/**
	 * Retrieve the service instance.
	 * 
	 * @param string $name The service ID.
	 * 
	 * @return mixed
	 */
	public function __get($name) {
		return $this->container->get($name);
	}
	
	/**
	 * Alias of setValue for BC with SimpleTest tests.
	 * 
	 * @param string $name  The service ID.
	 * @param mixed  $value The service instance.
	 * 
	 * @return mixed
	 */
	public function __set($name, $value) {
		return $this->setValue($name, $value);
	}

	/**
	 * Override the service instance. Useful for testing.
	 * 
	 * @param string $name  The service ID
	 * @param mixed  $value The service instance
	 * 
	 * @return mixed
	 */
	public function setValue($name, $value) {
		return $this->container->set($name, $value);
	}
}
