<?php

namespace Elgg\Notifications;

class NotificationsServiceElggObjectIntegrationTest extends NotificationsServiceIntegrationTestCase {

	public function up() {
		$this->test_object_class = \ElggObject::class;
		parent::up();
	}
}
