<?php
namespace Elgg\Cache;

use PHPUnit_Framework_TestCase as TestCase;

class MemoryPoolTest extends TestCase implements PoolTestCase {
	public function testGetDoesNotRegenerateValueFromCallbackOnHit() {
		$pool = new MemoryPool();

		$pool->get('foo', function() { return 1; });
		$result = $pool->get('foo', function() { return 2; });
		$this->assertEquals(1, $result);
	}
	
	public function testGetRegeneratesValueFromCallbackOnMiss() {
		$pool = new MemoryPool();
		
		$result = $pool->get('foo', function() { return 1; });
		$this->assertEquals(1, $result);
	}
	
	public function testInvalidateForcesTheSpecifiedValueToBeRegenerated() {
		$pool = new MemoryPool();

		$result = $pool->get('foo', function() { return 1; });
		$this->assertEquals(1, $result);
		$pool->invalidate('foo');

		$result = $pool->get('foo', function() { return 2; });
		$this->assertEquals(2, $result);
	}

	public function testPutOverridesGetCallback() {
		$pool = new MemoryPool();

		$result = $pool->get('foo', function() { return 1; });
		$this->assertEquals(1, $result);

		$pool->put('foo', 2);

		$result = $pool->get('foo', function() { return 3; });
		$this->assertEquals(2, $result);
	}
}

