<?php

namespace Elgg\Embed\Menus;

/**
 * Hook callbacks for menus
 *
 * @since 4.0
 *
 * @internal
 */
class Embed {

	/**
	 * Select the correct embed tab for display
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:embed'
	 *
	 * @return \Elgg\Menu\MenuItems
	 */
	public static function selectCorrectTab(\Elgg\Hook $hook) {
		$tab_name = $hook->getParam('tab');
		
		$items = $hook->getValue();
		foreach ($items as $item) {
			if ($item->getName() == $tab_name) {
				$item->setSelected();
				elgg_set_config('embed_tab', $item);
			}
		}
	
		if (!elgg_get_config('embed_tab') && count($items) > 0) {
			$keys = array_keys($items->all());
	
			$first_tab = $items->get($keys[0]);
			$first_tab->setSelected();
			elgg_set_config('embed_tab', $first_tab);
		}
		
		return $items;
	}
}
