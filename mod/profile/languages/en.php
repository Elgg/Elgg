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
			'profile:editicon' => "Upload a new profile picture",
			'profile:profilepictureinstructions' => "The profile picture is the image that's displayed on your profile page. <br /> You can change it as often as you'd like. (File formats accepted: GIF, JPG or PNG)",
			'profile:icon' => "Profile picture",
			'profile:createicon' => "Create your avatar",
			'profile:currentavatar' => "Current avatar",
			'profile:createicon:header' => "Profile picture",
			'profile:profilepicturecroppingtool' => "Profile picture cropping tool",
			'profile:createicon:instructions' => "Click and drag a square below to match how you want your picture cropped.  A preview of your cropped picture will appear in the box on the right.  When you are happy with the preview, click 'Create your avatar'. This cropped image will be used throughout the site as your avatar. ",
	
			'profile:editdetails' => "Edit details",
			'profile:editicon' => "Edit profile icon",
	
			'profile:aboutme' => "About me", 
			'profile:description' => "About me", 
			'profile:location' => "Location",
			'profile:skills' => "Skills",  
			'profile:interests' => "Interests", 
			'profile:contactemail' => "Contact email",
			'profile:phone' => "Telephone",
			'profile:mobile' => "Mobile phone",
			'profile:website' => "Website",

			'profile:river:update' => "%s updated their profile",
	
		/**
		 * Status messages
		 */
	
			'profile:saved' => "Your profile was successfully saved.",
			'profile:icon:uploaded' => "Your profile picture was successfully uploaded.",
	
		/**
		 * Error messages
		 */
	
			'profile:noaccess' => "You do not have permission to edit this profile.",
			'profile:notfound' => "Sorry; we could not find the specified profile.",
			'profile:cantedit' => "Sorry; you do not have permission to edit this profile.",
			'profile:icon:notfound' => "Sorry; there was a problem uploading your profile picture.",
	
	);
					
	add_translation("en",$english);

?>