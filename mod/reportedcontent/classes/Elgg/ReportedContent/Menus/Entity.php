<?php

namespace Elgg\ReportedContent\Menus;

/**
 * Hook callbacks for menus
 *
 * @since 4.0
 * @internal
 */
class Entity {
	
	/**
	 * Add items to entity menu
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:entity'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function register(\Elgg\Hook $hook) {
		
		$entity = $hook->getEntityParam();
		if (!$entity instanceof \ElggReportedContent) {
			return;
		}
		
		if (!elgg_is_admin_logged_in()) {
			return;
		}
		
		if ($entity->state === 'archived') {
			return;
		}
				
		$return = $hook->getValue();
		$return[] = \ElggMenuItem::factory([
			'name' => 'archive',
			'icon' => 'archive',
			'text' => elgg_echo('reportedcontent:archive'),
			'href' => elgg_generate_action_url('reportedcontent/archive', [
				'guid' => $entity->guid,
			]),
			'section' => 'actions',
			'data-colorbox-opts' => json_encode([
				'width' => '85%',
				'height' => '85%',
				'iframe' => true,
			]),
		]);
	
		return $return;
	}
}
