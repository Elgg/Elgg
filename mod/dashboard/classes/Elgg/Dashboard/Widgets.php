<?php

namespace Elgg\Dashboard;

/**
 * Widget related functions
 *
 * @since 4.0
 * @internal
 */
class Widgets {

	/**
	 * Register user dashboard with default widgets
	 *
	 * @param \Elgg\Hook $hook 'get_list', 'default_widgets'
	 *
	 * @return array
	 */
	public static function extendDefaultWidgetsList(\Elgg\Hook $hook) {
		$return = $hook->getValue();
		$return[] = [
			'name' => elgg_echo('dashboard'),
			'widget_context' => 'dashboard',
			'widget_columns' => 3,
	
			'event_name' => 'login:first',
			'event_type' => 'user',
			'entity_type' => 'user',
			'entity_subtype' => ELGG_ENTITIES_ANY_VALUE,
		];
	
		return $return;
	}
}
