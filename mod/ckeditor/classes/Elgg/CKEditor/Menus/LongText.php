<?php

namespace Elgg\CKEditor\Menus;

/**
 * Event callbacks for menus
 *
 * @since 4.0
 * @internal
 */
class LongText {

	/**
	 * Register item to menu
	 *
	 * @param \Elgg\Event $event 'register', 'menu:longtext'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function registerToggler(\Elgg\Event $event) {
		$id = $event->getParam('textarea_id');
		if ($id === null) {
			return;
		}
		
		$items = $event->getValue();
		
		$items[] = \ElggMenuItem::factory([
			'name' => 'ckeditor_toggler',
			'link_class' => 'ckeditor-toggle-editor elgg-longtext-control hidden',
			'href' => "#{$id}",
			'text' => elgg_echo('ckeditor:html'),
		]);
	
		return $items;
	}
}
