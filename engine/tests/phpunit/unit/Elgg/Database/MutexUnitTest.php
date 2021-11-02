<?php

namespace Elgg\Database;

/**
 * @group UnitTests
 */
class MutexUnitTest extends \Elgg\UnitTestCase {

	public function testMutexLocksIfNotAlreadyLocked() {
		$this->markTestIncomplete();
	}

	public function testMutexFailsIfAlreadyLocked() {
		$this->markTestIncomplete();
	}

	public function testUnlockingFailsIfWrongNamespace() {
		$this->markTestIncomplete();
	}
}
