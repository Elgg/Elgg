<?php

namespace Elgg\File;

/**
 * @group Router
 * @group FileRoutes
 */
class RouteResponseTest extends \Elgg\Plugins\RouteResponseIntegrationTestCase {

	public function getSubtype() {
		return 'file';
	}
	
	public function groupRoutesProtectedByToolOption() {
		return [
			[
				'route' => "collection:object:{$this->getSubtype()}:group",
				'tool' => 'file',
			],
		];
	}
}
