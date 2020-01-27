<?php

namespace Elgg\WebServices;

use Elgg\Menu\MenuItems;

/**
 * Make changes to the entity menu
 *
 * @since 3.2
 */
class EntityMenu {
	
	/**
	 * Make changes to the entity menu
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:entity'
	 *
	 * @return void|MenuItems
	 */
	public function __invoke(\Elgg\Hook $hook) {
		
		$entity = $hook->getEntityParam();
		if (!$entity instanceof \ElggApiKey) {
			return;
		}
		
		/* @var $return MenuItems */
		$return = $hook->getValue();
		
		$edit = $return->get('edit');
		if ($edit instanceof \ElggMenuItem) {
			$edit->addLinkClass('elgg-lightbox');
			$return->add($edit);
		}
		
		if ($entity->canEdit()) {
			$return[] = \ElggMenuItem::factory([
				'name' => 'regenerate',
				'icon' => 'refresh',
				'text' => elgg_echo('webservices:menu:entity:regenerate'),
				'href' => elgg_generate_action_url('webservices/api_key/regenerate', [
					'guid' => $entity->guid,
				]),
				'confirm' => true,
			]);
		}
		
		return $return;
	}
}
