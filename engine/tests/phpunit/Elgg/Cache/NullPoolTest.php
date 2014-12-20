<?php
namespace Elgg\Cache;

use PHPUnit_Framework_TestCase as TestCase;

class NullPoolTest extends TestCase implements PoolTestCase {
	public function testGetDoesNotRegenerateValueFromCallbackOnHit() {
		// NullPool never hits, so nothing to test here
		$this->assertTrue(true);
	}
	
	public function testGetRegeneratesValueFromCallbackOnMiss() {
		$pool = new NullPool();
		
		$result = $pool->get('foo', function() { return 1; });
		$this->assertEquals(1, $result);
		
		$result = $pool->get('foo', function() { return 2; });
		$this->assertEquals(2, $result);
	}
	
	public function testInvalidateForcesTheSpecifiedValueToBeRegenerated() {
		// All values are always regenerated. Nothing to test here...
		$this->assertTrue(true);
	}

	public function testNeverCachesResults() {
		$pool = new NullPool();
		$increment = function() {
			static $counter;
			
			if (!isset($counter)) {
				$counter = 0;
			}
			
			return $counter++;
		};
		
		$this->assertEquals(0, $pool->get('foo', $increment));
		$this->assertEquals(1, $pool->get('foo', $increment));
		$pool->put('foo', 4);
		$this->assertEquals(2, $pool->get('foo', $increment));
		$pool->invalidate('foo');
		$this->assertEquals(3, $pool->get('foo', $increment));
	}
}

