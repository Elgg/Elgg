<?php

	global $CONFIG;
	
	/// Activate captcha
	/**
	 * Elgg now has a basic captcha service built in, enable it by default
	 */	
	enable_plugin('captcha', $CONFIG->site->guid);
?>