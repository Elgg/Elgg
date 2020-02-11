<?php

namespace Elgg\Search;

use Elgg\BaseTestCase;
use Elgg\Plugins\PluginTesting;
use Elgg\UnitTestCase;

/**
 * @group SearchPlugin
 * @group Search
 */
class SearchRouterTest extends UnitTestCase {

	use PluginTesting;

	public function up() {
		$this->startPlugin();

		elgg_register_entity_type('object', 'custom');
		_elgg_services()->views->registerPluginViews($this->getPath());
	}

	public function down() {

	}

	public function testPageHandler() {
		$params = [
			'q' => 'lorem ipsum',
		];

		$request = BaseTestCase::prepareHttpRequest("search", 'GET', $params);
		_elgg_services()->setValue('request', $request);

		_elgg_services()->router->route($request);

		$response = _elgg_services()->responseFactory->getSentResponse();

		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());
	}
}