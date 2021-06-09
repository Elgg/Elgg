<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'profile' => 'Profile',
	'profile:notfound' => 'Sorry. We could not find the requested profile.',
	
	'admin:configure_utilities:profile_fields' => 'Edit Profile Fields',
	
	'profile:edit' => 'Edit profile',
	'profile:description' => "About me",
	'profile:briefdescription' => "Brief description",
	'profile:location' => "Location",
	'profile:skills' => "Skills",
	'profile:interests' => "Interests",
	'profile:contactemail' => "Contact email",
	'profile:phone' => "Telephone",
	'profile:mobile' => "Mobile phone",
	'profile:website' => "Website",
	'profile:twitter' => "Twitter username",
	'profile:saved' => "Your profile was successfully saved.",

	'profile:field:text' => 'Short text',
	'profile:field:longtext' => 'Large text area',
	'profile:field:tags' => 'Tags',
	'profile:field:url' => 'Web address',
	'profile:field:email' => 'Email address',
	'profile:field:tel' => 'Telephone',
	'profile:field:location' => 'Location',
	'profile:field:date' => 'Date',
	'profile:field:datetime-local' => 'Date Time',
	'profile:field:month' => 'Month',
	'profile:field:week' => 'Week',
	'profile:field:color' => 'Color',

	'profile:label' => "Profile label",
	'profile:type' => "Profile type",
	'profile:editdefault:delete:fail' => 'Removing profile field failed',
	'profile:editdefault:delete:success' => 'Profile field deleted',
	'profile:defaultprofile:reset' => 'Profile fields reset to the system default',
	'profile:resetdefault' => 'Reset profile fields to system defaults',
	'profile:resetdefault:confirm' => 'Are you sure you want to delete your custom profile fields?',
	'profile:explainchangefields' => "You can replace the existing profile fields with your own.
Click the 'Add' button and give the new profile field a label, for example, 'Favorite team', then select the field type (eg. text, url, tags).
To re-order the fields drag on the handle next to the field label.
To edit a field label - click on the edit icon.

At any time you can revert back to the default profile set up, but you will lose any information already entered into custom fields on profile pages.",
	'profile:editdefault:success' => 'New profile field added',
	'profile:editdefault:fail' => 'Default profile could not be saved',
	'profile:noaccess' => "You do not have permission to edit this profile.",
	'profile:invalid_email' => '%s must be a valid email address.',
);
