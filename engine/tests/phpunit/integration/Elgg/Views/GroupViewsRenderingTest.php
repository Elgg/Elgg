<?php

namespace Elgg\Views;

use Elgg\ViewRenderingTestCase;

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
		$group = $this->createOne('group');
		return [
			'item' => $group,
			'entity' => $group,
			'guid' => $group->guid,
		];
	}
}