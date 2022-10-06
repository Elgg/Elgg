<?php

namespace Elgg\Page;

/**
 * Add title to page head
 *
 * @since 4.0
 */
class AddTitleHandler {
	
	/**
	 * Add title to HTML head
	 *
	 * @param \Elgg\Event $event 'head', 'page'
	 *
	 * @return array
	 */
	public function __invoke(\Elgg\Event $event) {
		$head_params = $event->getValue();
	
		$title = $event->getParam('title');
		if (empty($title)) {
			$head_params['title'] = elgg_get_site_entity()->getDisplayName();
		} else {
			$head_params['title'] = $title . ' : ' . elgg_get_site_entity()->getDisplayName();
		}
	
		return $head_params;
	}
}
