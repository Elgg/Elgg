<?php

namespace Elgg\Bookmarks;

class RouteResponseTest extends \Elgg\Plugins\RouteResponseIntegrationTestCase {

	protected static function getSubtype() {
		return 'bookmarks';
	}
	
	public static function groupRoutesProtectedByToolOption() {
		return [
			[
				'route' => 'collection:object:' . self::getSubtype() . ':group',
				'tool' => 'bookmarks',
			],
		];
	}
}
