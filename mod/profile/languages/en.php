<?php

return array(
	'profile' => 'Profile',
	'profile:notfound' => 'Sorry. We could not find the requested profile.',
	'profile:upgrade:2017040700:title' => 'Migrate schema of profile fields',
	'profile:upgrade:2017040700:description' => 'This migration converts profile fields from metadata to annotations with each name
prefixed with "profile:". <strong>Note:</strong> If you have "inactive" profile fields you want migrated, re-create those fields
and re-load this page to make sure they get migrated.',
	
	'admin:configure_utilities:profile_fields' => 'Edit Profile Fields',
	
	'profile:edit' => 'Edit profile',
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
	'profile:twitter' => "Twitter username",
	'profile:saved' => "Your profile was successfully saved.",

	'profile:field:text' => 'Short text',
	'profile:field:longtext' => 'Large text area',
	'profile:field:tags' => 'Tags',
	'profile:field:url' => 'Web address',
	'profile:field:email' => 'Email address',
	'profile:field:location' => 'Location',
	'profile:field:date' => 'Date',

	'profile:edit:default' => 'Edit profile fields',
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
