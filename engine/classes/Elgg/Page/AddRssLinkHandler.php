<?php

namespace Elgg\Page;

/**
 * Add RSS link to page head
 *
 * @since 4.0
 */
class AddRssLinkHandler {
	
	/**
	 * Add rss link to HTML head
	 *
	 * @param \Elgg\Hook $hook 'head', 'page'
	 *
	 * @return array|void
	 */
	public function __invoke(\Elgg\Hook $hook) {
		if (!_elgg_has_rss_link()) {
			return;
		}
		
		$head_params = $hook->getValue();

		$head_params['links']['rss'] = [
			'rel' => 'alternative',
			'type' => 'application/rss+xml',
			'title' => 'RSS',
			'href' => elgg_http_add_url_query_elements(current_page_url(), [
				'view' => 'rss',
			]),
		];
	
		return $head_params;
	}
}
