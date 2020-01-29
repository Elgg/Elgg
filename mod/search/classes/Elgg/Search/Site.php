<?php

namespace Elgg\Search;

/**
 * Hook callbacks for site
 *
 * @since 4.0
 * @internal
 */
class Site {

	/**
	 * Exclude robots from indexing search pages
	 *
	 * This is good for performance since search is slow and there are many pages all with the same content.
	 *
	 * @param \Elgg\Hook $hook 'robots.txt' 'site'
	 * @return string
	 */
	public static function preventSearchIndexing(\Elgg\Hook $hook) {
		$rules = [
			'',
			'User-agent: *',
			'Disallow: /search/',
			''
		];
	
		$text = $hook->getValue();
		$text .= implode("\r\n", $rules);
		return $text;
	}
}
