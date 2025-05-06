<?php

namespace Elgg\Pages\Menus;

/**
 * Event callbacks for menus
 *
 * @since 4.0
 * @internal
 */
class PagesNav {

	/**
	 * Register menu items for pages_nav menu
	 *
	 * @param \Elgg\Event $event 'register', 'menu:pages_nav'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function register(\Elgg\Event $event) {
		$entity = $event->getEntityParam();
		if (!$entity instanceof \ElggPage) {
			return;
		}
		
		$return = $event->getValue();
		
		$top = $entity->getTopParentEntity();
				
		$next_level_guids = [$top->guid];
		while (!empty($next_level_guids)) {
			/* @var $children \ElggBatch */
			$children = elgg_get_entities([
				'type' => 'object',
				'subtype' => 'page',
				'metadata_name_value_pairs' => [
					'name' => 'parent_guid',
					'value' => $next_level_guids,
					'operand' => 'IN',
				],
				'batch' => true,
				'limit' => false,
			]);
			
			$next_level_guids = [];
			/* @var $child \ElggPage */
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
