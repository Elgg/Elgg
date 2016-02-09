<?php
namespace Elgg\Database;

class MutexTest extends \PHPUnit_Framework_TestCase {

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