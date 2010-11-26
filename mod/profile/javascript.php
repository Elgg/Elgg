<?php

	/**
	 * Elgg profile JS
	 * 
	 * @package ElggProfile
	 */

	// Get engine
		require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

		header("Content-type: text/javascript");
		header('Expires: ' . date('r',time() + 864000));
		header("Pragma: public");
		header("Cache-Control: public");
		echo elgg_view('profile/javascript');

?>