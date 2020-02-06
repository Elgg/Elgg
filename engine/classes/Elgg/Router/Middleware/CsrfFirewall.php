<?php

namespace Elgg\Router\Middleware;

use Elgg\Exceptions\Http\CsrfException;
use Elgg\Request;

/**
 * Middleware for validating CSRF tokens
 */
class CsrfFirewall {

	/**
	 * Validate CSRF tokens
	 *
	 * @param Request $request Request
	 *
	 * @return void
	 * @throws CsrfException
	 */
	public function __invoke(Request $request) {
		$request->elgg()->csrf->validate($request);
	}
}
