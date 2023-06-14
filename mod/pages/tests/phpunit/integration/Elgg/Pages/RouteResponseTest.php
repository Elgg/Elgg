<?php

namespace Elgg\Pages;

class RouteResponseTest extends \Elgg\Plugins\RouteResponseIntegrationTestCase {

	public function getSubtype() {
		return 'page';
	}
	
	public function groupRoutesProtectedByToolOption() {
		return [
			[
				'route' => "collection:object:{$this->getSubtype()}:group",
				'tool' => 'pages',
			],
		];
	}
}
