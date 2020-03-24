<?php

namespace Elgg\Menus;

use Elgg\Menu\MenuItems;

/**
 * Register menu items to the social menu
 *
 * @since 4.0
 * @internal
 */
class Social {
	
	/**
	 * Adds comment menu items to social menu
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:social'
	 *
	 * @return void|MenuItems
	 */
	public static function registerComments(\Elgg\Hook $hook) {
		$entity = $hook->getEntityParam();
		if (!$entity instanceof \ElggEntity) {
			return;
		}
		
		/* @var $return MenuItems */
		$return = $hook->getValue();
		
		$comment_count = $entity->countComments();
		$can_comment = $entity->canComment();
		if ($can_comment || $comment_count) {
			$text = $can_comment ? elgg_echo('comment:this') : elgg_echo('comments');
			
			$options = [
				'name' => 'comment',
				'icon' => 'comment',
				'badge' => $comment_count ?: null,
				'text' => $text,
				'title' => $text,
				'href' => $entity->getURL() . '#comments',
			];
			
			$item = $hook->getParam('item');
			if ($item instanceof \ElggRiverItem && $can_comment) {
				$options['href'] = "#comments-add-{$entity->guid}-{$item->id}";
				$options['rel'] = 'toggle';
			}
			
			$return[] = \ElggMenuItem::factory($options);
		}
		
		return $return;
	}
}
