<?php

namespace Elgg\File\Menus;

/**
 * Hook callbacks for menus
 *
 * @since 4.0
 *
 * @internal
 */
class Embed {

	/**
	 * Register item to menu
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:embed'
	 *
	 * @return \Elgg\Menu\MenuItems
	 */
	public static function registerFile(\Elgg\Hook $hook) {
		$return = $hook->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'file',
			'text' => elgg_echo('collection:object:file'),
			'priority' => 10,
			'data' => [
				'options' => [
					'type' => 'object',
					'subtype' => 'file',
				],
			],
		]);
		
		return $return;
	}

	/**
	 * Register item to menu
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:embed'
	 *
	 * @return \Elgg\Menu\MenuItems
	 */
	public static function registerFileUpload(\Elgg\Hook $hook) {
		$return = $hook->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'file_upload',
			'text' => elgg_echo('add:object:file'),
			'priority' => 100,
			'data' => [
				'view' => 'embed/file_upload/content',
			],
		]);
		
		return $return;
	}
}
