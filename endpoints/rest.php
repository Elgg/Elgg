<?php
	/**
	 * Rest endpoint.
	 * The API REST endpoint.
	 * 
	 * @package Elgg
	 * @subpackage API
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey <marcus@dushka.co.uk>
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	// Include required files
	require_once('../engine/start.php');
	global $ApiEnvironment;
	
	// Register the error handler
	error_reporting(E_ALL); 
	set_error_handler('__php_api_error_handler');
	
	// Register a default exception handler
	set_exception_handler('__php_api_exception_handler'); 
	
	// Get parameter variables
	$format = get_input('format', 'php');
	$method = get_input('method');
	$result = null;
	
	
	// See if we have a session
	/**
	 * If we have a session then we can assume that this is being called by AJAX from 
	 * within an already logged on browser.
	 * 
	 * NB. This may be a gaping security hole, but hey ho. 
	 */
	if (!isloggedin())
	{
		// Get api header
		$api_header = get_and_validate_api_headers();
		$ApiEnvironment->api_header = $api_header;
		
		// Get site


		

		// Pull API user details
		$ApiEnvironment->api_user = get_api_user($api_header->api_key);
		
		if ($ApiEnvironment->api_user)
		{
			// Get the secret key
			$secret_key = $ApiEnvironment->api_user->secret;
				
			// Validate HMAC
			$hmac = calculate_hmac($api_header->hmac_algo, 
					$api_header->time, 
					$api_header->api_key, 
					$secret_key, 
					$api_header->get_variables, 
					$api_header->method == 'POST' ? $api_header->posthash : "");
				
			if (strcmp(
				$api_header->hmac,
				$hmac	
			)==0)
			{
				// Now make sure this is not a replay
				if (!cache_hmac_check_replay($hmac)) 
				{
					$postdata = "";
					$token = "";
					$params = $_REQUEST;
					
					// Validate post data
					if ($api_header->method=="POST")
					{
						$postdata = get_post_data();
						$calculated_posthash = calculate_posthash($postdata, $api_header->posthash_algo);

						if (strcmp($api_header->posthash, $calculated_posthash)!=0)
							throw new SecurityException("POST data hash is invalid - Expected $calculated_posthash but got {$api_header->posthash}.");
					}
					
					// Execute 
					if (isset($params['auth_token'])) 
					$result = execute_method($method, $params, $token);
				}
				else
					throw new SecurityException("Packet signature already seen.");
			}
			else 
				throw new SecurityException("HMAC is invalid.  {$api_header->hmac} != [calc]$hmac = {$api_header->hmac_algo}(**SECRET KEY**, time:{$api_header->time}, apikey:{$api_header->api_key}, get_vars:{$api_header->get_variables}" . ($api_header->method=="POST"? "posthash:$api_header->posthash}" : ")"));
		}
		else
			throw new SecurityException("Invalid or missing API Key.",ErrorResult::$RESULT_FAIL_APIKEY_INVALID);
	}
	else
	{
		// TODO: set site environment
		
		// User is logged in, just execute 
		if (isset($params['auth_token'])) $token = $params['auth_token'];
		$result = execute_method($method, $params, $token);	
	}


	// Finally output
	if (!($result instanceof GenericResult))
		throw new APIException("API Result is of an unknown type, this should never happen.");
		
	output_result($result, $format);
		
?>