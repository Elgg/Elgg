<?php
/**
 * Elgg profile plugin language pack
 */

$english = array(

/**
 * Profile
 */

	'profile' => "Profile",
	'profile:edit:default' => 'Profile fields',
	'profile:preview' => 'Preview',

/**
 * Profile menu items and titles
 */

	'profile:yours' => "My profile",
	'profile:user' => "%s's profile",

	'profile:edit' => "Edit profile",
	'profile:profilepictureinstructions' => "Your avatar is the image that's displayed on your profile page. <br /> You can change it as often as you'd like. (File formats accepted: GIF, JPG or PNG)",
	'profile:icon' => "Avatar",
	'profile:createicon' => "Create your avatar",
	'profile:currentavatar' => "Current avatar",
	'profile:createicon:header' => "Profile picture",
	'profile:profilepicturecroppingtool' => "Avatar cropping tool",
	'profile:createicon:instructions' => "Click and drag a square below to match how you want your avatar cropped.  A preview will appear in the box on the right.  When you are happy with the preview, click 'Create your avatar'. This cropped version will be used throughout the site as your avatar. ",

	'profile:editdetails' => "Edit profile",
	'profile:editicon' => "Edit avatar",

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
	'profile:twitter' => "Twitter username",
	'twitter:visit' => "Visit this Twitter account",
	'profile:editdefault:fail' => 'Default profile could not be saved',
	'profile:editdefault:success' => 'Item successfully added to default profile',


	'profile:editdefault:delete:fail' => 'Removed default profile item field failed',
	'profile:editdefault:delete:success' => 'Default profile item deleted!',

	'profile:defaultprofile:reset' => 'Default system profile reset',

	'profile:resetdefault' => 'Reset default profile',
	'profile:explainchangefields' => 'You can replace the existing profile fields with your own using the form below. <br /><br />Give the new profile field a label, for example, \'Favorite team\', then select the field type (eg. text, url, tags), and click the \'Add\' button. To re-order the fields drag on the handle next to the field label. To edit a field label - click on the label\'s text to make it editable. <br />At any time you can revert back to the default profile set up, but you will loose any information already entered into custom fields on profile pages.',


/**
 * Profile status messages
 */

	'profile:saved' => "Your profile was successfully saved.",
	'profile:icon:uploaded' => "Your profile picture was successfully uploaded.",

/**
 * Profile comment wall
 **/
	'profile:commentwall:add' => "Add to the wall",
	'profile:commentwall' => "Comment Wall",
	'profile:commentwall:posted' => "You successfully posted on the comment wall.",
	'profile:commentwall:deleted' => "You successfully deleted the message.",
	'profile:commentwall:blank' => "Sorry; you need to actually put something in the message area before we can save it.",
	'profile:commentwall:notfound' => "Sorry; we could not find the specified item.",
	'profile:commentwall:notdeleted' => "Sorry; we could not delete this message.",
	'profile:commentwall:none' => "No comment wall posts found.",
	'profile:commentwall:somethingwentwrong' => "Something went wrong when trying to save your message, make sure you actually wrote a message.",
	'profile:commentwall:failure' => "An unexpected error occurred when adding your message. Please try again.",

/**
 * Email messages commentwall
 */

	'profile:comment:subject' => 'You have a new message on your comment wall!',
	'profile:comment:body' => "You have a new message on your comment wall from %s. It reads:


%s


To view your message board comments, click here:

	%s

To view %s's profile, click here:

	%s

You cannot reply to this email.",

/**
 * Profile error messages
 */

	'profile:no_friends' => 'This person hasn\'t added any friends yet!',
	'profile:noaccess' => "You do not have permission to edit this profile.",
	'profile:notfound' => "Sorry, we could not find the specified profile.",
	'profile:icon:notfound' => "Sorry, there was a problem uploading your profile picture.",
	'profile:icon:noaccess' => 'You cannot change this profile icon',
	'profile:field_too_long' => 'Cannot save your profile information because the "%s" section is too long.',

);

add_translation('en', $english);