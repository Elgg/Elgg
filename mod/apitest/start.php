<?php
	/**
	 * Elgg API Tester
	 * 
	 * @package ElggDevTools
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey <marcus@marcus-povey.co.uk>
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

	$API_CLIENT = new stdClass;
	
	// Status variables we can query later
	$LAST_CALL = null; 
	$LAST_CALL_RAW = "";
	$LAST_ERROR = null;
	

	function apitest_init($event, $object_type, $object = null) {
		
		global $CONFIG;
			
		add_menu("API Test",$CONFIG->wwwroot . "mod/apitest/",array(
				menu_item("The API Tester plugin",$CONFIG->wwwroot."mod/apitest/"),
		));
	}
	
	/**
	 * Generate our HMAC.
	 */
	function apitest_calculate_hmac($algo, $time, $api_key, $secret_key, $get_variables, $post_hash = "")
	{
		$ctx = hash_init($algo, HASH_HMAC, $secret_key);

		hash_update($ctx, trim($time));
		hash_update($ctx, trim($api_key));
		hash_update($ctx, trim($get_variables));
		if (trim($post_hash)!="") hash_update($ctx, trim($post_hash));

		return hash_final($ctx);
	}

	/**
	 * Generate our POST hash.
	 */
	function apitest_calculate_posthash($postdata, $algo)
	{
		$ctx = hash_init($algo);

		hash_update($ctx, $postdata);

		return hash_final($ctx);
	}

	/**
	 * Serialise HTTP headers.
	 */
	function apitest_serialise_headers(array $headers)
	{
		$headers_str = "";

		foreach ($headers as $k => $v)
			$headers_str .= trim($k) . ": " . trim($v) . "\r\n";

		return trim($headers_str);		
	}

	/**
	 * Make a raw call.
	 * @param array $method Method call parameters.
	 * @param string $postdata Optional POST data.
	 * @param string $content_type The content type.
	 * @return stdClass 
	 */
	function apitest_call(array $method, $postdata = "", $content_type = 'application/octet-stream')
	{
		// Get the config
		global $API_CLIENT, $LAST_CALL, $LAST_CALL_RAW, $LAST_ERROR; 

		$headers = array();
		$encoded_params = array();

		$time = microtime(true); // Get the current time in microseconds
		$request = ($postdata!="" ? "POST" : "GET"); // Get the request method, either post or get
		
		// Hard code the format - we're using PHP, so lets use PHP serialisation.
		$method['format'] = "php";

		// URL encode all the parameters
		foreach ($method as $k => $v){
			if (is_array($v))
			{
				foreach ($v as $v2)
				{
					 $encoded_params[] = urlencode($k).'[]='.urlencode($v2);
				}
			}
			else
				$encoded_params[] = urlencode($k).'='.urlencode($v);
		}

		$params = implode('&', $encoded_params);
		
		// Put together the query string
		$url = $API_CLIENT->api_endpoint."?". $params;

		// Construct headers
		$posthash = "";
		if ($request=='POST')
		{		
			$posthash = apitest_calculate_posthash($postdata, $API_CLIENT->postdata_hash_algo);

			$headers['X-Elgg-posthash'] = $posthash;
			$headers['X-Elgg-posthash-algo'] = $API_CLIENT->postdata_hash_algo;
			$headers['Content-type'] = $content_type;
			$headers['Content-Length'] = strlen($postdata);
		}

		$headers['X-Elgg-apikey'] = $API_CLIENT->api_key;
		$headers['X-Elgg-time'] = $time;
		$headers['X-Elgg-hmac-algo'] = $API_CLIENT->hmac_algo;
		$headers['X-Elgg-hmac'] = apitest_calculate_hmac($API_CLIENT->hmac_algo, 
									$time,
									$API_CLIENT->api_key,
									$API_CLIENT->secret,
									$params,
									$posthash
		);

		// Configure stream options
		$opts = array(
  			'http'=>array(
    				'method'=> $request,
    				'header'=> apitest_serialise_headers($headers)
			)
		);

		// If this is a post request then set the content
		if ($request=='POST')
			$opts['http']['content'] = $postdata; 

		// Set stream options
		$context = stream_context_create($opts);

		// Send the query and get the result and decode.
		$LAST_CALL_RAW = file_get_contents($url, false, $context);
		$LAST_CALL = unserialize($LAST_CALL_RAW);
		
		if (($LAST_CALL) && ($LAST_CALL->status!=0)) // Check to see if this was an error
			$LAST_ERROR = $LAST_CALL;
		
		return $LAST_CALL; // Return a stdClass containing the API result
	}
	
	function apitest_configure($apikey, $secret, $endpoint = "")
	{
		global $CONFIG;
		global $API_CLIENT;
		
		$apikey = sanitise_string($apikey);
		$secret = sanitise_string($secret);
		$endpoint = sanitise_string($endpoint);
		
		if ($endpoint=="")
			$endpoint = $CONFIG->wwwroot . "services/api/rest.php";
			
		$API_CLIENT->api_key = $apikey;
		$API_CLIENT->secret = $secret;
		$API_CLIENT->api_endpoint = $endpoint;
		$API_CLIENT->hmac_algo = 'sha1';
		$API_CLIENT->postdata_hash_algo = 'md5';
		$API_CLIENT->configured = true;
	}
	
	function apitest_draw_command_form($command, $details)
	{
		global $API_CLIENT;
		
		$params = array();
		
		// If authentication is required then ensure this is prompted for
		if ($details->require_auth == true)
			$params['auth_token'] = $_REQUEST['auth_token'];
					
		
		// Compile a list of parameters
		foreach ($details['parameters'] as $k => $v)
		{
			$params[$k] = $_REQUEST[$k];
		}
		
		// Construct list of variables
		$variables = "";
		foreach ($params as $k => $v)
		{
			$variables .= $k;
			$variables .= "<input type='text' name='$k' value='$v' />";
			
			if (isset($details['parameters'][$k]['required']) && ($details['parameters'][$k]['required']!=0))
				$variables .= " (optional)";
							
			$variables .= ", ";
		}
		
		// Do we need to provide post data?
		$postdata = "";
		if ($details->call_method == 'POST')
			$postdata = "<span onClick=\"showhide('$command')\"><a href=\"#\">add post data...</a></span>";
				
		$body = <<< END
			<form method='post'>
				<p>
					<input type="hidden" name="action" value="configure" />
					<input type="hidden" name="apikey" value="{$API_CLIENT->api_key}" /></p>
					<input type="hidden" name="secret" value="{$API_CLIENT->secret}" /></p>
					<input type="hidden" name="endpoint" value="{$API_CLIENT->api_endpoint}" /></p>

					<input type='hidden' name='method' value='$command' />
					<b>$command (<span onClick="showhide('{$command}_desc')"><a href="#">desc</a></span>):</b>
					
					$variables

					$postdata
					
					<input type='submit' name='>>' value='>>' />
					<div id="{$command}_desc" style="display:none">{$details['description']}</div>
					<div id="$command" style="display:none"><textarea name="post_data" cols="50" rows="10"></textarea></div>

				</p>
			</form>
END;

		return $body;
	}
	
	
	function apitest_draw_config_panel()
	{	
		global $API_CLIENT;
		
		return elgg_view("apitest/configform", array(
			"apikey" => $API_CLIENT->api_key,
			"secret" => $API_CLIENT->secret,
			"endpoint" => $API_CLIENT->api_endpoint
		));
	}
	
	// Make sure test_init is called on initialisation
	register_elgg_event_handler('init','system','apitest_init');
?>