<?php
	/**
	 * Elgg API
	 * Functions and objects which make up the API engine.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @author Curverider Ltd <info@elgg.com>
	 * @link http://elgg.org/
	 */

	// Result classes /////////////////////////////////////////////////////////////////////////

	/**
	 * GenericResult Result superclass.
	 * 
	 * @author Curverider Ltd <info@elgg.com>
	 * @package Elgg
	 * @subpackage Core
	 */
	abstract class GenericResult
	{	
		/** 
		 * The status of the result.
		 * @var int
		 */
		private $status_code;
		
		/**
		 * Message returned along with the status which is almost always an error message.
		 * This must be human readable, understandable and localised.
		 * @var string
		 */
		private $message;
		
		/**
		 * Result store. 
		 * Attach result specific informaton here.
		 *
		 * @var mixed. Should probably be an object of some sort.
		 */
		private $result;

		/**
		 * Set a status code and optional message.
		 *
		 * @param int $status The status code.
		 * @param string $message The message.
		 */
		protected function setStatusCode($status, $message = "")
		{
			$this->status_code = $status;
			$this->message = $message;
		}
		
		/**
		 * Set the result.
		 *
		 * @param mixed $result
		 */
		protected function setResult($result) { $this->result = $result; }
		
		protected function getStatusCode() { return $this->status_code; }
		protected function getStatusMessage() { return $this->message; }
		protected function getResult() { return $this->result; }

		/**
		 * Serialise to a standard class.
		 * 
		 * DEVNOTE: The API is only interested in data, we can not easily serialise
		 * custom classes without the need for 1) the other side being PHP, 2) you need to have the class
		 * definition installed, 3) its the right version! 
		 * 
		 * Therefore, I'm not bothering.
		 * 
		 * Override this to include any more specific information, however api results should be attached to the 
		 * class using setResult().
		 * 
		 * if $CONFIG->debug is set then additional information about the runtime environment and authentication will be
		 * returned.
		 * 
		 * @return stdClass Object containing the serialised result.
		 */
		public function export()
		{
			global $ERRORS, $CONFIG, $_PAM_HANDLERS_MSG;
			
			$result = new stdClass;
			
			$result->status = $this->getStatusCode();
			if ($this->getStatusMessage()!="") $result->message = $this->getStatusMessage();
			
			$resultdata = $this->getResult(); 
			if (isset($resultdata)) $result->result = $resultdata;

			if ((isset($CONFIG->debug)) && ($CONFIG->debug == true))
			{
				if (count($ERRORS))
					$result->runtime_errors = $ERRORS;
					
				if (count($_PAM_HANDLERS_MSG))
					$result->pam = $_PAM_HANDLERS_MSG;
			}

			return $result;
		}
	}
	
	/**
	 * SuccessResult
	 * Generic success result class, extend if you want to do something special.
	 * 
	 * @author Curverider Ltd <info@elgg.com>
	 * @package Elgg
	 * @subpackage Core
	 */
	class SuccessResult extends GenericResult
	{
		public static $RESULT_SUCCESS 	= 0;  // Do not change this from 0

		public function SuccessResult($result)
		{	
			$this->setResult($result);
			$this->setStatusCode(SuccessResult::$RESULT_SUCCESS);
		}
		
		public static function getInstance($result)
		{	
			// Return a new error object.
			return new SuccessResult($result);
		}
	}
	
	/**
	 * ErrorResult
	 * The error result class.
	 * 
	 * @author Curverider Ltd <info@elgg.com>
	 * @package Elgg
	 * @subpackage Core
	 */
	class ErrorResult extends GenericResult
	{
		public static $RESULT_FAIL 		= -1 ; // Fail with no specific code

		public static $RESULT_FAIL_APIKEY_DISABLED = -30;
		public static $RESULT_FAIL_APIKEY_INACTIVE = -31;
		public static $RESULT_FAIL_APIKEY_INVALID = -32;
		
		public static $RESULT_FAIL_AUTHTOKEN = -20; // Invalid, expired or missing auth token

		public function ErrorResult($message, $code = "", Exception $exception = NULL)
		{
			if ($code == "")
				$code = ErrorResult::$RESULT_FAIL;
				
			if ($exception!=NULL)
				$this->setResult($exception->__toString());
				
			$this->setStatusCode($code, $message);
		}
				
		/**
		 * Get a new instance of the ErrorResult.
		 *
		 * @param string $message
		 * @param int $code
		 * @param Exception $exception Optional exception for generating a stack trace.
		 */
		public static function getInstance($message, $code = "", Exception $exception = NULL)
		{	
			// Return a new error object.
			return new ErrorResult($message, $code, $exception);
		}
	}
	
	// Caching of HMACs ///////////////////////////////////////////////////////////////////////	
	
	/**
	 * ElggHMACCache
	 * Store cached data in a temporary database, only used by the HMAC stuff.
	 * 
	 * @author Curverider Ltd <info@elgg.com>
	 * @package Elgg
	 * @subpackage API
	 */
	class ElggHMACCache extends ElggCache
	{
		/**
		 * Set the Elgg cache.
		 *
		 * @param int $max_age Maximum age in seconds, 0 if no limit.
		 */
		function __construct($max_age = 0)
		{
			$this->set_variable("max_age", $max_age);
		}
	
		/**
		 * Save a key
		 *
		 * @param string $key
		 * @param string $data
		 * @return boolean
		 */
		public function save($key, $data)
		{
			global $CONFIG;
			
			$key = sanitise_string($key);
			$time = time();
			
			return insert_data("INSERT into {$CONFIG->dbprefix}hmac_cache (hmac, ts) VALUES ('$key', '$time')");
		}
		
		/**
		 * Load a key
		 *
		 * @param string $key
		 * @param int $offset
		 * @param int $limit
		 * @return string
		 */
		public function load($key, $offset = 0, $limit = null)
		{
			global $CONFIG;
			
			$key = sanitise_string($key);
			
			$row = get_data_row("SELECT * from {$CONFIG->dbprefix}hmac_cache where hmac='$key'");
			if ($row)
				return $row->hmac;
			
			return false;
		}
		
		/**
		 * Invalidate a given key.
		 *
		 * @param string $key
		 * @return bool
		 */
		public function delete($key)
		{
			global $CONFIG;
			
			$key = sanitise_string($key);
			
			return delete_data("DELETE from {$CONFIG->dbprefix}hmac_cache where hmac='$key'");
		}
		
		/**
		 * Clear out all the contents of the cache.
		 * 
		 * Not currently implemented in this cache type.
		 */
		public function clear() { return true; }
		
		/**
		 * Clean out old stuff.
		 *
		 */
		public function __destruct()
		{
			global $CONFIG;
			
			$time = time();
			$age = (int)$this->get_variable("max_age");
			
			$expires = $time-$age;
			
			delete_data("DELETE from {$CONFIG->dbprefix}hmac_cache where ts<$expires");
		}
	}

	// API Call functions /////////////////////////////////////////////////////////////////////	
	
	/** 
	 * An array holding methods.
	 * The structure of this is 
	 * 	$METHODS = array (
	 * 		"api.method" => array (
	 * 			"function" = 'my_function_callback'
	 * 			"call_method" = 'GET' | 'POST'
	 * 			"parameters" = array (
	 * 				"variable" = array ( // NB, the order is the same as defined by your function callback
	 * 					type => 'int' | 'bool' | 'float' | 'string'
	 * 					required => true (default) | false  
	 * 				)
	 * 			)
	 * 			"require_auth_token" => true (default) | false
	 * 			"description" => "Some human readable description"
	 * 		)
	 *  )
	 */
	$METHODS = array();
	
	/**
	 * Get the request method.
	 */
	function get_call_method()
	{
		return $_SERVER['REQUEST_METHOD'];
	}
	
	/**
	 * This function analyses all expected parameters for a given method, returning them in an associated array from
	 * input. 
	 * 
	 * This ensures that they are sanitised and that no superfluous commands are registered. It also means that 
	 * hmacs work through the page handler.
	 *
	 * @param string $method The method
	 * @return Array containing commands and values, including method and api
	 */
	function get_parameters_for_method($method)
	{
		global $CONFIG, $METHODS;

		$method = sanitise_string($method);
		$sanitised = array();
		
		foreach ($CONFIG->input as $k => $v)
		{
			if ((isset($METHODS[$method]['parameters'][$k])) || ($k == 'auth_token') || ($k == 'method'))
				$sanitised[$k] = get_input($k); // Make things go through the sanitiser	
		}
	
		return $sanitised;
	}
	
	/**
	 * Obtain a token for a user.
	 *
	 * @param string $username The username
	 * @param string $password The password
	 */
	function obtain_user_token($username, $password)
	{
		global $CONFIG;
		
		$site = $CONFIG->site_id;
		$user = get_user_by_username($username);
		$time = time();
		$time += 60*60;
		$token = md5(rand(). microtime() . $username . $password . $time . $site);
		
		if (!$user) return false; 
		
		if (insert_data("INSERT into {$CONFIG->dbprefix}users_apisessions (user_guid, site_guid, token, expires) values ({$user->guid}, $site, '$token', '$time') on duplicate key update token='$token', expires='$time'"))
			return $token;
			
		return false;
	}
	
	/**
	 * Validate a token against a given site. 
	 * 
	 * A token registered with one site can not be used from a different apikey(site), so be aware of this
	 * during development.
	 * 
	 * @param int $site The ID of the site
	 * @param string $token The Token.
	 * @return mixed The user id attached to the token or false.
	 */
	function validate_user_token($site, $token)
	{
		global $CONFIG;
		
		$site = (int)$site;
		$token = sanitise_string($token);
		
		if (!$site) throw new ConfigurationException(elgg_echo('ConfigurationException:NoSiteID'));
		
		$time = time();
		
		$user = get_data_row("SELECT * from {$CONFIG->dbprefix}users_apisessions where token='$token' and site_guid=$site and $time < expires");
		if ($user)
			return $user->user_guid;
		
		return false;
	}
	
	/**
	 * Expose an arbitrary function as an api call.
	 * 
	 * Limitations: Currently can not expose functions which expect objects.
	 * 
	 * @param string $method The api name to expose this as, eg "myapi.dosomething"
	 * @param string $function Your function callback.
	 * @param array $parameters Optional list of parameters in the same order as in your function, with optional parameters last. 
	 * 	This array should be in the format 
	 *   "variable" = array ( 
	 * 					type => 'int' | 'bool' | 'float' | 'string' | 'array'
	 * 					required => true (default) | false  
	 * 	 )
	 * @param string $description Optional human readable description of the function.
	 * @param string $call_method Define what call method should be used for this function.
	 * @param bool $require_auth_token Whether this requires a user authentication token or not (default is true).
	 * @param bool $anonymous Can anonymous (non-authenticated in any way) users execute this call. 
	 * @return bool
	 */
	function expose_function($method, $function, array $parameters = NULL, $description = "", $call_method = "GET", $require_auth_token = true, $anonymous = false)
	{
		global $METHODS;
		
		if (
			($method!="") &&
			($function!="")
		)
		{
			$METHODS[$method] = array();
				
			$METHODS[$method]["function"] = $function;
			
			if ($parameters!=NULL)
				$METHODS[$method]["parameters"] = $parameters;
				
			$call_method = strtoupper($call_method);
			switch ($call_method)
			{
				case 'POST' : $METHODS[$method]["call_method"] = 'POST'; break;
				case 'GET' : $METHODS[$method]["call_method"] = 'GET'; break;
				default : 
					throw new InvalidParameterException(sprintf(elgg_echo('InvalidParameterException:UnrecognisedMethod'), $method));
			}
				
			$METHODS[$method]["description"] = $description;
			
			$METHODS[$method]["require_auth_token"] = $require_auth_token;
			
			$METHODS[$method]["anonymous"] = $anonymous;
			
			return true;
		}
		
		return false;	
	}
	
	/**
	 * Executes a method.
	 * A method is a function which you have previously exposed using expose_function.
	 *
	 * @param string $method Method, e.g. "foo.bar"
	 * @param array $parameters Array of parameters in the format "variable" => "value", thse will be sanitised before being fed to your handler.
	 * @param string $token The authentication token to authorise this method call.
	 * @return GenericResult The result of the execution.
	 * @throws APIException, SecurityException
	 */
	function execute_method($method, array $parameters, $token = "")
	{
		global $METHODS, $CONFIG;
		
		// Sanity check
		$method = sanitise_string($method); 
		$token = sanitise_string($token); 
		
		// See if we can find the method handler
		if ((isset($METHODS[$method]["function"])) && (is_callable($METHODS[$method]["function"])))
		{
			// See if this is being made with the right call method
			if (strcmp(get_call_method(), $METHODS[$method]["call_method"])==0)
			{
				$serialised_parameters = "";
				
				
				// If we have parameters then we need to sanitise the parameters.
				if ((isset($METHODS[$method]["parameters"])) && (is_array($METHODS[$method]["parameters"]))) 
				{
					foreach ($METHODS[$method]["parameters"] as $key => $value)
					{
						if (
							(is_array($value)) 			// Check that this is an array
							&& (isset($value['type']))		// Check we have a type defined
						)
						{
							// Check that the variable is present in the request

							if (
								(!isset($parameters[$key])) ||				// No parameter
								((!isset($value['required'])) || ($value['required']==true)) // Or not optional
							)
								throw new APIException(sprintf(elgg_echo('APIException:MissingParameterInMethod'), $key, $method));
							else
							{
								// Avoid debug error
								if (isset($parameters[$key]))
								{
									// Set variables casting to type.	
									switch (strtolower($value['type']))
									{
										case 'int':
										case 'integer' : $serialised_parameters .= "," . (int)trim($parameters[$key]); break;
										case 'bool':
										case 'boolean': 
													if (strcasecmp(trim($parameters[$key]), "false")==0) 
														$parameters[$key]='';
															
													$serialised_parameters .= "," . (bool)trim($parameters[$key]); 
													break;
										case 'string': $serialised_parameters .= ",'" .  (string)mysql_real_escape_string(trim($parameters[$key])) . "'"; 
													break;
										case 'float': $serialised_parameters .= "," . (float)trim($parameters[$key]); 
													break;
										case 'array':
														$array = "array(";
														
														if (is_array($parameters[$key]))
														{
															foreach ($parameters[$key] as $k => $v)
															{
																$k = sanitise_string($k);
																$v = sanitise_string($v);
																
																$array .= "'$k'=>'$v',";
															}
															
															$array = trim($array,",");
														}
														else
															throw APIException(sprintf(elgg_echo('APIException:ParameterNotArray'), $key));
															
														$array .= ")";
														
														$serialised_parameters .= $array;
													break;
			
										default : throw new APIException(sprintf(elgg_echo('APIException:UnrecognisedTypeCast'), $value['type'], $key, $method));
									}
								}
							}
						}
						else
							throw new APIException(sprintf(elgg_echo('APIException:InvalidParameter'), $key, $method));
					}
				}
				
				// Execute function: Construct function and calling parameters
				$function = $METHODS[$method]["function"];
				$serialised_parameters = trim($serialised_parameters, ", ");
				
				$result = eval("return $function($serialised_parameters);");
			
				// Sanity check result
				if ($result instanceof GenericResult) // If this function returns an api result itself, just return it
					return $result; 
					
				if ($result === FALSE)
					throw new APIException(sprintf(elgg_echo('APIException:FunctionParseError'), $function, $serialised_parameters));
					
				if ($result ===  NULL)
					throw new APIException(sprintf(elgg_echo('APIException:FunctionNoReturn'), $function, $serialised_parameters)); // If no value
				
				return SuccessResult::getInstance($result); // Otherwise assume that the call was successful and return it as a success object.	
			 
			}	
			else
				throw new CallException(sprintf(elgg_echo('CallException:InvalidCallMethod'), $method, $METHODS[$method]["call_method"]));
		}
	
		// Return an error if not found
		throw new APIException(sprintf(elgg_echo('APIException:MethodCallNotImplemented'), $method)); 
	}
		
	// System functions ///////////////////////////////////////////////////////////////////////
	
	/**
	 * Simple api to return a list of all api's installed on the system.
	 */
	function list_all_apis()
	{
		global $METHODS;
		return $METHODS;
	}
	
	// Expose some system api functions
	expose_function("system.api.list", "list_all_apis", NULL, elgg_echo("system.api.list"), "GET", false);

	/**
	 * The auth.gettoken API.
	 * This API call lets a user log in, returning an authentication token which can be used
	 * in leu of a username and password login from then on.
	 * 
	 * @param string username Username
	 * @param string password Clear text password
	 */
	function auth_gettoken($username, $password)
	{
		if (authenticate($username, $password))
		{
			$token = obtain_user_token($username, $password);
			if ($token)
				return $token;
		}
		
		throw new SecurityException(elgg_echo('SecurityException:authenticationfailed'));
	}
	
	// The authentication token api
	expose_function("auth.gettoken", "auth_gettoken", array(
		"username" => array (
  			'type' => 'string'
  		),
  		"password" => array (
  			'type' => 'string'
		)
	), elgg_echo('auth.gettoken'), "GET", false, false);
	
	
	// PAM AUTH HMAC functions ////////////////////////////////////////////////////////////////
	
	/**
	 * Map various algorithms to their PHP equivs.
	 * This also gives us an easy way to disable algorithms.
	 * 
	 * @param string $algo The algorithm
	 * @return string The php algorithm
	 * @throws APIException if an algorithm is not supported. 
	 */
	function map_api_hash($algo)
	{
		$algo = strtolower(sanitise_string($algo));
		$supported_algos = array(
			"md5" => "md5",	// TODO: Consider phasing this out
			"sha" => "sha1", // alias for sha1
			"sha1" => "sha1",
			"sha256" => "sha256"
		);
		
		if (array_key_exists($algo, $supported_algos))
			return $supported_algos[$algo];
			
		throw new APIException(sprintf(elgg_echo('APIException:AlgorithmNotSupported'), $algo));
	}
	
	/**
	 * Calculate the HMAC for the query.
	 * This function signs an api request using the information provided and is then verified by
	 * searunner.
	 * 
	 * @param $algo string The HMAC algorithm used as stored in X-Searunner-hmac-algo.
	 * @param $time string String representation of unix time as stored in X-Searunner-time.
	 * @param $api_key string Your api key.
	 * @param $secret string Your secret key.
	 * @param $get_variables string URLEncoded string representation of the get variable parameters, eg "format=php&method=searunner.test".
	 * @param $post_hash string Optional sha1 hash of the post data.
	 * @return string The HMAC string.
	 */
	function calculate_hmac($algo, $time, $api_key, $secret_key, $get_variables, $post_hash = "")
	{
		global $CONFIG;
		
		if ((isset($CONFIG)) && ($CONFIG->debug))
			error_log("HMAC Parts: $algo, $time, $api_key, $secret_key, $get_variables, $post_hash");
		
		$ctx = hash_init(map_api_hash($algo), HASH_HMAC, $secret_key);

		hash_update($ctx, trim($time));
		hash_update($ctx, trim($api_key));
		hash_update($ctx, trim($get_variables));
		if (trim($post_hash)!="") hash_update($ctx, trim($post_hash));

		return hash_final($ctx);
	}

	/**
	 * Calculate a hash for some post data.
	 * 
	 * TODO: Work out how to handle really large bits of data.
	 *
	 * @param $postdata string The post data.
	 * @param $algo string The algorithm used.
	 * @return string The hash.
	 */
	function calculate_posthash($postdata, $algo)
	{
		$ctx = hash_init(map_api_hash($algo));

		hash_update($ctx, $postdata);

		return hash_final($ctx);
	}
	
	/**
	 * This function will do two things. Firstly it verifys that a $hmac hasn't been seen before, and 
	 * secondly it will add the given hmac to the cache.
	 * 
	 * @param $hmac The hmac string.
	 * @return bool True if replay detected, false if not.
	 */
	function cache_hmac_check_replay($hmac)
	{
		$cache = new ElggHMACCache(90000); // cache lifetime is 25 hours (see time window in get_and_validate_api_headers() )
	
		if (!$cache->load($hmac))
		{	
			$cache->save($hmac, $hmac);
		
			return false;
		}
				
		return true;
	}
	
	/**
	 * Find an API User's details based on the provided public api key. These users are not users in the traditional sense.
	 *
	 * @param int $site_guid The GUID of the site.
	 * @param string $api_key The API Key
	 * @return mixed stdClass representing the database row or false.
	 */
	function get_api_user($site_guid, $api_key)
	{
		global $CONFIG;
		
		$api_key = sanitise_string($api_key);
		$site_guid = (int)$site_guid;
		
		return get_data_row("SELECT * from {$CONFIG->dbprefix}api_users where api_key='$api_key' and site_guid=$site_guid and active=1");	
	}
	
	/**
	 * Revoke an api user key.
	 *
	 * @param int $site_guid The GUID of the site.
	 * @param string $api_key The API Key (public).
	 */
	function remove_api_user($site_guid, $api_key)
	{
		global $CONFIG;
		
		$keypair = get_api_user($site_guid, $api_key);
		if ($keypair)
			return delete_data("DELETE from {$CONFIG->dbprefix}api_users where id={$keypair->id}");
			
		return false;
	}
	
	/**
	 * Generate a new API user for a site, returning a new keypair on success.
	 *
	 * @param int $site_guid The GUID of the site.
	 */
	function create_api_user($site_guid)
	{
		global $CONFIG;
		
		$site_guid = (int)$site_guid;
		
		$public = sha1(rand().$site_guid.microtime());
		$secret = sha1(rand().$site_guid.microtime().$public);
		
		if (insert_data("INSERT into {$CONFIG->dbprefix}api_users (site_guid, api_key, secret) values ($site_guid, '$public', '$secret')"))
			return get_api_user($site_guid, $public);
			
		return false;
	}
	
	/**
	 * This function looks at the super-global variable $_SERVER and extracts the various
	 * header variables needed to pass to the validation functions after performing basic validation.
	 *
	 * @return stdClass Containing all the values.
	 * @throws APIException Detailing any error.
	 */
	function get_and_validate_api_headers()
	{
		$result = new stdClass;
		
		$result->method = get_call_method(); 
		if (($result->method != "GET") && ($result->method!= "POST")) // Only allow these methods
			throw new APIException(elgg_echo('APIException:NotGetOrPost'));

		$result->api_key = $_SERVER['HTTP_X_ELGG_APIKEY'];
		if ($result->api_key == "")
			throw new APIException(elgg_echo('APIException:MissingAPIKey'));
		
		$result->hmac = $_SERVER['HTTP_X_ELGG_HMAC'];
		if ($result->hmac == "")
			throw new APIException(elgg_echo('APIException:MissingHmac'));
		
		$result->hmac_algo = $_SERVER['HTTP_X_ELGG_HMAC_ALGO'];
		if ($result->hmac_algo == "")
			throw new APIException(elgg_echo('APIException:MissingHmacAlgo'));
		
		$result->time = $_SERVER['HTTP_X_ELGG_TIME'];
		if ($result->time == "") 
			throw new APIException(elgg_echo('APIException:MissingTime')); 
		if (($result->time<(microtime(true)-86400.00)) || ($result->time>(microtime(true)+86400.00))) // Basic timecheck, think about making this smaller if we get loads of users and the cache gets really big.
			throw new APIException(elgg_echo('APIException:TemporalDrift'));
		
		$result->get_variables = get_parameters_for_method(get_input('method')); //$_SERVER['QUERY_STRING'];
		if ($result->get_variables == "")
			throw new APIException(elgg_echo('APIException:NoQueryString'));

		if ($result->method=="POST")
		{
			$result->posthash = $_SERVER['HTTP_X_ELGG_POSTHASH'];
			if ($result->posthash == "") 
				throw new APIException(elgg_echo('APIException:MissingPOSTHash'));
			
			$result->posthash_algo = $_SERVER['HTTP_X_ELGG_POSTHASH_ALGO'];
			if ($result->posthash_algo == "") 
				throw new APIException(elgg_echo('APIException:MissingPOSTAlgo'));
				
			$result->content_type = $_SERVER['CONTENT_TYPE'];
			if ($result->content_type == "")
				throw new APIException(elgg_echo('APIException:MissingContentType'));
		}
		
		return $result;
	}
	
	/**
	 * Return a sanitised form of the POST data sent to the script
	 *
	 * @return string
	 */
	function get_post_data()
	{
		global $GLOBALS;
		
		$postdata = $GLOBALS['HTTP_RAW_POST_DATA'];

		// Attempt another method to return post data (incase always_populate_raw_post_data is switched off)
		if (!$postdata)
		{
			$postdata = file_get_contents('php://input');
		}
		
		return $postdata;
	}
	
	// PAM functions //////////////////////////////////////////////////////////////////////////

	/**
	 * Function that examines whether an authentication token is present returning true if it is, OR the requested 
	 * method doesn't require one.
	 * 
	 * If a token is present and a validated user id is returned, that user is logged in to the current session.
	 *
	 * @param unknown_type $credentials
	 */
	function pam_auth_usertoken($credentials = NULL)
	{
		global $METHODS, $CONFIG;
		
		$method = get_input('method');
		$token = get_input('auth_token');
		
		$validated_userid = validate_user_token($CONFIG->site_id, $token); 
		
		if ($validated_userid) {			
			$u = get_entity($validated_userid);
			if (!$u) return false; // Could we get the user?
			if ( (!$u instanceof ElggUser)) return false; // Not an elgg user
			if ($u->isBanned()) return false; // User is banned
			if (!login($u)) return false; // Fail if we couldn't log the user in 
			
		}
		
		if ((!$METHODS[$method]["require_auth_token"]) || ($validated_userid) || (isloggedin())) {
			return true;
		} else
			throw new SecurityException(elgg_echo('SecurityException:AuthTokenExpired'), ErrorResult::$RESULT_FAIL_AUTHTOKEN);
		
		return false;
	}
	
	/**
	 * Test to see whether a given function has been declared as anonymous access (it doesn't require any auth token)
	 *
	 * @param unknown_type $credentials
	 */
	function pam_auth_anonymous_method($credentials = NULL)
	{
		global $METHODS, $CONFIG;
		
		$method = get_input('method');
		
		if ((isset($METHODS[$method]["anonymous"])) && ($METHODS[$method]["anonymous"]))
			return true;
		
		return false;
	}
	
	/**
	 * See if the user has a valid login sesson.
	 */
	function pam_auth_session($credentials = NULL)
	{
		return isloggedin();
	}
	
	/**
	 * Secure authentication through headers and HMAC.
	 */
	function pam_auth_hmac($credentials = NULL)
	{
		global $CONFIG;
		
		$api_header = get_and_validate_api_headers(); // Get api header
		$api_user = get_api_user($CONFIG->site_id, $api_header->api_key); // Pull API user details
	
		if ($api_user)
		{
			// Get the secret key
			$secret_key = $api_user->secret;
		
			// Serialise parameters
			$encoded_params = array();
			foreach ($api_header->get_variables as $k => $v)
				$encoded_params[] = urlencode($k).'='.urlencode($v);
			$params = implode('&', $encoded_params);		
			
			// Validate HMAC
			$hmac = calculate_hmac($api_header->hmac_algo, 
					$api_header->time, 
					$api_header->api_key, 
					$secret_key, 
					$params, 
					$api_header->method == 'POST' ? $api_header->posthash : "");
		
			if ((strcmp(
				$api_header->hmac,
				$hmac	
			)==0) && ($api_header->hmac) && ($hmac))
			{
				// Now make sure this is not a replay
				if (!cache_hmac_check_replay($hmac)) 
				{
					
					// Validate post data
					if ($api_header->method=="POST")
					{
						$postdata = get_post_data();
						$calculated_posthash = calculate_posthash($postdata, $api_header->posthash_algo);

						if (strcmp($api_header->posthash, $calculated_posthash)!=0)
							throw new SecurityException(sprintf(elgg_echo('SecurityException:InvalidPostHash'), $calculated_posthash, $api_header->posthash));
					}
					
					// If we've passed all the checks so far then we can be reasonably certain that the request is authentic, so return this fact to the PAM engine.
					return true;
				}
				else
					throw new SecurityException(elgg_echo('SecurityException:DupePacket'));
			}
			else 
				throw new SecurityException("HMAC is invalid.  {$api_header->hmac} != [calc]$hmac = {$api_header->hmac_algo}(**SECRET KEY**, time:{$api_header->time}, apikey:{$api_header->api_key}, get_vars:{$params}" . ($api_header->method=="POST"? "posthash:$api_header->posthash}" : ")"));
		}
		else
			throw new SecurityException(elgg_echo('SecurityException:InvalidAPIKey'),ErrorResult::$RESULT_FAIL_APIKEY_INVALID);
			
		return false;
	}
	
	/**
	 * A bit of a hack. Basically, this combines session and hmac, so that one of them must evaluate to true in order 
	 * to proceed.
	 * 
	 * This ensures that this and auth_token are evaluated separately.
	 *
	 * @param unknown_type $credentials
	 */
	function pam_auth_session_or_hmac($credentials = NULL)
	{
		if (pam_auth_session($credentials))
			return true;
			
		if (pam_auth_hmac($credentials))
			return true;
			
		return false;
	}
	
	// Client api functions ///////////////////////////////////////////////////////////////////
	
	$APICLIENT_LAST_CALL = NULL; 
	$APICLIENT_LAST_CALL_RAW = ""; 
	$APICLIENT_LAST_ERROR = NULL; 
	
	/**
	 * Utility function to serialise a header array into its text representation.
	 * 
	 * @param $headers array The array of headers "key" => "value"
	 * @return string 
	 */
	function serialise_api_headers(array $headers)
	{
		$headers_str = "";

		foreach ($headers as $k => $v)
			$headers_str .= trim($k) . ": " . trim($v) . "\r\n";

		return trim($headers_str);		
	}
	
	/**
	 * Send a raw API call to an elgg api endpoint.
	 *
	 * @param array $keys The api keys.
	 * @param string $url URL of the endpoint.
	 * @param array $call Associated array of "variable" => "value"
	 * @param string $method GET or POST
	 * @param string $post_data The post data
	 * @param string $content_type The content type
	 * @return stdClass The unserialised response object
	 */
	function send_api_call(array $keys, $url, array $call, $method = 'GET', $post_data = '', $content_type = 'application/octet-stream')
	{
		global $APICLIENT_LAST_CALL, $APICLIENT_LAST_CALL_RAW, $APICLIENT_LAST_ERROR, $CONFIG; 
		
		$headers = array();
		$encoded_params = array();
		
		$method = strtoupper($method);
		switch (strtoupper($method))
		{
			case 'GET' :
			case 'POST' :  break;
			default: throw new NotImplementedException(sprintf(elgg_echo('NotImplementedException:CallMethodNotImplemented'), $method));
		}
		
		// Time
		$time = microtime(true); 

		// URL encode all the parameters, ensuring auth_token (if present) is at the end!
		foreach ($call as $k => $v){
			if ($k!='auth_token')
				$encoded_params[] = urlencode($k).'='.urlencode($v);
		}
		if ($call['auth_token'])
			$encoded_params[] = urlencode('auth_token').'='.urlencode($call['auth_token']);

		$params = implode('&', $encoded_params);
		
		// Put together the query string
		$url = $url . "?" . $params;
		
		// Construct headers
		$posthash = "";
		if ($method == 'POST') $posthash = calculate_posthash($post_data, 'md5');
		
		if ((isset($keys['public'])) && (isset($keys['private'])))
		{
			$headers['X-Elgg-apikey'] = $keys['public'];
			$headers['X-Elgg-time'] = $time;
			$headers['X-Elgg-hmac-algo'] = 'sha1';
			$headers['X-Elgg-hmac'] = calculate_hmac('sha1', 
										$time,
										$keys['public'],
										$keys['private'],
										$params,
										$posthash
			);
		}
		if ($method == 'POST') 
		{
			$headers['X-Elgg-posthash'] = $posthash;
			$headers['X-Elgg-posthash-algo'] = 'md5';
			
			$headers['Content-type'] = $content_type;
			$headers['Content-Length'] = strlen($post_data);
		}
		
		// Opt array
		$http_opts = array(
			'method' => $method,
			'header' => serialise_api_headers($headers)
		);
		if ($method == 'POST') $http_opts['content'] = $post_data;
		
		$opts = array('http' => $http_opts);
		
		// Send context
		$context = stream_context_create($opts);
		
		// Send the query and get the result and decode.
		if ((isset($CONFIG->debug)) && ($CONFIG->debug))
			error_log("APICALL: $url");
		$APICLIENT_LAST_CALL_RAW = file_get_contents($url, false, $context);
	
		$APICLIENT_LAST_CALL = unserialize($APICLIENT_LAST_CALL_RAW);
		
		if (($APICLIENT_LAST_CALL) && ($APICLIENT_LAST_CALL->status!=0))
			$APICLIENT_LAST_ERROR = $APICLIENT_LAST_CALL;
		
		return $APICLIENT_LAST_CALL;
	}

	/**
	 * Send a GET call
	 *
	 * @param string $url URL of the endpoint.
	 * @param array $call Associated array of "variable" => "value"
	 * @param array $keys The keys dependant on chosen authentication method
	 * @return stdClass The unserialised response object
	 */
	function send_api_get_call($url, array $call, array $keys) { return send_api_call($keys, $url, $call); }
	
	/**
	 * Send a GET call
	 *
	 * @param string $url URL of the endpoint.
	 * @param array $call Associated array of "variable" => "value"
	 * @param array $keys The keys dependant on chosen authentication method
	 * @param string $post_data The post data
	 * @param string $content_type The content type
	 * @return stdClass The unserialised response object
	 */
	function send_api_post_call($url, array $call, array $keys, $post_data, $content_type = 'application/octet-stream') { return send_api_call($keys, $url, $call, 'POST', $post_data, $content_type); }
	
	/**
	 * Return a key array suitable for the API client using the standard authentication method based on api-keys and secret keys.
	 *
	 * @param string $secret_key Your secret key
	 * @param string $api_key Your api key
	 */
	function get_standard_api_key_array($secret_key, $api_key) { return array('public' => $api_key, 'private' => $api_key); }
	
	// Error handler functions ////////////////////////////////////////////////////////////////
	
	/** Define a global array of errors */
	$ERRORS = array();
	
	/**
	 * PHP Error handler function.
	 * This function acts as a wrapper to catch and report PHP error messages.
	 * 
	 * @see http://uk3.php.net/set-error-handler
	 * @param unknown_type $errno
	 * @param unknown_type $errmsg
	 * @param unknown_type $filename
	 * @param unknown_type $linenum
	 * @param unknown_type $vars
	 */
	function __php_api_error_handler($errno, $errmsg, $filename, $linenum, $vars)
	{
		global $ERRORS;
		
		$error = date("Y-m-d H:i:s (T)") . ": \"" . $errmsg . "\" in file " . $filename . " (line " . $linenum . ")";
		
		switch ($errno) {
			case E_USER_ERROR:
					error_log("ERROR: " . $error);
					$ERRORS[] = "ERROR: " .$error;
					
					// Since this is a fatal error, we want to stop any further execution but do so gracefully.
					throw new Exception("ERROR: " . $error); 
				break;
	
			case E_WARNING :
			case E_USER_WARNING : 
					error_log("WARNING: " . $error);
					$ERRORS[] = "WARNING: " .$error;
				break;
	
			default:
				error_log("DEBUG: " . $error); 
				$ERRORS[] = "DEBUG: " .$error;
		}
	}
	
	/**
	 * PHP Exception handler.
	 * This is a generic exception handler for PHP exceptions. This will catch any 
	 * uncaught exception and return it as an ErrorResult in the requested format.
	 *
	 * @param Exception $exception
	 */
	function __php_api_exception_handler($exception) {
		
		error_log("*** FATAL EXCEPTION (API) *** : " . $exception);
			
		page_draw($exception->getMessage(), elgg_view("api/output",
			array('result' => ErrorResult::getInstance(
				$exception->getMessage(), 
				$exception->getCode() == 0 ? ErrorResult::$RESULT_FAIL : $exception->getCode(), 
				$exception)
			))
		);
	}
	
	// Initialisation & pagehandler ///////////////////////////////////////////////////////////
	
	/**
	 * Initialise the API subsystem.
	 *
	 */
	function api_init()
	{
		// Register a page handler, so we can have nice URLs
		register_page_handler('api','api_endpoint_handler');
	}
	
	/**
	 * Register a page handler for the various API endpoints.
	 *
	 * @param array $page
	 */
	function api_endpoint_handler($page) 
	{
		global $CONFIG;
		
		// Which view
		if ($page[1])
		{
			elgg_set_viewtype($page[1]);
			
		}
		
		// Which endpoint
		if ($page[0])
		{
			switch ($page[0])
			{
				case 'rest' :
				default : include($CONFIG->path . "services/api/rest.php");
			}
		} 
	}
	
	
	register_elgg_event_handler('init','system','api_init');
	
?>