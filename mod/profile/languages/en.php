<?php
	/**
	 * Elgg profile plugin language pack
	 *
	 * @package ElggProfile
	 */

	$english = array(

	/**
	 * Profile
	 */

		'profile' => "Profile",
		'profile:edit:default' => 'Replace profile fields',
		'profile:preview' => 'Preview',

	/**
	 * Profile menu items and titles
	 */

		'profile:yours' => "Your profile",
		'profile:user' => "%s's profile",

		'profile:edit' => "Edit profile",
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
		'profile:briefdescription' => "Brief description",
		'profile:location' => "Location",
		'profile:skills' => "Skills",
		'profile:interests' => "Interests",
		'profile:contactemail' => "Contact email",
		'profile:phone' => "Telephone",
		'profile:mobile' => "Mobile phone",
		'profile:website' => "Website",

		'profile:banned' => 'This user account has been suspended.',
		'profile:deleteduser' => 'Deleted user',

		'profile:river:update' => "%s updated their profile",
		'profile:river:iconupdate' => "%s updated their profile icon",

		'profile:label' => "Profile label",
		'profile:type' => "Profile type",

		'profile:editdefault:fail' => 'Default profile could not be saved',
		'profile:editdefault:success' => 'Item successfully added to default profile',


		'profile:editdefault:delete:fail' => 'Removed default profile item field failed',
		'profile:editdefault:delete:success' => 'Default profile item deleted!',

		'profile:defaultprofile:reset' => 'Default system profile reset',

		'profile:resetdefault' => 'Reset default profile',
		'profile:explainchangefields' => 'You can replace the existing profile fields with your own using the form below. First you give the new profile field a label, for example, \'Favourite team\'. Next you need to select the field type, for example, tags, url, text and so on. At any time you can revert back to the default profile set up.',


	/**
	 * Profile status messages
	 */

		'profile:saved' => "Your profile was successfully saved.",
		'profile:icon:uploaded' => "Your profile picture was successfully uploaded.",

	/**
	 * Profile error messages
	 */

		'profile:noaccess' => "You do not have permission to edit this profile.",
		'profile:notfound' => "Sorry, we could not find the specified profile.",
		'profile:icon:notfound' => "Sorry, there was a problem uploading your profile picture.",
		'profile:icon:noaccess' => 'You cannot change this profile icon',
		'profile:field_too_long' => 'Cannot save your profile information because the "%s" section is too long.',

	);

	add_translation("en",$english);