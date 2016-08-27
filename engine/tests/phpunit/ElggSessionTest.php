<?php

/**
 * Many methods for \ElggSession pass through to the storage class so we
 * don't test them here.
 *
 * @group Session
 */
class ElggSessionTest extends \Elgg\TestCase {

	public function testStart() {
		$session = \ElggSession::getMock();
		$this->assertTrue($session->start());
		$this->assertTrue($session->has('__elgg_session'));
	}

	public function testInvalidate() {
		$session = \ElggSession::getMock();
		$session->start();
		$session->set('foo', 5);
		$id = $session->getId();
		$this->assertTrue($session->invalidate());
		$this->assertFalse($session->has('foo'));
		$this->assertNotEquals($id, $session->getId());
		$this->assertTrue($session->has('__elgg_session'));
	}

	public function testMigrate() {
		$session = \ElggSession::getMock();
		$session->start();
		$session->set('foo', 5);
		$id = $session->getId();
		$this->assertTrue($session->migrate());
		$this->assertTrue($session->has('foo'));
		$this->assertNotEquals($id, $session->getId());
		$this->assertTrue($session->has('__elgg_session'));
	}

	public function testCanSetLoggedInUser() {

		$user = $this->getMockBuilder(\ElggUser::class)
		->setMethods(['__get'])
		->disableOriginalConstructor()
		->getMock();

		$user->expects($this->any())
		->method('__get')
		->will($this->returnCallback(function($name) {
			if ($name == 'guid') {
				return 123;
			}
		}));

		$session = \ElggSession::getMock();

		$session->setLoggedInUser($user);

		$this->assertEquals($user, $session->getLoggedInUser());
		$this->assertEquals(123, $session->getLoggedInUserGuid());

		$session->removeLoggedInUser();

		$this->assertNull($session->getLoggedInUser());
	}

}
