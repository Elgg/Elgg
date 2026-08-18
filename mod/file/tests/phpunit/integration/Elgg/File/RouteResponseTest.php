<?php

namespace Elgg\File;

class RouteResponseTest extends \Elgg\Plugins\RouteResponseIntegrationTestCase {

	protected static function getSubtype() {
		return 'file';
	}
	
	protected function getGroupToolName(): ?string {
		return 'file';
	}
	
	public static function groupRoutesProtectedByToolOption() {
		return [
			[
				'route' => 'collection:object:' . self::getSubtype() . ':group',
				'tool' => 'file',
			],
		];
	}
}
