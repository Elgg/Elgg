<?php

	/**
	 * Elgg blog autosaver
	 *
	 * @package ElggBlog
	 */

	// Load engine
		require_once(dirname(dirname(dirname(__FILE__))) . '/engine/start.php');
		gatekeeper();

	// Get input data
		$title = $_POST['blogtitle'];
		$body = $_POST['blogbody'];
		$tags = $_POST['blogtags'];
		$guid = get_input('blogpost', 0);

		$_SESSION['user']->blogtitle = $title;
		$_SESSION['user']->blogbody = $body;
		$_SESSION['user']->blogtags = $tags;
		$_SESSION['user']->blogguid = $guid;

?>