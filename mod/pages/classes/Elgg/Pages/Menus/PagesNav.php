<?php

namespace Elgg\Pages\Menus;

/**
 * Hook callbacks for menus
 *
 * @since 4.0
 * @internal
 */
class PagesNav {

	/**
	 * Register menu items for pages_nav menu
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:pages_nav'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function register(\Elgg\Hook $hook) {
		$entity = $hook->getEntityParam();
		if (!$entity instanceof \ElggPage) {
			return;
		}
		
		$return = $hook->getValue();
		
		$top = $entity->getTopParentEntity();
				
		$next_level_guids = [$top->guid];
		while (!empty($next_level_guids)) {
			$children = elgg_get_entities([
				'type' => 'object',
				'subtype' => 'page',
				'metadata_name_value_pairs' => [
					'name' => 'parent_guid',
					'value' => $next_level_guids,
					'operand' => 'IN',
				],
				'batch' => true,
			]);
			
			$next_level_guids = [];
			foreach ($children as $child) {
				$return[] = \ElggMenuItem::factory([
					'name' => $child->guid,
					'text' => $child->getDisplayName(),
					'href' => $child->getURL(),
					'parent_name' => $child->getParentGUID(),
				]);
				
				$next_level_guids[] = $child->guid;
			}
		}
		
		if (count($return) < 1) {
			return;
		}
		
		$return[] = \ElggMenuItem::factory([
			'name' => $top->guid,
			'text' => $top->getDisplayName(),
			'href' => $top->getURL(),
			'parent_name' => $top->getParentGUID(),
		]);
		
		return $return;
	}
}
