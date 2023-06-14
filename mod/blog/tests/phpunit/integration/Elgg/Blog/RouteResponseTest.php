<?php

namespace Elgg\Blog;

class RouteResponseTest extends \Elgg\Plugins\RouteResponseIntegrationTestCase {

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
