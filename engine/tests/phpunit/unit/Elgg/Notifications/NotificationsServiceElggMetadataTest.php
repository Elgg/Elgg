<?php

namespace Elgg\Notifications;

/**
 * @group NotificationsService
 * @group MetadataNotifications
 * @group UnitTests
 */
class NotificationsServiceElggMetadataTest extends NotificationsServiceUnitTestCase {

	public function up() {
		$this->test_object_class = \ElggMetadata::class;

		/**
		 * @todo: MetadataTable mock is currently unable to handle queries with
		 * an SQL access query that is different from the one that was in place when
		 * the mock was created. Because we have a logged in user during some
		 * tests, the SQL query differs.
		 * In practice this shouldn't affect the reliability of tests.
		 * Given the plan is to remove access from metadata table, this may need to be
		 * reimplmented once that is in.
		 */
		$this->ignore_access = elgg_set_ignore_access(true);

		parent::up();
	}

	public function down() {
		elgg_set_ignore_access($this->ignore_access);
		parent::down();
	}

}