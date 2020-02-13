<?php

namespace Elgg\Router\Middleware;

use Elgg\Exceptions\HttpException;
use Elgg\Request;

/**
 * Protects a route url tampering
 */
class SignedRequestGatekeeper {
	
	/**
	 * Make sure the request is correctly signed
	 *
	 * @param Request $request Request
	 *
	 * @return void
	 * @throws HttpException
	 */
	public function __invoke(Request $request) {
		_elgg_services()->urlSigner->assertValid($request->getURL());
	}
}
