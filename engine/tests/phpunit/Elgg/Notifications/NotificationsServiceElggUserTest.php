<?php

namespace Elgg\Notifications;

/**
 * @group NotificationsService
 */
class NotificationsServiceElggUserTest extends NotificationsServiceTestCase {

	public function setUp() {
		$this->test_object_class = \ElggUser::class;
		parent::setUp();
	}

}