<?php

namespace Elgg\Helpers\Application;

use Elgg\Exceptions\HttpException;
use Elgg\Request;

/**
 * @see Elgg\ApplicationUnitTest
 */
class FooExceptionController {
	public function __invoke(Request $request) {
		$msg = $request->getParam('msg');
		$code = $request->getParam('code', ELGG_HTTP_INTERNAL_SERVER_ERROR);
		throw new HttpException($msg, $code);
	}
}
