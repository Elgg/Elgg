<?php

namespace Elgg\Groups\Forms;

/**
 * Prepare the fields for the groups/edit form
 *
 * @since 5.0
 */
class PrepareFields {
	
	/**
	 * Prepare fields
	 *
	 * @param \Elgg\Event $event 'form:prepare:fields', 'groups/edit'
	 *
	 * @return array
	 */
	public function __invoke(\Elgg\Event $event): array {
		$vars = $event->getValue();
		
		// input names => defaults
		$values = [
			'name' => '',
			'membership' => ACCESS_PUBLIC,
			'vis' => ACCESS_PUBLIC,
			'guid' => null,
			'owner_guid' => elgg_get_logged_in_user_guid(),
			'content_access_mode' => \ElggGroup::CONTENT_ACCESS_MODE_UNRESTRICTED,
			'content_default_access' => '',
		];
		
		// handle customizable profile fields
		$fields = elgg()->fields->get('group', 'group');
		foreach ($fields as $field) {
			$values[$field['name']] = '';
		}
		
		$group = elgg_extract('entity', $vars);
		if ($group instanceof \ElggGroup) {
			// load current file values
			foreach (array_keys($values) as $field) {
				if (isset($group->$field)) {
					$values[$field] = $group->$field;
				}
			}
			
			if ($group->access_id != ACCESS_PUBLIC && $group->access_id != ACCESS_LOGGED_IN) {
				// group only access - this is done to handle access not created when group is created
				$values['vis'] = ACCESS_PRIVATE;
			} else {
				$values['vis'] = $group->access_id;
			}
			
			// The content_access_mode was introduced in 1.9. This method must be
			// used for backwards compatibility with groups created before 1.9.
			$values['content_access_mode'] = $group->getContentAccessMode();
		}
		
		// handle tool options
		if ($group instanceof \ElggGroup) {
			$tools = elgg()->group_tools->group($group);
		} else {
			$tools = elgg()->group_tools->all();
		}
		
		foreach ($tools as $tool) {
			if ($group instanceof \ElggGroup) {
				$enabled = $group->isToolEnabled($tool->name);
			} else {
				$enabled = $tool->isEnabledByDefault();
			}
			
			$prop_name = $tool->mapMetadataName();
			$values[$prop_name] = $enabled ? 'yes' : 'no';
		}
		
		return array_merge($values, $vars);
	}
}
