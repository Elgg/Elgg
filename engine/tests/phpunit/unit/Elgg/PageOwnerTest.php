<?php

/**
 * @group UnitTests
 */
class Elgg_PageOwnerUnitTest extends \Elgg\UnitTestCase {

	public function up() {

	}

	public function down() {

	}

	/**
	 * It should be possible to set and unset a page owner even if that results in no page owner at all.
	 */
	public function testSetAndUnsetPageOwner() {
		/**
		 * How to test:
		 *  1. Set page owner
		 *  2. Unset page owner
		 *  3. Assert that fetching page owner results in the expected page owner
		 */

		$user = $this->createUser();

		// check if setting to false returns 0
		elgg_set_page_owner_guid($user->guid);
		$this->assertEquals($user->guid, elgg_get_page_owner_guid());
		elgg_set_page_owner_guid(false);
		$this->assertEquals(0, elgg_get_page_owner_guid());

		// check if setting to null returns 0
		elgg_set_page_owner_guid($user->guid);
		$this->assertEquals($user->guid, elgg_get_page_owner_guid());
		elgg_set_page_owner_guid(null);
		$this->assertEquals(0, elgg_get_page_owner_guid());
	}

}
