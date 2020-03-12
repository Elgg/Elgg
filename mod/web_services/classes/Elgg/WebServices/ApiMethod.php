<?php

namespace Elgg\WebServices;

use Elgg\Collections\CollectionItemInterface;
use Elgg\Exceptions\InvalidParameterException;
use Elgg\Request;

/**
 * Describes an API method
 *
 * @property string $call_method        How should the API method be called (GET|POST)
 * @property string $description        Description of the API method
 * @property array  $params             Parameters supported by the API call
 * @property bool   $require_api_auth   Does this method require API authorization
 * @property bool   $require_user_auth  Does this method require user authorization
 * @property bool   $supply_associative If set to true, the callback function will receive a single argument that contains an associative array of parameter => input pairs for the method
 *
 * @since 4.0
 */
class ApiMethod implements CollectionItemInterface {

	/**
	 * @var string
	 */
	protected $method;
	
	/**
	 * @var callable
	 */
	protected $callback;
	
	/**
	 * @var array
	 */
	protected $params = [];
	
	/**
	 * @var string
	 */
	protected $description = '';
	
	/**
	 * @var string GET|POST
	 */
	protected $call_method = 'GET';
	
	/**
	 * @var bool
	 */
	protected $require_api_auth = false;
	
	/**
	 * @var bool
	 */
	protected $require_user_auth = false;
	
	/**
	 * @var bool
	 */
	protected $supply_associative = false;
	
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
	 * @throws \TypeError
	 * @throws InvalidParameterException
	 */
	public function __set($name, $value) {
		
		switch ($name) {
			case 'method':
			case 'callback':
				// aren't allowed to be set, only in constructor
				return;
			case 'params':
				if (!is_array($value)) {
					throw new \TypeError("'{$name}' needs to be an array, " . gettype($value) . " given.");
				}
				
				if (empty($value)) {
					// set to empty value
					break;
				}
				
				// catch common mistake of not setting up param array correctly
				$first = current($value);
				if (!is_array($first)) {
					throw new InvalidParameterException(elgg_echo('InvalidParameterException:APIParametersArrayStructure', [$this->getID()]));
				}
				
				// ensure the required flag is set correctly in default case for each parameter
				foreach ($value as $key => $v) {
					if (!isset($v['type'])) {
						throw new InvalidParameterException(elgg_echo('APIException:InvalidParameter', [$key, $this->getID()]));
					}
					
					// check if 'required' was specified - if not, make it true
					$value[$key]['required'] = (bool) elgg_extract('required', $v, true);
				}
				
				break;
			case 'description':
			case 'call_method':
				if (!is_string($value)) {
					throw new \TypeError("'{$name}' needs to be a string, " . gettype($value) . " given.");
				}
				
				// validate call method
				if ($name === 'call_method') {
					$value = strtoupper($value);
					if (!in_array($value, ['GET', 'POST'])) {
						throw new InvalidParameterException(elgg_echo('InvalidParameterException:UnrecognisedHttpMethod', [$value, $this->getID()]));
					}
				}
				break;
			case 'require_api_auth':
			case 'require_user_auth':
			case 'supply_associative':
				if (!is_bool($value)) {
					throw new \TypeError("'{$name}' needs to be a boolean, " . gettype($value) . " given.");
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
		
		// blacklist of actual protected values
		switch ($name) {
			case 'callback':
			case 'method':
				return null;
		}
		
		return $this->$name;
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
			throw new \APIException(elgg_echo('APIException:FunctionDoesNotExist', [$this->getID()]));
		}
		
		// check http call method
		if ($this->call_method !== $request->getMethod()) {
			throw new \APIException(elgg_echo('APIException:InvalidCallMethod', [$this->getID(), $this->call_method]));
		}
		
		$parameters = $this->getParameters($request);
		
		if ($this->supply_associative) {
			$result = call_user_func($callable, $parameters);
		} else {
			$result = call_user_func_array($callable, $parameters);
		}
		
		$result = elgg_trigger_plugin_hook('rest:output', $this->getID(), $parameters, $result);
		
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
				throw new \APIException(elgg_echo('APIException:MissingParameterInMethod', [$key, $this->getID()]));
			}
			
			// type cast
			$sanitised[$key] = $this->typeCastParameter($key, $value, elgg_extract('type', $settings));
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
			case 'integer' :
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
				throw new \APIException(elgg_echo('APIException:UnrecognisedTypeCast', [$type, $key, $this->getID()]));
		}
		
		return $value;
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
	 */
	public function getID() {
		return $this->method;
	}
}
