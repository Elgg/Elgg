<?php

namespace Elgg\Views;

/**
 * @group ViewRendering
 * @group ViewsService
 */
class GroupViewsRenderingTest extends ViewRenderingTestCase {

	public function getViewNames() {
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
