<?php
namespace Elgg\Cache\Pool;

class NoopTest extends \PHPUnit_Framework_TestCase implements TestCase {
	public function testGetDoesNotRegenerateValueFromCallbackOnHit() {
		// Noop never hits, so nothing to test here
		$this->assertTrue(true);
	}
	
	public function testGetRegeneratesValueFromCallbackOnMiss() {
		$pool = new Noop();
		
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
		$pool = new Noop();
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

