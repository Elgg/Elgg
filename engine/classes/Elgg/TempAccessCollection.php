<?php

namespace Elgg;

use ElggAccessCollection;
use stdClass;

/**
 * Magic access collection wrapper for ACCESS_PUBLIC, ACCESS_LOGGEDIN and ACCESS_PRIVATE
 */
class TempAccessCollection extends ElggAccessCollection {

	/**
	 * {@inheritdoc}
	 */
	public function save() {
		throw new \LogicException('Instances of ' . __CLASS__ . ' can not be saved');
	}

}