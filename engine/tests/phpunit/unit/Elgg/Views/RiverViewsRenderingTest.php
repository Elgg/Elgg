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
			'river/comment/object/message',
			'river/create/object/message',
			'river/friend/user/attachments',
			'river/profileiconupdate/user/attachments',
			'river/profileiconupdate/user/responses',
		];
	}

	public function getDefaultViewVars() {
		$item = new \ElggRiverItem((object) [
			'action_type' => 'test',
			'object_guid' => $this->createObject()->guid,
			'subject_guid' => $this->createUser()->guid,
		]);

		return [
			'item' => $item,
		];
	}
}
