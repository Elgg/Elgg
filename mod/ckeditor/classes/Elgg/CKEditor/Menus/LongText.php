<?php

namespace Elgg\CKEditor\Menus;

/**
 * Hook callbacks for menus
 *
 * @since 4.0
 * @internal
 */
class LongText {

	/**
	 * Register item to menu
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:longtext'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function registerToggler(\Elgg\Hook $hook) {
		$id = $hook->getParam('textarea_id');
		if ($id === null) {
			return;
		}
		$items = $hook->getValue();
		
		$items[] = \ElggMenuItem::factory([
			'name' => 'ckeditor_toggler',
			'link_class' => 'ckeditor-toggle-editor elgg-longtext-control hidden',
			'href' => "#{$id}",
			'text' => elgg_echo('ckeditor:html'),
		]);
	
		return $items;
	}
}
