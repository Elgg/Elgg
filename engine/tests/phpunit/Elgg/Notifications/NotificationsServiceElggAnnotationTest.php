<?php

namespace Elgg\Notifications;

/**
 * @group NotificationsService
 */
class NotificationsServiceElggAnnotationTest extends NotificationsServiceTestCase {
	
	public function setUp() {
		$this->test_object_class = \ElggAnnotation::class;
		parent::setUp();
	}

}