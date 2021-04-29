<?php

namespace Elgg\SystemLog;

use Elgg\Cache\CompositeCache;
use Elgg\Traits\Di\ServiceFacade;

/**
 * System log cache
 */
class LogEventCache extends CompositeCache {

	use ServiceFacade;

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct('system_log', elgg()->config, ELGG_CACHE_RUNTIME);
	}

	/**
	 * Returns registered service name
	 * @return string
	 */
	public static function name() {
		return 'system_log.cache';
	}
}
