<?php

namespace Elgg\Friends\Collections;

/**
 * Access collection URL handler
 */
class UrlHandler {
	
	/**
	 * Rewrite access collection URL
	 *
	 * @param \Elgg\Hook $hook 'access_collection:url' 'access_collection'
	 *
	 * @return void|string
	 */
	public function __invoke(\Elgg\Hook $hook) {

		$collection = $hook->getParam('access_collection');
		if (!$collection instanceof \ElggAccessCollection) {
			return;
		}

		return elgg_generate_url('view:access_collection:friends', [
			'collection_id' => $collection->id,
		]);
	}
}
