<?php

namespace Elgg\WebServices;

use Elgg\Collections\CollectionItemInterface;
use Elgg\Exceptions\DomainException;
use Elgg\Exceptions\InvalidArgumentException;
use Elgg\Request;

/**
 * Describes an API method
 *
 * @property string      $call_method        How should the API method be called (GET|POST)
 * @property string      $description        Description of the API method
 * @property-read string $method             The API method name
 * @property array       $params             Parameters supported by the API call
 * @property bool        $require_api_auth   Does this method require API authorization
 * @property bool        $require_user_auth  Does this method require user authorization
 * @property bool        $supply_associative If set to true, the callback function will receive a single argument that contains an associative array of parameter => input pairs for the method
 *
 * @since 4.0
 */
class ApiMethod implements CollectionItemInterface {

	/**
	 * @var callable
	 */
	protected $callback;
	
	protected string $method;
	
	protected array $params = [];
	
	protected string $description = '';
	
	protected string $call_method = 'GET';
	
	protected bool $require_api_auth = false;
	
	protected bool $require_user_auth = false;
	
	protected bool $supply_associative = false;
	
	/**
	 * Api method
	 *
	 * @param string   $method   the API method name
	 * @param callable $callback Callback function when the API method is called
	 */
	public function __construct(string $method, $callback) {
		$this->method = $method;
		$this->callback = $callback;
	}
	
	/**
	 * Set a class property
	 *
	 * @param string $name  the name of the property
	 * @param mixed  $value the value of the property
	 *
	 * @return void
	 * @throws \Elgg\Exceptions\DomainException
	 * @throws \Elgg\Exceptions\InvalidArgumentException
	 */
	public function __set($name, $value): void {
		
		switch ($name) {
			case 'method':
			case 'callback':
				// aren't allowed to be set, only in constructor
				return;
			case 'params':
				if (!is_array($value)) {
					$value_type = gettype($value);
					throw new InvalidArgumentException("'{$name}' needs to be an array, '{$value_type}}' given.");
				}
				
				if (empty($value)) {
					// set to empty value
					break;
				}
				
				// catch common mistake of not setting up param array correctly
				$first = current($value);
				if (!is_array($first)) {
					throw new InvalidArgumentException(elgg_echo('InvalidArgumentException:APIParametersArrayStructure', [$this->method]));
				}
				
				// ensure the required flag is set correctly in default case for each parameter
				foreach ($value as $key => $v) {
					if (!isset($v['type'])) {
						throw new InvalidArgumentException(elgg_echo('APIException:InvalidParameter', [$key, $this->method]));
					}
					
					// check if 'required' was specified - if not, make it true
					$value[$key]['required'] = (bool) elgg_extract('required', $v, true);
				}
				break;
			case 'description':
			case 'call_method':
				if (!is_string($value)) {
					$value_type = gettype($value);
					throw new InvalidArgumentException("'{$name}' needs to be a string, '{$value_type}' given.");
				}
				
				// validate call method
				if ($name === 'call_method') {
					$value = strtoupper($value);
					if (!in_array($value, ['GET', 'POST'])) {
						throw new DomainException(elgg_echo('DomainException:UnrecognisedHttpMethod', [$value, $this->method]));
					}
				}
				break;
			case 'require_api_auth':
			case 'require_user_auth':
			case 'supply_associative':
				if (!is_bool($value)) {
					$value_type = gettype($value);
					throw new InvalidArgumentException("'{$name}' needs to be a boolean, '{$value_type}' given.");
				}
				break;
		}
		
		$this->$name = $value;
	}
	
	/**
	 * Get a class property
	 *
	 * @param string $name the name of the property
	 *
	 * @return mixed
	 */
	public function __get($name) {
		return $this->$name;
	}
	
	/**
	 * Check if a class property has a value
	 *
	 * @param string $name the name of the property
	 *
	 * @return bool
	 */
	public function __isset($name): bool {
		return isset($this->$name);
	}
	
	/**
	 * Create an ApiMethod from an associative array.
	 *
	 * Required keys are:
	 * 	(string) method:   The API method name
	 * 	(string) callback: Callback function when the API method is called
	 *
	 * Optional options are:
	 *  (string) call_method:        The HTTP call method (GET|POST) (default: GET)
	 *  (string) description:        The description of the API method
	 *  (array)  params:             The input parameters for the API call. In the format:
	 *                               [
	 *                                  'variable' = [
	 *                                     'type' => 'int' | 'bool' | 'float' | 'string' | 'array',
	 *                                     'required' => true (default) | false,
	 *                                     'default' => value (optional),
	 *                                  ],
	 *                               ]
	 *  (bool)   require_api_auth:   Does the API require API authentication (default: false)
	 *  (bool)   require_user_auth:  Does the API require user authentication (default: false)
	 *  (bool)   supply_associative: Will the input params be provided as an array to the callback (default: false)
	 *
	 * @param array $options Option array of key value pairs
	 *
	 * @return static
	 * @throws InvalidArgumentException
	 */
	public static function factory(array $options): static {
		$method = elgg_extract('method', $options);
		$callback = elgg_extract('callback', $options);
		if (empty($method) || empty($callback)) {
			throw new InvalidArgumentException(__METHOD__ . ' requires at least a "method" and a "callback" in the $options array');
		}
		
		$api_method = new static($method, $callback);
		
		$defaults = [
			'params' => [],
			'call_method' => 'GET',
			'require_api_auth' => false,
			'require_user_auth' => false,
			'supply_associative' => false,
		];
		$options = array_merge($defaults, $options);
		foreach ($defaults as $param_key => $default) {
			// using magic __set() to force logic inside that function
			$api_method->__set($param_key, elgg_extract($param_key, $options));
		}
		
		$description = (string) elgg_extract('description', $options);
		if (empty($description)) {
			$lan_key = "web_services:api_methods:{$api_method->method}:" . strtolower($api_method->call_method) . ':description';
			if (elgg_language_key_exists($lan_key)) {
				$description = elgg_echo($lan_key);
			}
		}
		
		if (!empty($description)) {
			$api_method->description = $description;
		}
		
		return $api_method;
	}
	
	/**
	 * Executes the Api method
	 *
	 * @param Request $request the Elgg request
	 *
	 * @return \GenericResult The result of the execution
	 * @throws \APIException
	 */
	public function execute(Request $request) {
		$handlers = _elgg_services()->handlers;
		
		$callable = $handlers->resolveCallable($this->callback);
		
		// function must be callable
		if (empty($callable)) {
			throw new \APIException(elgg_echo('APIException:FunctionDoesNotExist', [$this->method]));
		}
		
		// check http call method
		if ($this->call_method !== $request->getMethod()) {
			throw new \APIException(elgg_echo('APIException:InvalidCallMethod', [$this->method, $this->call_method]));
		}
		
		$parameters = $this->getParameters($request);
		
		if ($this->supply_associative) {
			$result = call_user_func($callable, $parameters);
		} else {
			$result = call_user_func_array($callable, $parameters);
		}
		
		$result = elgg_trigger_event_results('rest:output', $this->method, $parameters, $result);
		
		// Sanity check result
		// If this function returns an api result itself, just return it
		if ($result instanceof \GenericResult) {
			return $result;
		}
		
		if ($result === false) {
			throw new \APIException(elgg_echo('APIException:FunctionParseError', [
				$handlers->describeCallable($this->callback),
				var_export($parameters, true),
			]));
		}
		
		if ($result === null) {
			// If no value
			throw new \APIException(elgg_echo('APIException:FunctionNoReturn', [
				$handlers->describeCallable($this->callback),
				var_export($parameters, true),
			]));
		}
		
		// Otherwise assume that the call was successful and return it as a success object.
		return \SuccessResult::getInstance($result);
	}
	
	/**
	 * Get a readable version of the api endpoint callable
	 *
	 * @return string
	 */
	public function describeCallable() {
		return _elgg_services()->handlers->describeCallable($this->callback);
	}
	
	/**
	 * This function analyses all expected parameters for a given method
	 *
	 * This function sanitizes the input parameters and returns them in an associated array
	 *
	 * @param Request $request the Elgg request
	 *
	 * @return array containing parameters as key => value
	 * @throws \APIException
	 */
	protected function getParameters(Request $request) {
		$sanitised = [];
		
		// if there are parameters, sanitize them
		foreach ($this->params as $key => $settings) {
			$default = elgg_extract('default', $settings);
			
			// Make things go through the sanitiser
			$value = $request->getParam($key, $default);
			
			// check required
			if ((bool) elgg_extract('required', $settings) && elgg_is_empty($value)) {
				throw new \APIException(elgg_echo('APIException:MissingParameterInMethod', [$key, $this->method]));
			}
			
			// type cast
			$sanitised[$key] = $this->typeCastParameter($key, $value, (string) elgg_extract('type', $settings));
		}
		
		return $sanitised;
	}
	
	/**
	 * Cast input params to the configured type
	 *
	 * @param string $key   input parameter name
	 * @param mixed  $value input value
	 * @param string $type  required type
	 *
	 * @return mixed
	 * @throws \APIException
	 */
	protected function typeCastParameter(string $key, $value, string $type) {
		if (is_null($value)) {
			return null;
		}
		
		$value = is_string($value) ? trim($value) : $value;
		
		switch (strtolower($type)) {
			case 'int':
			case 'integer':
				return (int) $value;
				
			case 'bool':
			case 'boolean':
				// check falsy values
				if ($value === 'false' || $value === 0 || $value === '0') {
					return false;
				}
				return true;
				
			case 'string':
				return (string) $value;
				
			case 'float':
				return (float) $value;
				
			case 'array':
				// we can handle an array of strings, maybe ints, definitely not booleans or other arrays
				if (!is_array($value)) {
					throw new \APIException(elgg_echo('APIException:ParameterNotArray', [$key]));
				}
				return $value;
				
			default:
				throw new \APIException(elgg_echo('APIException:UnrecognisedTypeCast', [$type, $key, $this->method]));
		}
	}
	
	/**
	 * =================================
	 * CollectionItemInterface functions
	 * =================================
	 */
	
	/**
	 * {@inheritDoc}
	 */
	public function getPriority() {
		// methods don't have a priority, only needed for CollectionItemInterface
		return 1;
	}

	/**
	 * {@inheritDoc}
	 *
	 * @return string
	 */
	public function getID(): string {
		return "{$this->call_method}:{$this->method}";
	}
}
