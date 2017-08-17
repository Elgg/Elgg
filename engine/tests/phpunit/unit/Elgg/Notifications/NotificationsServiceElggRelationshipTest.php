<?php

namespace Elgg\Notifications;

/**
 * @group NotificationsService
 * @group RelationshipNotifications
 * @group UnitTests
 */
class NotificationsServiceElggRelationshipTest extends NotificationsServiceUnitTestCase {

	public function up() {
		$this->test_object_class = \ElggRelationship::class;
		parent::up();
	}

	public function down() {

	}

}