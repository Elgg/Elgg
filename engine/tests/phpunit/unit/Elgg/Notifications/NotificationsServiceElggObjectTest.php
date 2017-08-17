<?php

namespace Elgg\Notifications;

/**
 * @group NotificationsService
 * @group ObjectNotifications
 * @group UnitTests
 */
class NotificationsServiceElggObjectTest extends NotificationsServiceUnitTestCase {

	public function up() {
		$this->test_object_class = \ElggObject::class;
		parent::up();
	}

	public function down() {

	}

}