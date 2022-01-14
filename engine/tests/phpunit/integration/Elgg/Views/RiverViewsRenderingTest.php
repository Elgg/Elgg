<?php

namespace Elgg\Views;

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
			'river/object/create',
			'river/relationship/friend/create',
			'river/user/default/profileiconupdate',
		];
	}

	public function getDefaultViewVars() {
		$item = new \ElggRiverItem((object) [
			'view' => 'river/elements/layout',
			'action_type' => 'test',
			'object_guid' => $this->createObject()->guid,
			'subject_guid' => $this->createUser()->guid,
		]);

		return [
			'item' => $item,
		];
	}
}
