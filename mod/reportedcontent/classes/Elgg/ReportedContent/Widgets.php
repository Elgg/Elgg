<?php

namespace Elgg\ReportedContent;

/**
 * Widget related functions
 */
class Widgets {
	
	/**
	 * Set the URL for the reportedcontent widget
	 *
	 * @param \Elgg\Event $event 'entity:url', 'object:widget'
	 *
	 * @return null|string
	 */
	public static function reportedcontentWidgetURL(\Elgg\Event $event): ?string {
		if (!empty($event->getValue())) {
			// someone already set an url
			return null;
		}
		
		$widget = $event->getEntityParam();
		if (!$widget instanceof \ElggWidget || $widget->handler !== 'reportedcontent') {
			return null;
		}
		
		return elgg_generate_url('admin', [
			'segments' => 'administer_utilities/reportedcontent',
		]);
	}
}
