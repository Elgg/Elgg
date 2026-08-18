<?php

namespace Elgg\Pages;

class RouteResponseTest extends \Elgg\Plugins\RouteResponseIntegrationTestCase {

	protected static function getSubtype() {
		return 'page';
	}
	
	protected function getGroupToolName(): ?string {
		return 'pages';
	}
	
	public static function groupRoutesProtectedByToolOption() {
		return [
			[
				'route' => 'collection:object:' . self::getSubtype() . ':group',
				'tool' => 'pages',
			],
		];
	}
}
