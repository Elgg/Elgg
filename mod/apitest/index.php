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

	require_once("../../engine/start.php");
	
	global $CONFIG, $API_CLIENT;
	
	
	// Get some variables
	$apikey = get_input("apikey");
	$secret = get_input("secret");
	$endpoint = get_input("endpoint");
	
	
	if ($_REQUEST['action'] == "configure")
   		apitest_configure($apikey, $secret, $endpoint);
   		
	// Get a list of commands
	if ($API_CLIENT->configured == true)
	{
		$commands = apitest_call(
	                array (
	                        'method' => 'system.api.list'
	                )
	    );
	    $commands = $commands->result;
	}

	/* See if we are executing a method - This is a quick demo, obviously use functions as they are much easier!*/
	if (isset($_REQUEST['method']))
	{
	
		$command_details = $commands[$_REQUEST['method']];
		$auth_req = $command_details['require_auth'] == 1 ? true : false;
		
		$params = array();
		$params['method'] = $_REQUEST['method'];
		if ($auth_req) 
			$params['auth_token'] = $_REQUEST['auth_token'];
		
		foreach ($command_details['parameters'] as $k => $v)
		{
			$params[$k] = $_REQUEST[$k];
		}
		
		$result = apitest_call($params, $_REQUEST['post_data']);
		
		
		if ($result->status == 0)
			system_message("<div id=\"result\"><pre>".print_r($result->result, true)."</pre></div>");
		else 
			register_error($result->message);
					
		if (!is_object($result)) echo $LAST_CALL_RAW;
		
		
		
	}

	// Draw command form
	$list = "";
	foreach ($commands as $command => $details)
		$list .= apitest_draw_command_form($command, $details);
		
	$body = elgg_view_layout("one_column", elgg_view("apitest/main", array(
		"config" => apitest_draw_config_panel(),
		"commandlist" => $list
	)));
	
	page_draw("API Commands",$body);
?>