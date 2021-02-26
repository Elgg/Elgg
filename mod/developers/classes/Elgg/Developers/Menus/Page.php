<?php

namespace Elgg\Developers\Menus;

/**
 * Hook callbacks for menus
 *
 * @since 4.0
 *
 * @internal
 */
class Page {

	/**
	 * Register item to menu
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:page'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function register(\Elgg\Hook $hook) {
		if (!elgg_in_context('admin') || !elgg_is_admin_logged_in()) {
			return;
		}
		
		$return = $hook->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'dev_settings',
			'href' => 'admin/developers/settings',
			'text' => elgg_echo('settings'),
			'priority' => 10,
			'section' => 'develop',
		]);
	
		$return[] = \ElggMenuItem::factory([
			'name' => 'error_log',
			'href' => 'admin/develop_tools/error_log',
			'text' => elgg_echo('admin:develop_tools:error_log'),
			'section' => 'develop',
		]);
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'develop_tools:entity_explorer',
			'href' => 'admin/develop_tools/entity_explorer',
			'text' => elgg_echo('admin:develop_tools:entity_explorer'),
			'section' => 'develop',
		]);
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'develop_tools:sandbox',
			'href' => 'theme_sandbox/intro',
			'text' => elgg_echo('admin:develop_tools:sandbox'),
			'section' => 'develop',
			'target' => '_blank',
		]);
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'inspect',
			'text' => elgg_echo('admin:inspect'),
			'section' => 'develop',
		]);
		
		$inspect_options = self::getInspectOptions();
		foreach ($inspect_options as $key => $value) {
			$return[] = \ElggMenuItem::factory([
				'name' => 'dev_inspect_' . elgg_get_friendly_title($key),
				'href' => "admin/develop_tools/inspect?" . http_build_query([
					'inspect_type' => $key,
				]),
				'text' => $value,
				'section' => 'develop',
				'parent_name' => 'inspect',
			]);
		}
		
		return $return;
	}
	
	/**
	 * Get the available inspect options
	 *
	 * @return array
	 */
	protected static function getInspectOptions() {
		$options = [
			'Actions' => elgg_echo('developers:inspect:actions'),
			'Events' => elgg_echo('developers:inspect:events'),
			'Menus' => elgg_echo('developers:inspect:menus'),
			'Plugin Hooks' => elgg_echo('developers:inspect:pluginhooks'),
			'Routes' => elgg_echo('developers:inspect:routes'),
			'Services' => elgg_echo('developers:inspect:services'),
			'Simple Cache' => elgg_echo('developers:inspect:simplecache'),
			'Views' => elgg_echo('developers:inspect:views'),
			'Widgets' => elgg_echo('developers:inspect:widgets'),
		];
		
		ksort($options);
		
		return $options;
	}
}
