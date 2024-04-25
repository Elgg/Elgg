<?php

namespace Elgg\TheWire;

/**
 * Widget related functions
 */
class Widgets {
	
	/**
	 * Set the URL for the thewire widget
	 *
	 * @param \Elgg\Event $event 'entity:url', 'object:widget'
	 *
	 * @return null|string
	 */
	public static function thewireWidgetURL(\Elgg\Event $event): ?string {
		if (!empty($event->getValue())) {
			// someone already set an url
			return null;
		}
		
		$widget = $event->getEntityParam();
		if (!$widget instanceof \ElggWidget || $widget->handler !== 'thewire') {
			return null;
		}
		
		$owner = $widget->getOwnerEntity();
		if ($owner instanceof \ElggGroup) {
			// not yet supported
			return null;
		} elseif ($owner instanceof \ElggUser) {
			return elgg_generate_url('collection:object:thewire:owner', [
				'username' => $owner->username,
			]);
		}
		
		return elgg_generate_url('collection:object:thewire:all');
	}
}
