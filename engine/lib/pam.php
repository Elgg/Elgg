<?php

	/**
	 * Elgg PAM library
	 * Contains functions for managing authentication using various arbitrary methods
	 * 
	 * @package Elgg
	 * @subpackage Core

	 * @author Curverider Ltd

	 * @link http://elgg.org/
	 */

	$_PAM_HANDLERS = array();
	$_PAM_HANDLERS_MSG = array();
	
	
	/**
	 * Register a PAM handler.
	 * 
	 * @param string $handler The handler function in the format 
	 * 		pam_handler($credentials = NULL);
	 * @param string $importance The importance - "sufficient" or "required"
	 */
	function register_pam_handler($handler, $importance = "sufficient")
	{
		global $_PAM_HANDLERS;
		
		if (is_callable($handler))
		{
			$_PAM_HANDLERS[$handler] = new stdClass;
			
			$_PAM_HANDLERS[$handler]->handler = $handler;
			$_PAM_HANDLERS[$handler]->importance = strtolower($importance);
			
			return true;
		}
		
		return false;
	}
	
	/**
	 * Attempt to authenticate.
	 * This function will go through all registered PAM handlers to see if a user can be authorised.
	 *
	 * If $credentials are provided the PAM handler should authenticate using the provided credentials, if
	 * not then credentials should be prompted for or otherwise retrieved (eg from the HTTP header or $_SESSION).
	 * 
	 * @param mixed $credentials Mixed PAM handler specific credentials (eg username,password or hmac etc)
	 * @return bool true if authenticated, false if not.
	 */
	function pam_authenticate($credentials = NULL)
	{
		global $_PAM_HANDLERS, $_PAM_HANDLERS_MSG;
		
		$authenticated = false;
		
		foreach ($_PAM_HANDLERS as $k => $v)
		{
			$handler = $v->handler;
			$importance = $v->importance;
		
			try {
				// Execute the handler 
				if ($handler($credentials))
				{
					// Explicitly returned true
					$_PAM_HANDLERS_MSG[$k] = "Authenticated!";

					$authenticated = true;
				}
				else
				{
					$_PAM_HANDLERS_MSG[$k] = "Not Authenticated.";
				
					// If this is required then abort.
					if ($importance == 'required')
						return false;
				}
			} 
			catch (Exception $e)
			{		
				$_PAM_HANDLERS_MSG[$k] = "$e";
				
				// If this is required then abort.
				if ($importance == 'required')
					return false;
			}	
		}
		
		return $authenticated;
	}
	
?>