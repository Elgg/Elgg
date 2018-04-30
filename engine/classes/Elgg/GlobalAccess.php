<?php

namespace Elgg;

use ElggAccessCollection;

/**
 * Magic access collection wrapper for ACCESS_PUBLIC and ACCESS_LOGGED_IN
 */
class GlobalAccess extends ElggAccessCollection {

	/**
	 * {@inheritdoc}
	 */
	public function save() {
		throw new \LogicException('Instances of ' . __CLASS__ . ' can not be saved');
	}

}