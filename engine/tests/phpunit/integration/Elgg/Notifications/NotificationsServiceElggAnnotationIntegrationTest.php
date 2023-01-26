<?php

namespace Elgg\Notifications;

/**
 * @group NotificationsService
 */
class NotificationsServiceElggAnnotationIntegrationTest extends NotificationsServiceIntegrationTestCase {

	public function up() {
		$this->test_object_class = \ElggAnnotation::class;
		parent::up();
	}
}
