<?php

namespace Elgg;

use Elgg\Http\ResponseBuilder;

abstract class ActionResponseTestCase extends IntegrationTestCase {

	public function up() {
		$this->createApplication([
			'isolate' => true,
		]);
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
	 */
	public function executeAction($name, array $params = [], $ajax = false, $add_csrf_tokens = true) {
		$request = $this->prepareHttpRequest("action/{$name}", 'POST', $params, $ajax, $add_csrf_tokens);
		_elgg_services()->set('request', $request);

		return _elgg_services()->router->getResponse($request);
	}
}
