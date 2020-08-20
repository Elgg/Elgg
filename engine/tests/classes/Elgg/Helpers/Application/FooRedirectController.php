<?php

namespace Elgg\Helpers\Application;

use Elgg\Exceptions\HttpException;
use Elgg\Request;

/**
 * @see Elgg\ApplicationUnitTest
 */
class FooRedirectController {
	public function __invoke(Request $request) {
		$msg = $request->getParam('msg');
		$code = $request->getParam('code', ELGG_HTTP_TEMPORARY_REDIRECT);
		$ex = new HttpException($msg, $code);
		$ex->setRedirectUrl($request->getParam('forward_url'));
		throw $ex;
	}
}
