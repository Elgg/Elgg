<?php

namespace Elgg\Friends\Collections;

class UrlHandler {
	
	/**
	 * Rewrite access collection URL
	 * @elgg_plugin_hook access_collection:url access_collection
	 *
	 * @param \Elgg\Hook $hook Hook
	 * @return string
	 */
	public function __invoke(\Elgg\Hook $hook) {

		$collection = $hook->getParam('access_collection');
		if (!$collection instanceof \ElggAccessCollection) {
			return;
		}

		return elgg_normalize_url("collections/view/$collection->id");
	}
}
