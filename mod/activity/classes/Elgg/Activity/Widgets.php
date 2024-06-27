<?php

namespace Elgg\Activity;

/**
 * Widget related functions
 */
class Widgets {

	/**
	 * Set the title URL for the activity widget
	 *
	 * @param \Elgg\Event $event 'entity:url', 'object:widget'
	 *
	 * @return null|string
	 */
	public static function riverWidgetURL(\Elgg\Event $event): ?string {
		if (!empty($event->getValue())) {
			// someone already set an url
			return null;
		}
		
		$widget = $event->getEntityParam();
		if (!$widget instanceof \ElggWidget || $widget->handler !== 'river_widget') {
			return null;
		}
		
		$owner = $widget->getOwnerEntity();
		if ($owner instanceof \ElggGroup) {
			return elgg_generate_url('collection:river:group', [
				'guid' => $owner->guid,
			]);
		} elseif ($owner instanceof \ElggUser) {
			if ($widget->context === 'dashboard') {
				if ($widget->content_type === 'all') {
					return elgg_generate_url('collection:river:all');
				}
				
				return elgg_generate_url('collection:river:friends', [
					'username' => $owner->username,
				]);
			}
			
			return elgg_generate_url('collection:river:owner', [
				'username' => $owner->username,
			]);
		}
		
		return elgg_generate_url('collection:river:all');
	}
	
	/**
	 * Set the title URL for the group activity widget
	 *
	 * @param \Elgg\Event $event 'entity:url', 'object:widget'
	 *
	 * @return null|string
	 */
	public static function groupWidgetURL(\Elgg\Event $event): ?string {
		if (!empty($event->getValue())) {
			// someone already set an url
			return null;
		}
		
		$widget = $event->getEntityParam();
		if (!$widget instanceof \ElggWidget || $widget->handler !== 'group_activity') {
			return null;
		}
		
		$group_guid = (int) $widget->group_guid;
		$group = get_entity($group_guid);
		if (!$group instanceof \ElggGroup) {
			return null;
		}
		
		return elgg_generate_url('collection:river:group', [
			'guid' => $group->guid,
		]);
	}
}
