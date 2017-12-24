<?php

namespace Elgg\SystemLog;

use Elgg\Cache\CompositeCache;

class LogEventCache extends CompositeCache {

	public function __construct() {
		$flags = ELGG_CACHE_PERSISTENT | ELGG_CACHE_FILESYSTEM | ELGG_CACHE_RUNTIME;

		// not available in elgg()->dic. Maybe we should...
		$config = _elgg_config();

		parent::__construct('system_log', $config, $flags);
	}

	public function bindToShutdown() {
		register_shutdown_function(function () {
			$this->clear();
		});
	}
}
