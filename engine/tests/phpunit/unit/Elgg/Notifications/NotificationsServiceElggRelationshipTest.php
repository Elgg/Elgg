<?php

namespace Elgg\Notifications;

/**
 * @group NotificationsService
 * @group RelationshipNotifications
 */
class NotificationsServiceElggRelationshipTest extends NotificationsServiceUnitTestCase {
	
	public function setUp() {
		$this->test_object_class = \ElggRelationship::class;
		parent::setUp();
	}

}