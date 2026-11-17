<?php

namespace Elgg\ReportedContent\Menus;

/**
 * Event callbacks for menus
 *
 * @since 4.0
 * @internal
 */
class Footer {
	
	/**
	 * Add report this to footer
	 *
	 * @param \Elgg\Event $event 'register', 'menu:footer'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function register(\Elgg\Event $event) {
		
		if (!elgg_is_logged_in()) {
			return;
		}
				
		$return = $event->getValue();
		$return[] = \ElggMenuItem::factory([
			'name' => 'report_this',
			'icon' => 'exclamation-triangle',
			'text' => elgg_echo('reportedcontent:this'),
			'href' => elgg_generate_url('ajax', [
				'type' => 'form',
				'segments' => 'reportedcontent/add',
			]),
			'title' => elgg_echo('reportedcontent:this:tooltip'),
			'priority' => 500,
			'link_class' => 'elgg-lightbox',
			'deps' => 'elgg/reportedcontent',
		]);
	
		return $return;
	}
}
