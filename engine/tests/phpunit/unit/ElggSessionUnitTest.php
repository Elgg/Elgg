<?php

/**
 * Many methods for \ElggSession pass through to the storage class so we
 * don't test them here.
 *
 * @group Session
 * @group UnitTests
 */
class ElggSessionUnitTest extends \Elgg\UnitTestCase {

	public function down() {
		_elgg_services()->session->invalidate();
	}

	public function testStart() {
		$session = \ElggSession::getMock();
		$this->assertTrue($session->start());
		$this->assertTrue($session->has('__elgg_session'));
	}

	public function testInvalidate() {
		$session = \ElggSession::getMock();
		$session->start();
		$session->set('foo', 5);
		$id = $session->getID();
		$this->assertTrue($session->invalidate());
		$this->assertFalse($session->has('foo'));
		$this->assertNotEquals($id, $session->getID());
		$this->assertTrue($session->has('__elgg_session'));
	}

	public function testMigrate() {
		$session = \ElggSession::getMock();
		$session->start();
		$session->set('foo', 5);
		$id = $session->getID();
		$this->assertTrue($session->migrate());
		$this->assertTrue($session->has('foo'));
		$this->assertNotEquals($id, $session->getID());
		$this->assertTrue($session->has('__elgg_session'));
	}
}
