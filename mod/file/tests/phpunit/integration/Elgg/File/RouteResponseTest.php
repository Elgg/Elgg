<?php

namespace Elgg\File;

/**
 * @group Router
 * @group FileRoutes
 */
class RouteResponseTest extends \Elgg\Plugins\RouteResponseTest {

	public function getSubtype() {
		return 'file';
	}

	/**
	 * @expectedException \Elgg\EntityNotFoundException
	 */
	public function testViewRouteRespondsWithErrorIfEntityIsOfIncorrectSubtype() {
		parent::testViewRouteRespondsWithErrorIfEntityIsOfIncorrectSubtype();
	}
}