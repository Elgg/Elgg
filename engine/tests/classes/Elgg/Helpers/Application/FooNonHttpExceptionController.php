<?php

namespace Elgg\Helpers\Application;

use Elgg\Request;

/**
 * @see Elgg\ApplicationUnitTest
 */
class FooNonHttpExceptionController {
	public function __invoke(Request $request) {
		throw new \InvalidArgumentException($request->getParam('echo'));
	}
}
