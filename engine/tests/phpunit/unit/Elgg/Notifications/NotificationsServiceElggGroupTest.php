<?php

namespace Elgg\Notifications;

/**
 * @group NotificationsService
 * @group GroupNotifications
 * @gorup UnitTests
 */
class NotificationsServiceElggGroupTest extends NotificationsServiceUnitTestCase {

	public function up() {
		$this->test_object_class = \ElggGroup::class;
		parent::up();
	}

	public function down() {

	}

}