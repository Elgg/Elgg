<?php

namespace Elgg\Views;

use Elgg\ViewRenderingTestCase;

/**
 * @group ViewRendering
 * @group ViewsService
 */
class RiverViewsRenderingTest extends ViewRenderingTestCase {

	public function getViewNames() {
		return [
			'river/elements/body',
			'river/elements/image',
			'river/elements/layout',
			'river/elements/responses',
			'river/elements/summary',
			'river/object/comment/create',
			'river/relationship/friend/create',
			'river/user/default/profileiconupdate',
		];
	}

	public function getDefaultViewVars() {
		$item = new \ElggRiverItem((object) [
			'view' => 'river/elements/layout',
			'action_type' => 'test',
			'object_guid' => $this->createOne('object')->guid,
			'subject_guid' => $this->createOne('user')->guid,
		]);

		return [
			'item' => $item,
		];
	}
}