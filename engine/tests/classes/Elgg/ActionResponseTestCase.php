<?php

namespace Elgg;

use Elgg\Http\Input;
use Elgg\Http\ResponseBuilder;

abstract class ActionResponseTestCase extends IntegrationTestCase {

	public function up() {

	}

	public function down() {

	}

	/**
	 * Execute an action
	 *
	 * @param string $name   Name of the action
	 * @param array  $params An array of input parameters
	 * @param bool   $ajax   Ajax version
	 *
	 * @return ResponseBuilder
	 */
	public function executeAction($name, array $params = [], $ajax = false) {
		$request = BaseTestCase::prepareHttpRequest("action/{$name}", 'POST', $params, $ajax, true);
		_elgg_services()->setValue('request', $request);
		_elgg_services()->setValue('input', new Input($request, _elgg_services()->context));
		return _elgg_services()->actions->execute($name);
	}
}