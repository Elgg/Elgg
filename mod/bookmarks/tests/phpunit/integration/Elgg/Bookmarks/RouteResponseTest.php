<?php

namespace Elgg\Bookmarks;

class RouteResponseTest extends \Elgg\Plugins\RouteResponseIntegrationTestCase {

	public function getSubtype() {
		return 'bookmarks';
	}
	
	public function groupRoutesProtectedByToolOption() {
		return [
			[
				'route' => "collection:object:{$this->getSubtype()}:group",
				'tool' => 'bookmarks',
			],
		];
	}
}
