<?php

namespace Elgg\Router\Middleware;

use Elgg\HttpException;
use Elgg\Request;

/**
 * Protects a route from non-xhr requests
 */
class AjaxGatekeeper {

	/**
	 * Gatekeeper
	 *
	 * @param Request $request Request
	 *
	 * @return void
	 * @throws HttpException
	 */
	public function __invoke(Request $request) {
		$request->elgg()->gatekeeper->assertXmlHttpRequest();
	}
}
