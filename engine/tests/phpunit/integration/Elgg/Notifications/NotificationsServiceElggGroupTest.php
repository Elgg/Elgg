<?php

namespace Elgg\Notifications;

/**
 * @group NotificationsService
 */
class NotificationsServiceElggGroupTest extends NotificationsServiceIntegrationTestCase {

	public function up() {
		$this->test_object_class = \ElggGroup::class;
		parent::up();
	}
}
