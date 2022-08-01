<?php

namespace Elgg\Notifications;

/**
 * @group NotificationsService
 */
class NotificationsServiceElggObjectTest extends NotificationsServiceIntegrationTestCase {

	public function up() {
		$this->test_object_class = \ElggObject::class;
		parent::up();
	}
}
