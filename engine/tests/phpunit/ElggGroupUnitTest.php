<?php

/**
 * @group UnitTests
 */
class ElggGroupUnitTest extends \Elgg\UnitTestCase {

	public function up() {

	}

	public function down() {

	}

	public function testCanConstructWithoutArguments() {
		$this->assertNotNull(new \ElggGroup());
	}

}
