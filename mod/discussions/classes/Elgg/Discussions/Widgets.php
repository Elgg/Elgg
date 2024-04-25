<?php

namespace Elgg\Discussions;

/**
 * Widget related functions
 */
class Widgets {

	/**
	 * Set the title URL for the discussions widget
	 *
	 * @param \Elgg\Event $event 'entity:url', 'object:widget'
	 *
	 * @return null|string
	 */
	public static function widgetURL(\Elgg\Event $event): ?string {
		if (!empty($event->getValue())) {
			// someone already set an url
			return null;
		}
		
		$widget = $event->getEntityParam();
		if (!$widget instanceof \ElggWidget || $widget->handler !== 'discussions') {
			return null;
		}
		
		$owner = $widget->getOwnerEntity();
		if ($owner instanceof \ElggUser) {
			if ($widget->context === 'dashboard') {
				return elgg_generate_url('collection:object:discussion:my_groups', ['username' => $owner->username]);
			}
			
			return elgg_generate_url('collection:object:discussion:owner', ['username' => $owner->username]);
		} elseif ($owner instanceof \ElggGroup) {
			return elgg_generate_url('collection:object:discussion:group', ['guid' => $owner->guid]);
		}
		
		return elgg_generate_url('collection:object:discussion:all');
	}
}
