<?php

namespace Elgg\Helpers;

/**
 * @see \Elgg\Integration\ElggCoreObjectTest
 */
class ElggObjectWithExposableAttributes extends \ElggObject {
	public function expose_attributes() {
		return $this->attributes;
	}
}
