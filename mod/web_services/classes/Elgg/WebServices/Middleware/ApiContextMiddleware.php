<?php

namespace Elgg\WebServices\Middleware;

use Elgg\Request;

/**
 * Middleware to set 'api' context
 *
 * @since 4.0
 */
class ApiContextMiddleware {
	
	/**
	 * Invoke middleware
	 *
	 * @param Request $request the Elgg request
	 *
	 * @return void
	 */
	public function __invoke(Request $request) {
		elgg_set_context('api');
	}
}
