<?php

namespace Elgg\Javascript;

/**
 * Adds SRI information to js elgg.data object.
 * Used by RequireJS AMD config.
 *
 * @since 4.3
 */
class AddSRIConfig {
	
	/**
	 * Add SRI information from cached data.
	 * Need to do this on every page as the cache is extended on every first load of a simplecache resource.
	 *
	 * @param \Elgg\Hook $hook 'elgg.data', 'page'
	 *
	 * @return array
	 */
	public function __invoke(\Elgg\Hook $hook) {
		if (!elgg_get_config('subresource_integrity_enabled')) {
			return;
		}
		
		$return = $hook->getValue();
		
		$data = _elgg_services()->serverCache->load('sri') ?? [];
		
		$return['sri'] = $data['js'] ?? [];
		
		return $return;
	}
}
