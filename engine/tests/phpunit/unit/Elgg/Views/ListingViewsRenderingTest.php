<?php

namespace Elgg\Views;

/**
 * @group ViewRendering
 * @group ViewsService
 */
class ListingViewsRenderingTest extends ViewRenderingTestCase {

	public function getViewNames() {
		return [
			'page/components/list',
			'page/components/gallery',
			'page/components/table',
		];
	}

	public function getDefaultViewVars() {
		$object = $this->createObject();
		$items = array_fill(0, 25, $object);
		return [
			'items' => $items,
		];
	}
}
