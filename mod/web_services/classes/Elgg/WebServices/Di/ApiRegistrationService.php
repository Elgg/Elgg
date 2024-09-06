<?php

namespace Elgg\WebServices\Di;

use Elgg\Collections\CollectionInterface;
use Elgg\EventsService;
use Elgg\Exceptions\ExceptionInterface;
use Elgg\Exceptions\InvalidArgumentException;
use Elgg\Traits\Di\ServiceFacade;
use Elgg\WebServices\ApiMethod;

/**
 * Registration service for api endpoints (methods)
 *
 * @since 4.0
 */
class ApiRegistrationService {
	
	use ServiceFacade;
	
	protected ApiRegistrationCollection $collection;
	
	protected EventsService $events;
	
	/**
	 * New registration service
	 *
	 * @param ApiRegistrationCollection $collection Collection to store API endpoints
	 * @param EventsService             $events     Events service
	 */
	public function __construct(ApiRegistrationCollection $collection, EventsService $events) {
		$this->collection = $collection;
		$this->events = $events;
		
		$this->registerPluginStaticConfig();
	}
	
	/**
	 * Expose a function as a web service.
	 *
	 * Limitations: Currently cannot expose functions which expect objects.
	 * It also cannot handle arrays of booleans or arrays of arrays.
	 * Also, input will be filtered to protect against XSS attacks through the web services.
	 *
	 * @param ApiMethod $method The API method to register
	 *
	 * @return void
	 * @see ApiMethod::factory()
	 */
	public function registerApiMethod(ApiMethod $method): void {
		$this->collection->add($method);
	}
	
	/**
	 * Unregister a API method
	 *
	 * @param string $name                Name of the API method
	 * @param string $http_request_method The HTTP call method (GET|POST|...)
	 *
	 * @return void
	 */
	public function unregisterApiMethod(string $name, string $http_request_method = 'GET'): void {
		$http_request_method = strtoupper($http_request_method);
		
		$this->collection->remove("{$http_request_method}:{$name}");
	}
	
	/**
	 * Get an API method based on it's name
	 *
	 * @param string $name                Name of the API method
	 * @param string $http_request_method The HTTP call method (GET|POST|...)
	 *
	 * @return null|ApiMethod
	 */
	public function getApiMethod(string $name, string $http_request_method = 'GET'): ?ApiMethod {
		$http_request_method = strtoupper($http_request_method);
		
		return $this->collection->get("{$http_request_method}:{$name}");
	}
	
	/**
	 * Return all registered API methods
	 *
	 * @return ApiMethod[]
	 */
	public function getAllApiMethods() {
		$result = $this->collection->all();
		
		usort($result, function (ApiMethod $a, ApiMethod $b) {
			list(, $a_name) = explode(':', $a->getID());
			list(, $b_name) = explode(':', $b->getID());
			
			return strnatcasecmp($a_name, $b_name);
		});
		
		return $result;
	}
	
	/**
	 * Returns registered service name
	 *
	 * @return string
	 */
	public static function name() {
		return 'webservices.api_registration';
	}
	
	/**
	 * Read the API configuration from the active plugins and register those APIs
	 *
	 * @return void
	 */
	protected function registerPluginStaticConfig(): void {
		$config = [];
		$plugins = elgg_get_plugins('active');
		foreach ($plugins as $plugin) {
			$plugin_config = $plugin->getStaticConfig('web_services', []);
			if (empty($plugin_config)) {
				continue;
			}
			
			foreach ($plugin_config as $method => $http_methods) {
				if (empty($http_methods) || !is_array($http_methods)) {
					continue;
				}
				
				foreach ($http_methods as $http_method => $options) {
					if (empty($options) || !is_array($options)) {
						continue;
					}
					
					$config[$method][$http_method] = $options;
				}
			}
		}
		
		// allow plugins to edit/extend the API methods
		$config = $this->events->triggerResults('register', 'api_methods', $config, $config);
		if (!is_array($config)) {
			throw new InvalidArgumentException('The "register", "api_methods" event should return an array of API method configurations');
		}
		
		// register all API methods
		foreach ($config as $method => $http_methods) {
			if (empty($http_methods) || !is_array($http_methods)) {
				continue;
			}
			
			foreach ($http_methods as $http_method => $options) {
				if (empty($options) || !is_array($options)) {
					continue;
				}
				
				$options['method'] = $method;
				$options['call_method'] = $http_method;
				
				try {
					$this->collection->add(ApiMethod::factory($options));
				} catch (ExceptionInterface $e) {
					elgg_log($e, \Psr\Log\LogLevel::ERROR);
				}
			}
		}
	}
}
