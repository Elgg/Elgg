<?php

namespace Elgg;

use Elgg\Traits\Loggable;

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * Use the elgg_* versions instead.
 *
 * @internal
 *
 * @since 1.11.0
 */
class DeprecationService {

	use Loggable;

	/**
	 * Sends a notice about deprecated use of a function, view, etc.
	 *
	 * @param string $msg         Message to log
	 * @param string $dep_version Human-readable *release* version: 1.7, 1.8, ...
	 *
	 * @return bool
	 */
	public function sendNotice($msg, $dep_version) {
		$this->getLogger()->warning("Deprecated in $dep_version: $msg");

		return true;
	}
}
