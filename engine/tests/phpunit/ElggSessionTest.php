<?php
/**
 * Many methods for ElggSession pass through to the storage class so we
 * don't test them here.
 */

class ElggSessionTest extends PHPUnit_Framework_TestCase {

	public function testInvalidate() {
		$session = new ElggSession(new Elgg_Http_MockSessionStorage());
		$this->assertTrue($session->start());
		$session->set('foo', 5);
		$id = $session->getId();
		$this->assertTrue($session->invalidate());
		$this->assertFalse($session->has('foo'));
		$this->assertNotEquals($id, $session->getId());
	}

	public function testMigrate() {
		$session = new ElggSession(new Elgg_Http_MockSessionStorage());
		$this->assertTrue($session->start());
		$session->set('foo', 5);
		$id = $session->getId();
		$this->assertTrue($session->migrate());
		$this->assertTrue($session->has('foo'));
		$this->assertNotEquals($id, $session->getId());
	}
}
