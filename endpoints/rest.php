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

	
	
	
	
	/*

	 Elgg API system
A brief specification: internal only

NB: this is a loose specification, and as such some holes or shortcomings may become evident in
implementation.  Therefore, feel free to adjust as necessary, bearing in mind the goals, which 
are unmovable ...

Goals:  an extensible, two-way API that can be used to easily code secure client applications 
on a variety of networked systems, whether web or application-based.  The results should be available, 
at the very least, in JSON, serialised PHP, XML and CSV, but the output formats should also be 
extensible by plugins in a documented way.  Similarly, plugins must be able to add new function calls, 
in a similar way to how they register events or enable actions.






On release, we will need to provide simple client libraries for PHP, .NET, C, Java and (although this 
can hopefully be outsourced to Kevin or similar) Ruby on Rails.  Additionally, Django, vanilla Python 
and Perl libraries would be a bonus, although not required.

Brief implementation requirements:   A set of procedural functions.  If possible, the output should 
use the existing views system, creating a new base view set for xml, json, csv and php.  That way other 
output formats can be specified simply by changing the &view URL parameter, and added / extended by plugins.  
(It would also allow RSS output pretty much for free for certain types of data.)  On failure, a friendly 
message should be returned in a way that can be read by the client software.

These functions should be made available in a simple api.php module within engine/lib.php, without the use of
any external libraries.  If an external library really must be used, ensure that it has a compatible license 
and can be used on all systems where Elgg can be installed, including Apache for Windows and Apache-compatible 
web servers.

When a plugin or core software module registers an API call, it should reference a function name, the 
parameters it requires, and an English description of the call.  A special API call – and internal function - 
should return a list of enabled calls, for the use of client applications and internal help pages respectively.

As one application of the API is as a back-end for AJAX applications, the API endpoint should check $_SESSION 
for logged in user information before checking for any other kind of login data.  This way the browser can 
simply make an asynchronous callback request, allowing for many very interesting Javascript applications.
In an ideal world, client applications should not need a special API key.  This is because an application would 
have to install a new key for each installed Elgg site, which is not preferable, as it has a serious user 
experience hit (before the user can use a new client software on a particular install, they have to go to 
their account settings and obtain something that to them looks like a string of gobbledygook).  If possible, 
all the client application should need is a valid username and password.

Using a $CONFIG configuration option, site admins should be able to shut down the entire API system if 
required, or disallow the $_SESSION authentication method.

	 */
	
	
	// Include required files
	require_once('../engine/start.php');
	global $CONFIG;

	// Register the error handler
	error_reporting(E_ALL); 
	set_error_handler('__php_api_error_handler');
	
	// Register a default exception handler
	set_exception_handler('__php_api_exception_handler'); 
	
	// Check to see if the api is available
	if ((isset($CONFIG->disable_api)) && ($CONFIG->disable_api == true))
		throw new ConfigurationException("Sorry, API access has been disabled by the administrator.");
	
	
	
	
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
		//$CONFIG->api_header = get_and_validate_api_headers(); // Get api header
		//$CONFIG->api_user = get_api_user($CONFIG->api_header->api_key); // Pull API user details
	
	
		
		
		
		
		
		
		
		
	
	}
	else
	{
		// User is logged in, just execute 
		
		
		
		
	}
	
	// Finally output
	if (!($result instanceof GenericResult))
		throw new APIException("API Result is of an unknown type, this should never happen.");

	// Output the result
	echo output_result($result, $format);

	
	
	
	
	
	
	
	
	
	
	

	
	
	
	
	
	
	
	
	
	
	// See if we have a session
	/**
	 * If we have a session then we can assume that this is being called by AJAX from 
	 * within an already logged on browser.
	 * 
	 * NB. This may be a gaping security hole, but hey ho. 
	 */
//	if (!isloggedin())
//	{
/*		// Get api header
		$api_header = get_and_validate_api_headers();
		$ApiEnvironment->api_header = $api_header;
		
		// Pull API user details
		$ApiEnvironment->api_user = get_api_user($api_header->api_key);
		
		// Get site
		$ApiEnvironment->site_id = $ApiEnvironment->api_user->side_id;	
		
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
	}*/
//	else
//	{
//		// Set site environment
//		$ApiEnvironment->site_id = $CONFIG->site_id;
//		
//		// User is logged in, just execute 
//		if (isset($params['auth_token'])) $token = $params['auth_token'];
//		$result = execute_method($method, $params, $token);	
//	}


	
		
?>