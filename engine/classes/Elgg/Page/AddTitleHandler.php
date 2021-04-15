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
			$head_params['title'] = _elgg_services()->config->sitename;
		} else {
			$head_params['title'] = $title . ' : ' . _elgg_services()->config->sitename;
		}
	
		return $head_params;
	}
}
