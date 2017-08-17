<?php

namespace Elgg\Notifications;

/**
 * @group NotificationsService
 * @group UserNotifications
 * @group UnitTests
 */
class NotificationsServiceElggUserTest extends NotificationsServiceUnitTestCase {

	public function up() {
		$this->test_object_class = \ElggUser::class;
		parent::up();
	}

	public function down() {

	}

}