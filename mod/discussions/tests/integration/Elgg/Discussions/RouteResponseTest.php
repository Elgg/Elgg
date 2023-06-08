<?php

namespace Elgg\Discussions;

/**
 * @group Router
 * @group DiscussionsRoutes
 */
class RouteResponseTest extends \Elgg\Plugins\RouteResponseIntegrationTestCase {

	public function getSubtype() {
		return 'discussion';
	}
	
	public function groupRoutesProtectedByToolOption() {
		return [
			[
				'route' => "collection:object:{$this->getSubtype()}:group",
				'tool' => 'forum',
			],
		];
	}
}
