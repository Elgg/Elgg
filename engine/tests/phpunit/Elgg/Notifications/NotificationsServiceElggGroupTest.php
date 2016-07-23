<?php

namespace Elgg\Notifications;

/**
 * @group NotificationsService
 */
class NotificationsServiceElggGroupTest extends NotificationsServiceTestCase {
	
	public function setUp() {
		$this->test_object_class = \ElggGroup::class;
		parent::setUp();
	}

}