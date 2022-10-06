<?php

namespace Elgg\Search;

/**
 * Event callbacks for site
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
	 * @param \Elgg\Event $event 'robots.txt' 'site'
	 * @return string
	 */
	public static function preventSearchIndexing(\Elgg\Event $event) {
		$rules = [
			'',
			'User-agent: *',
			'Disallow: /search/',
			''
		];
	
		$text = $event->getValue();
		$text .= implode("\r\n", $rules);
		return $text;
	}
}
