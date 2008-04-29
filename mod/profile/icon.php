<?php

	/**
	 * Elgg profile icon
	 * 
	 * @package ElggProfile
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Ben Werdmuller <ben@curverider.co.uk>
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

	// Load the Elgg framework
		require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

	// Get the owning user

		$user = page_owner_entity(); // page_owner_entity();
		$username = $user->username;
		
	// Get the size
		$size = strtolower(get_input('size'));
		if (!in_array($size,array('large','medium','small')))
			$size = "medium";
		
	// Try and get the icon
	
		$filehandler = new ElggFile();
		$filehandler->setFilename($username . $size . ".jpg");
		if ($filehandler->open("read") && $contents = $filehandler->read($filehandler->size())) {
		} else {
			
			global $CONFIG;
			$contents = @file_get_contents($CONFIG->pluginspath . "profile/graphics/default{$size}.jpg");
			
		}
		
		header("Content-type: image/jpeg");
		echo $contents;

?>