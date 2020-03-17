<?php

namespace Elgg\Page;

/**
 * Prepares breadcrumbs
 *
 * @since 4.0
 */
class PrepareBreadcrumbsHandler {
	
	/**
	 * Prepare breadcrumbs before display. This turns titles into 100-character excerpts, and also
	 * removes the last crumb if it's not a link.
	 *
	 * @param \Elgg\Hook $hook 'prepare', 'breadcrumbs'
	 *
	 * @return array
	 */
	public function __invoke(\Elgg\Hook $hook) {
		$breadcrumbs = $hook->getValue();
	
		// remove last crumb if not a link
		$last_crumb = end($breadcrumbs);
		if (empty($last_crumb['href'])) {
			array_pop($breadcrumbs);
		}
	
		// apply excerpt to text
		foreach (array_keys($breadcrumbs) as $i) {
			$breadcrumbs[$i]['text'] = elgg_get_excerpt($breadcrumbs[$i]['text'], 100);
		}
		return $breadcrumbs;
	}
}
