<?php

use Elgg\IntegrationTestCase;

class MutexIntegrationTest extends IntegrationTestCase {

	protected $mutex;
	
	/**
	 * {@inheritdoc}
	 */
	public function up() {
		$this->mutex = _elgg_services()->mutex;
	}
	
	public function testInvalidNamespace() {
		$this->expectException(\Elgg\Exceptions\InvalidArgumentException::class);
		$this->mutex->isLocked('0000');
	}

	public function testLockUnlock() {
		$this->assertFalse($this->mutex->isLocked('foo'));
		$this->assertFalse($this->mutex->isLocked('bar'));
		
		$this->assertTrue($this->mutex->lock('foo'));
		$this->assertFalse($this->mutex->lock('foo'));
		$this->assertTrue($this->mutex->isLocked('foo'));

		$this->assertFalse($this->mutex->isLocked('bar'));

		$this->mutex->unlock('foo');
		$this->mutex->unlock('bar');

		$this->assertFalse($this->mutex->isLocked('foo'));
		$this->assertFalse($this->mutex->isLocked('bar'));
	}
}
