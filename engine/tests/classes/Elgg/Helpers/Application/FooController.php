<?php

namespace Elgg\Helpers\Application;

use Elgg\Request;
use Elgg\Http\OkResponse;

/**
 * @see Elgg\ApplicationUnitTest
 */
class FooController {
	public function __invoke(Request $request) {
		$response = new OkResponse($request->getParam('echo'));
		return $response;
	}
}
