<?php
namespace Elgg\Cache;

use PHPUnit_Framework_TestCase as TestCase;
use Stash;

class StashPoolTest extends TestCase implements PoolTestCase {

	public function testGetDoesNotRegenerateValueFromCallbackOnHit() {
		$pool = StashPool::createEphemeral();

		$pool->get('foo', function() { return 1; });
		$result = $pool->get('foo', function() { return 2; });
		$this->assertEquals(1, $result);
	}
	
	public function testGetRegeneratesValueFromCallbackOnMiss() {
		$pool = StashPool::createEphemeral();
		
		$result = $pool->get('foo', function() { return 1; });
		$this->assertEquals(1, $result);
	}
	
	public function testInvalidateForcesTheSpecifiedValueToBeRegenerated() {
		$pool = StashPool::createEphemeral();

		$result = $pool->get('foo', function() { return 1; });
		$this->assertEquals(1, $result);
		$pool->invalidate('foo');

		$result = $pool->get('foo', function() { return 2; });
		$this->assertEquals(2, $result);
	}

	/**
	 * Stash recommends always calling $item->lock() on miss to make sure that
	 * the caching is as performant as possible by avoiding multiple
	 * simultaneous regenerations of the same value.
	 * 
	 * http://www.stashphp.com/Invalidation.html#stampede-protection
	 * 
	 * 1. Create a new cache
	 * 2. Get any entry
	 * 3. Check that Stash\Item::lock() was called
	 * 4. Get the same entry
	 * 5. Check that Stash\Item::lock() was *not* called
	 */
	public function testEnablesStashStampedeProtection() {
		$pool = StashPool::createEphemeral();
		$this->markTestIncomplete();
	}
}
