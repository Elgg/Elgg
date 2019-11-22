<?php
/**
 * Lists all function deprecated in Elgg 3.3
 */

/**
 * Deletes all cached views in the simplecache
 *
 * @return bool
 * @since 1.7.4
 * @deprecated 3.3
 */
function elgg_invalidate_simplecache() {
	elgg_deprecated_notice(__METHOD__ . ' is deprecated. Use elgg_clear_caches()', '3.3');
	
	_elgg_services()->simpleCache->clear();
}

/**
 * Flush all the registered caches
 *
 * @return void
 * @since 1.11
 * @deprecated 3.3 use elgg_clear_caches()
 */
function elgg_flush_caches() {
	// this event sequence could take while, make sure there is no timeout
	set_time_limit(0);
	
	elgg_invalidate_caches();
	elgg_clear_caches();
	
	_elgg_services()->events->triggerDeprecatedSequence(
		'cache:flush',
		'system',
		null,
		null,
		"The 'cache:flush' sequence has been deprecated, please use 'cache:clear'.",
		'3.3'
	);
}
