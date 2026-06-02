<?php

namespace Elgg\Helpers\Controllers;

use Elgg\Controllers\GenericEntity;

/**
 * For testing the GenericEntity controller
 *
 * @internal
 */
class GenericEntityTesting extends GenericEntity {
	
	/**
	 * Create controller
	 *
	 * @param \Elgg\Request $request Elgg request
	 */
	public function __construct(\Elgg\Request $request) {
		$this->request = $request;
		$this->route = $request->getHttpRequest()?->getRoute();
	}
}
