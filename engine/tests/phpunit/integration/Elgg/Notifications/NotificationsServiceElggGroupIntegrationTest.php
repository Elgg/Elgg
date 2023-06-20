<?php

namespace Elgg\Notifications;

class NotificationsServiceElggGroupIntegrationTest extends NotificationsServiceIntegrationTestCase {

	public function up() {
		$this->test_object_class = \ElggGroup::class;
		parent::up();
	}
}
