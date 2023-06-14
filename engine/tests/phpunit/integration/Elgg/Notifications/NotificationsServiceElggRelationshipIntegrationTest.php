<?php

namespace Elgg\Notifications;

class NotificationsServiceElggRelationshipIntegrationTest extends NotificationsServiceIntegrationTestCase {

	public function up() {
		$this->test_object_class = \ElggRelationship::class;
		parent::up();
	}
}
