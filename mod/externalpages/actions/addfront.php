<?php

	/**
	 * Elgg front pages: add/edit
	 * Here we use the title field for the lefthand side and the description for the righthand side
	 * 
	 * @package ElggExPages
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.org/
	 */

	// Make sure we're logged as admin
		admin_gatekeeper();

	// Get input data
		$contents_left = get_input('front_left', '', false);
		$contents_right = get_input('front_right', '', false);
		$previous_guid = get_input('front_guid');
		
		//remove the old front page
		if(get_entity($previous_guid)){
			delete_entity($previous_guid);
		}
			
		// Initialise a new ElggObject
			$frontpage = new ElggObject();
		// Tell the system what type of external page it is
			$frontpage->subtype = "front";
		// Set its owner to the current user
			$frontpage->owner_guid = $_SESSION['user']->getGUID();
		// For now, set its access to public
			$frontpage->access_id = ACCESS_PUBLIC;
		// Set its title and description appropriately
			$frontpage->title = $contents_left;
			$frontpage->description = $contents_right;
			
		// Before we can set metadata, save
			if (!$frontpage->save()) {
				register_error(elgg_echo("expages:error"));
				forward("pg/expages/index.php?type=front");
			}
		
		// Success message
			system_message(elgg_echo("expages:posted"));
		
		
	// Forward back to the page
			forward("pg/expages/index.php?type=front");
		
?>
