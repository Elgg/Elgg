<?php

namespace Elgg\Profile;

/**
 * Event callback for fields
 *
 * @since 4.0
 */
class FieldsHandler {

	/**
	 * Returns fields config for users
	 *
	 * @param \Elgg\Event $event 'fields' 'user:user'
	 *
	 * @return array
	 */
	public function __invoke(\Elgg\Event $event) {
		$return = (array) $event->getValue();

		$fields = $this->getCustomFields() ?: $this->getDefaultFields();
		
		return array_merge($return, $fields);
	}
	
	/**
	 * Returns an array of admin defined fields
	 *
	 * @return array
	 */
	protected function getCustomFields(): array {
		$custom_fields = elgg_get_config('profile_custom_fields');
		if (elgg_is_empty($custom_fields)) {
			return [];
		}
		
		$result = [];
		$custom_fields = explode(',', $custom_fields);
		$translations = [];
		
		foreach ($custom_fields as $field_id) {
			$label = elgg_get_config("admin_defined_profile_{$field_id}");
			if (empty($label)) {
				continue;
			}
			
			$result[] = [
				'#type' => elgg_get_config("admin_defined_profile_type_{$field_id}"),
				'#label' => $label,
				'name' => "admin_defined_profile_{$field_id}",
			];
			
			$translations["profile:admin_defined_profile_{$field_id}"] = $label;
		}
		
		if (!empty($translations)) {
			elgg()->translator->addTranslation(elgg_get_current_language(), $translations);
		}
			
		return $result;
	}
	
	/**
	 * Returns an array of default fields
	 *
	 * @return array
	 */
	protected function getDefaultFields(): array {
		return [
			[
				'#type' => 'text',
				'#label' => elgg_echo('profile:briefdescription'),
				'name' => 'briefdescription',
			],
			[
				'#type' => 'location',
				'#label' => elgg_echo('profile:location'),
				'name' => 'location',
			],
			[
				'#type' => 'tags',
				'#label' => elgg_echo('profile:interests'),
				'name' => 'interests',
			],
			[
				'#type' => 'tags',
				'#label' => elgg_echo('profile:skills'),
				'name' => 'skills',
			],
			[
				'#type' => 'email',
				'#label' => elgg_echo('profile:contactemail'),
				'name' => 'contactemail',
			],
			[
				'#type' => 'tel',
				'#label' => elgg_echo('profile:phone'),
				'name' => 'phone',
			],
			[
				'#type' => 'tel',
				'#label' => elgg_echo('profile:mobile'),
				'name' => 'mobile',
			],
			[
				'#type' => 'url',
				'#label' => elgg_echo('profile:website'),
				'name' => 'website',
			],
			[
				'#type' => 'longtext',
				'#label' => elgg_echo('profile:description'),
				'name' => 'description',
			],
		];
	}
}
