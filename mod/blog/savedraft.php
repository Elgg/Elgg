<?php

	/**
	 * Elgg blog autosaver
	 */

	// Load engine
		require_once(dirname(dirname(dirname(__FILE__))) . '/engine/start.php');
		gatekeeper();
		
	// Get input data
		$title = $_POST['blogtitle'];
		$body = $_POST['blogbody'];
		$tags = $_POST['blogtags'];
	
		$_SESSION['user']->blogtitle = $title;
		$_SESSION['user']->blogbody = $body;
		$_SESSION['user']->blogtags = $tags;
		
?>