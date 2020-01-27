<?php

namespace Elgg\Blog;

/**
 * @group Router
 * @group BlogRoutes
 */
class RouteResponseTest extends \Elgg\Plugins\RouteResponseTest {

	public function getSubtype() {
		return 'blog';
	}
	
	public function groupRoutesProtectedByToolOption() {
		return [
			[
				'route' => "collection:object:{$this->getSubtype()}:group",
				'tool' => 'blog',
			],
		];
	}
}