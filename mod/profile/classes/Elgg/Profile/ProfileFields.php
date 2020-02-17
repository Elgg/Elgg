<?php

namespace Elgg\Profile;

/**
 * Hook callbacks for profile fields
 *
 * @since 4.0
 * @internal
 */
class ProfileFields {

	/**
	 * This function loads a set of default fields into the profile, then triggers a hook letting other plugins to edit
	 * add and delete fields.
	 *
	 * Note: This is a secondary system:init call and is run at a super low priority to guarantee that it is called after all
	 * other plugins have initialised.
	 *
	 * @return void
	 */
	public static function setup() {
		$profile_defaults =  [
			'description' => 'longtext',
			'briefdescription' => 'text',
			'location' => 'location',
			'interests' => 'tags',
			'skills' => 'tags',
			'contactemail' => 'email',
			'phone' => 'tel',
			'mobile' => 'tel',
			'website' => 'url',
			'twitter' => 'text',
		];
	
		$loaded_defaults = [];
		$fieldlist = elgg_get_config('profile_custom_fields');
		if ($fieldlist || $fieldlist === '0') {
			$fieldlistarray = explode(',', $fieldlist);
			foreach ($fieldlistarray as $listitem) {
				if ($translation = elgg_get_config("admin_defined_profile_{$listitem}")) {
					$type = elgg_get_config("admin_defined_profile_type_{$listitem}");
					$loaded_defaults["admin_defined_profile_{$listitem}"] = $type;
					add_translation(get_current_language(), ["profile:admin_defined_profile_{$listitem}" => $translation]);
				}
			}
		}
	
		if (count($loaded_defaults)) {
			elgg_set_config('profile_using_custom', true);
			$profile_defaults = $loaded_defaults;
		}
		
		$profile_fields = elgg_trigger_plugin_hook('profile:fields', 'profile', null, $profile_defaults);
		elgg_set_config('profile_fields', $profile_fields);
	}
}
