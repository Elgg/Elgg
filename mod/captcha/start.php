<?php
	/**
	 * Elgg captcha plugin
	 * 
	 * @package ElggCaptcha
	 */

	function captcha_init()
	{
		global $CONFIG;
		
		// Register page handler for captcha functionality
		register_page_handler('captcha','captcha_page_handler');
		
		// Extend CSS
		elgg_extend_view('css','captcha/css');
		
		// Number of background images
		$CONFIG->captcha_num_bg = 5;
		
		// Default length
		$CONFIG->captcha_length = 5;
		
		// Register a function that provides some default override actions
		register_plugin_hook('actionlist', 'captcha', 'captcha_actionlist_hook');
		
		// Register actions to intercept
		$actions = array();
		$actions = trigger_plugin_hook('actionlist', 'captcha', null, $actions);
		
		if (($actions) && (is_array($actions)))
		{
			foreach ($actions as $action)
				register_plugin_hook("action", $action, "captcha_verify_action_hook");
		}
	}
	
	function captcha_page_handler($page) 
	{
		global $CONFIG;
		
		if (isset($page[0])) {
			set_input('captcha_token',$page[0]);
		}

		include($CONFIG->pluginspath . "captcha/captcha.php");
	}
	
	/**
	 * Generate a token to act as a seed value for the captcha algorithm.
	 */
	function captcha_generate_token()
	{
		return md5(generate_action_token(time()).rand()); // Use action token plus some random for uniqueness
	}
	
	/**
	 * Generate a captcha based on the given seed value and length.
	 *
	 * @param string $seed_token
	 * @return string
	 */
	function captcha_generate_captcha($seed_token)
	{
		global $CONFIG;
		
		/*
		 * We generate a token out of the random seed value + some session data, 
		 * this means that solving via pr0n site or indian cube farm becomes
		 * significantly more tricky (we hope).
		 * 
		 * We also add the site secret, which is unavailable to the client and so should
		 * make it very very hard to guess values before hand.
		 * 
		 */
		
		return strtolower(substr(md5(generate_action_token(0) . $seed_token), 0, $CONFIG->captcha_length));
	}
	
	/**
	 * Verify a captcha based on the input value entered by the user and the seed token passed.
	 *
	 * @param string $input_value
	 * @param string $seed_token
	 * @return bool
	 */
	function captcha_verify_captcha($input_value, $seed_token)
	{
		if (strcasecmp($input_value, captcha_generate_captcha($seed_token)) == 0)
			return true;
			
		return false;
	}
	
	/**
	 * Listen to the action plugin hook and check the captcha.
	 *
	 * @param unknown_type $hook
	 * @param unknown_type $entity_type
	 * @param unknown_type $returnvalue
	 * @param unknown_type $params
	 */
	function captcha_verify_action_hook($hook, $entity_type, $returnvalue, $params)
	{
		$token = get_input('captcha_token');
		$input = get_input('captcha_input');
		
		if (($token) && (captcha_verify_captcha($input, $token)))
			return true;
		
		register_error(elgg_echo('captcha:captchafail'));

		// forward to referrer or else action code sends to front page
		forward(REFERER);
			
		return false;
	}
	
	/**
	 * This function returns an array of actions the captcha will expect a captcha for, other plugins may
	 * add their own to this list thereby extending the use.
	 *
	 * @param unknown_type $hook
	 * @param unknown_type $entity_type
	 * @param unknown_type $returnvalue
	 * @param unknown_type $params
	 */
	function captcha_actionlist_hook($hook, $entity_type, $returnvalue, $params)
	{
		if (!is_array($returnvalue))
			$returnvalue = array();
			
		$returnvalue[] = 'register';
		$returnvalue[] = 'user/requestnewpassword';
			
		return $returnvalue;
	}
	
	register_elgg_event_handler('init','system','captcha_init');
?>