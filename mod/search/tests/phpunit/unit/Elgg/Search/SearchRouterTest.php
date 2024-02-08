<?php

namespace Elgg\Search;

use Elgg\BaseTestCase;
use Elgg\UnitTestCase;

class SearchRouterTest extends UnitTestCase {

	public function up() {
		$this->startPlugin();

		elgg_entity_enable_capability('object', 'custom', 'searchable');
		_elgg_services()->views->registerViewsFromPath($this->getPath());
	}

	public function testPageHandler() {
		$request = BaseTestCase::prepareHttpRequest('search', 'GET', [
			'q' => 'lorem ipsum',
		]);
		
		_elgg_services()->set('request', $request);

		_elgg_services()->router->route($request);

		$response = _elgg_services()->responseFactory->getSentResponse();

		$this->assertEquals(ELGG_HTTP_OK, $response->getStatusCode());
	}
}
