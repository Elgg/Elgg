<?php

namespace Elgg\Profile;

/**
 * Hook callbacks for widgets
 *
 * @since 4.0
 * @internal
 */
class Widgets {

	/**
	 * Register profile widgets with default widgets
	 *
	 * @param \Elgg\Hook $hook 'get_list', 'default_widgets'
	 *
	 * @return array
	 */
	public static function getDefaultWidgetsList(\Elgg\Hook $hook) {
		$return = $hook->getValue();
		$return[] = [
			'name' => elgg_echo('profile'),
			'widget_context' => 'profile',
			'widget_columns' => 2,
	
			'event_name' => 'create',
			'event_type' => 'user',
			'entity_type' => 'user',
			'entity_subtype' => ELGG_ENTITIES_ANY_VALUE,
		];
	
		return $return;
	}
}
