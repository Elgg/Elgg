<?php

namespace Elgg\Notifications;

/**
 * @group NotificationsService
 * @group GroupNotifications
 */
class NotificationsServiceElggGroupTest extends NotificationsServiceUnitTestCase {
	
	public function setUp() {
		$this->test_object_class = \ElggGroup::class;
		parent::setUp();
	}

}