<?php

namespace Elgg\ReportedContent\Menus;

/**
 * Hook callbacks for menus
 *
 * @since 4.0
 * @internal
 */
class Footer {
	
	/**
	 * Add report this to footer
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:footer'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function register(\Elgg\Hook $hook) {
		
		if (!elgg_is_logged_in()) {
			return;
		}
				
		$return = $hook->getValue();
		$return[] = \ElggMenuItem::factory([
			'name' => 'report_this',
			'href' => 'ajax/form/reportedcontent/add',
			'title' => elgg_echo('reportedcontent:this:tooltip'),
			'text' => elgg_echo('reportedcontent:this'),
			'icon' => 'exclamation-triangle',
			'priority' => 500,
			'section' => 'default',
			'link_class' => 'elgg-lightbox',
			'deps' => 'elgg/reportedcontent',
		]);
	
		return $return;
	}
}
