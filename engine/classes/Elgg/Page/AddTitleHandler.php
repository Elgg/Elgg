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
	 * @param \Elgg\Hook $hook 'head', 'page'
	 *
	 * @return array
	 */
	public function __invoke(\Elgg\Hook $hook) {
		$head_params = $hook->getValue();
	
		$title = $hook->getParam('title');
		if (empty($title)) {
			$head_params['title'] = elgg_get_site_entity()->getDisplayName();
		} else {
			$head_params['title'] = $title . ' : ' . elgg_get_site_entity()->getDisplayName();
		}
	
		return $head_params;
	}
}
