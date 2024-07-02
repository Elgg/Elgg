<?php

namespace Elgg\Menus;

use Elgg\Menu\PreparedMenu;

/**
 * Prepares breadcrumbs
 *
 * @since 5.0
 */
class Breadcrumbs {
	
	/**
	 * Prepare breadcrumbs before display. This turns titles into 100-character excerpts, and also
	 * removes the last crumb if it's not a link.
	 *
	 * @param \Elgg\Event $event 'prepare', 'menu:breadcrumbs'
	 *
	 * @return void
	 */
	public static function cleanupBreadcrumbs(\Elgg\Event $event): void {
		/* @var $breadcrumbs PreparedMenu */
		$breadcrumbs = $event->getValue();
		
		$items = $breadcrumbs->getItems('default');
		if (empty($items)) {
			return;
		}
		
		$last = null;
		foreach ($items as $crumb) {
			$last = $crumb;
			$crumb->setText(elgg_get_excerpt((string) $crumb->getText(), 100));
		}
		
		// remove last crumb if it has no link
		if (empty($last->getHref())) {
			elgg_log("Having a breadcrumb at the end of the list without a link makes no sense. Please update your code for the '{$last->getText()}[{$last->getID()}]' breadcrumb.", \Psr\Log\LogLevel::NOTICE);
			$breadcrumbs->getSection('default')->remove($last->getID());
		} elseif (!$last->getChildren() && elgg_http_url_is_identical(elgg_get_current_url(), $last->getHref())) {
			elgg_log("Having a breadcrumb at the end of the list which links to the current page makes no sense. Please update your code for the '{$last->getText()}[{$last->getID()}]' breadcrumb.", \Psr\Log\LogLevel::NOTICE);
			$breadcrumbs->getSection('default')->remove($last->getID());
		}
	}
	
	/**
	 * Adds a home item
	 *
	 * @param \Elgg\Event $event 'prepare', 'menu:breadcrumbs'
	 *
	 * @return null|PreparedMenu
	 */
	public static function addHomeItem(\Elgg\Event $event): ?PreparedMenu {
		/* @var $return PreparedMenu */
		$return = $event->getValue();
		
		/* @var $items \ElggMenuItem[] */
		$items = $return->getItems('default');
		if (empty($items)) {
			return null;
		}
		
		$href = elgg_get_site_url();
		if (elgg_in_context('admin')) {
			$href = elgg_generate_url('admin');
		}
		
		array_unshift($items, \ElggMenuItem::factory([
			'name' => 'home',
			'icon' => 'home',
			'text' => false,
			'title' => elgg_get_site_entity()->getDisplayName(),
			'href' => $href,
		]));
		
		$return->getSection('default')->fill($items);
		
		return $return;
	}
}
