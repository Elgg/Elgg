<?php

namespace Elgg\Helpers;

/**
 * @see \Elgg\Integration\ElggCoreUserTest
 */
class ElggUserWithExposableAttributes extends \ElggUser {
	public function expose_attributes() {
		return $this->attributes;
	}
}
