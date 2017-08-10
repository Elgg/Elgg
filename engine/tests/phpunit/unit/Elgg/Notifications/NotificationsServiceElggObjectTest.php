<?php

namespace Elgg\Notifications;

/**
 * @group NotificationsService
 * @group ObjectNotifications
 */
class NotificationsServiceElggObjectTest extends NotificationsServiceUnitTestCase {
	
	public function setUp() {
		$this->test_object_class = \ElggObject::class;
		parent::setUp();
	}

}