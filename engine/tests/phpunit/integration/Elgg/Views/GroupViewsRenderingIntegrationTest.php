<?php

namespace Elgg\Views;

class GroupViewsRenderingIntegrationTest extends ViewRenderingIntegrationTestCase {

	public static function getViewNames() {
		return [
			'group/elements/summary',
			'group/default',
		];
	}

	public function getDefaultViewVars() {
		$group = $this->createGroup();
		return [
			'item' => $group,
			'entity' => $group,
			'guid' => $group->guid,
		];
	}
}
