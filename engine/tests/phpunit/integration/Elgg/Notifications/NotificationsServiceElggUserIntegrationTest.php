<?php

namespace Elgg\Notifications;

class NotificationsServiceElggUserIntegrationTest extends NotificationsServiceIntegrationTestCase {

	public function up() {
		$this->test_object_class = \ElggUser::class;
		parent::up();
	}
}
