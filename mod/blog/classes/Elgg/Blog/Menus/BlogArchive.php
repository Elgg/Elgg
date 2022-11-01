<?php

namespace Elgg\Blog\Menus;

/**
 * Event callbacks for menus
 *
 * @since 4.0
 * @internal
 */
class BlogArchive {

	/**
	 * Register user item to menu
	 *
	 * @param \Elgg\Event $event 'register', 'menu:blog_archive'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function register(\Elgg\Event $event) {
		$page_owner = $event->getParam('entity', elgg_get_page_owner_entity());
		$page = $event->getParam('page', 'all');
		
		// can't use default param logic as the key exists, but the value is mostly NULL
		$show_blog_archive = $event->getParam('show_blog_archive') ?? in_array($page, ['all', 'owner', 'friends', 'group']);
		if (!$show_blog_archive) {
			// only generate archive menu for supported pages
			return;
		}
		
		$options = [
			'type' => 'object',
			'subtype' => 'blog',
		];
		if ($page_owner instanceof \ElggUser) {
			if ($page === 'friends') {
				$options['relationship'] = 'friend';
				$options['relationship_guid'] = (int) $page_owner->guid;
				$options['relationship_join_on'] = 'owner_guid';
			} else {
				$options['owner_guid'] = $page_owner->guid;
			}
		} elseif ($page_owner instanceof \ElggEntity) {
			$options['container_guid'] = $page_owner->guid;
		}
		
		$options = array_merge($options, (array) $event->getParam('blog_archive_options', []));
		
		$dates = elgg_get_entity_dates($options);
		if (!$dates) {
			return;
		}
	
		$blog_archive_url = $event->getParam('blog_archive_url');
		$generate_url = function($lower = null, $upper = null) use ($page_owner, $page, $blog_archive_url) {
			if (!empty($blog_archive_url)) {
				$url_segment = elgg_http_add_url_query_elements($blog_archive_url, [
					'lower' => $lower,
					'upper' => $upper,
				]);
			} else if ($page_owner instanceof \ElggUser) {
				if ($page === 'friends') {
					$url_segment = elgg_generate_url('collection:object:blog:friends', [
						'username' => $page_owner->username,
						'lower' => $lower,
						'upper' => $upper,
					]);
				} else {
					$url_segment = elgg_generate_url('collection:object:blog:owner', [
						'username' => $page_owner->username,
						'lower' => $lower,
						'upper' => $upper,
					]);
				}
			} else if ($page_owner instanceof \ElggGroup) {
				$url_segment = elgg_generate_url('collection:object:blog:group', [
					'guid' => $page_owner->guid,
					'subpage' => 'archive',
					'lower' => $lower,
					'upper' => $upper,
				]);
			} else {
				$url_segment = elgg_generate_url('collection:object:blog:all', [
					'lower' => $lower,
					'upper' => $upper,
				]);
			}
	
			return $url_segment;
		};
		
		$return = $event->getValue();
		
		$years = [];
		$dates = array_reverse($dates);
		
		foreach ($dates as $date) {
			$timestamplow = mktime(0, 0, 0, (int) substr($date, 4, 2), 1, (int) substr($date, 0, 4));
			$timestamphigh = mktime(0, 0, 0, ((int) substr($date, 4, 2)) + 1, 1, (int) substr($date, 0, 4));
	
			$year = substr($date, 0, 4);
			if (!in_array($year, $years)) {
				$return[] = \ElggMenuItem::factory([
					'name' => $year,
					'text' => $year,
					'href' => '#',
					'child_menu' => [
						'display' => 'toggle',
					],
					'priority' => -(int) "{$year}00", // make negative to be sure 2019 is before 2018
				]);
			}
	
			$month = trim(elgg_echo('date:month:' . substr($date, 4, 2), ['']));
	
			$return[] = \ElggMenuItem::factory([
				'name' => $date,
				'text' => $month,
				'href' => $generate_url($timestamplow, $timestamphigh),
				'parent_name' => $year,
				'priority' => -(int) $date, // make negative to be sure March 2019 is before February 2019
			]);
		}
	
		return $return;
	}
}
