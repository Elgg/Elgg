<?php

namespace Elgg\Views;

class ListingViewsRenderingIntegrationTest extends ViewRenderingIntegrationTestCase {

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
