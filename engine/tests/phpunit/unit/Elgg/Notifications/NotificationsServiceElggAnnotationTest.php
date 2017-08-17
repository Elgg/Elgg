<?php

namespace Elgg\Notifications;

/**
 * @group NotificationsService
 * @group AnnotationNotifications
 * @group UnitTests
 */
class NotificationsServiceElggAnnotationTest extends NotificationsServiceUnitTestCase {

	public function up() {
		$this->test_object_class = \ElggAnnotation::class;
		parent::up();
	}

	public function down() {

	}
}