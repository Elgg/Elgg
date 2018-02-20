<?php

namespace Elgg;

use Elgg\Http\ResponseBuilder;
use Exception;

abstract class ActionResponseTestCase extends IntegrationTestCase {

	public function up() {

	}

	public function down() {

	}

	/**
	 * Execute an action
	 *
	 * @param string $name            Name of the action
	 * @param array  $params          An array of input parameters
	 * @param bool   $ajax            Ajax version
	 * @param bool   $add_csrf_tokens Add action tokens
	 *
	 * @return ResponseBuilder
	 * @throws PageNotFoundException
	 * @throws Exception
	 */
	public function executeAction($name, array $params = [], $ajax = false, $add_csrf_tokens = true) {
		$request = BaseTestCase::prepareHttpRequest("action/{$name}", 'POST', $params, $ajax, $add_csrf_tokens);
		_elgg_services()->setValue('request', $request);

		return _elgg_services()->router->getResponse($request);
	}
}