<?php

	/**
	 * Elgg blog autosaver
	 * 
	 * @package ElggBlog
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
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