<?php

namespace Elgg\Notifications;

/**
 * @group NotificationsService
 */
class NotificationsServiceElggMetadataTest extends NotificationsServiceTestCase {
	
	public function setUp() {
		$this->test_object_class = \ElggMetadata::class;
		parent::setUp();
	}

}