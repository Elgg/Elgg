<?php

namespace Elgg\Blog;

class RouteResponseTest extends \Elgg\Plugins\RouteResponseIntegrationTestCase {

	protected static function getSubtype() {
		return 'blog';
	}
	
	public static function groupRoutesProtectedByToolOption() {
		return [
			[
				'route' => 'collection:object:' . self::getSubtype() . ':group',
				'tool' => 'blog',
			],
		];
	}
}
