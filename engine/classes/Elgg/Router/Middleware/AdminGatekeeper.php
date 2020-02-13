<?php

namespace Elgg\Router\Middleware;

use Elgg\Exceptions\HttpException;
use Elgg\Request;

/**
 * Protects a route from non-admin users
 */
class AdminGatekeeper {

	/**
	 * Gatekeeper
	 *
	 * @param Request $request Request
	 *
	 * @return void
	 * @throws HttpException
	 */
	public function __invoke(Request $request) {
		$request->elgg()->gatekeeper->assertAuthenticatedAdmin();
	}
}
