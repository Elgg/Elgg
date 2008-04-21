<?php

	/**
	 * Elgg profile plugin language pack
	 * 
	 * @package ElggProfile
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Ben Werdmuller <ben@curverider.co.uk>
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

	$english = array(
	
		/**
		 * Menu items and titles
		 */
	
			'profile' => "Profile",
			'profile:yours' => "Your profile",
			'profile:user' => "%s's profile",
	
			'profile:edit' => "Edit profile",
	
			'profile:aboutme' => "About me", 
			'profile:location' => "Location",
			'profile:skills' => "Skills",  
			'profile:interests' => "Interests", 

		/**
		 * Status messages
		 */
	
			'profile:saved' => "Your profile was successfully saved.",
	
		/**
		 * Error messages
		 */
	
			'profile:notfound' => "Sorry; we could not find the specified profile.",
			'profile:cantedit' => "Sorry; you do not have permission to edit this profile.",
	
	);
					
	add_translation("en",$english);

?>