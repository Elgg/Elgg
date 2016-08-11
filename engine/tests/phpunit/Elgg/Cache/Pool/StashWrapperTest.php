<?php

namespace Elgg\Cache\Pool;

class StashWrapperTest extends \Elgg\TestCase implements \Elgg\Cache\Pool\TestCase {

	public function testGetDoesNotRegenerateValueFromCallbackOnHit() {
		$pool = StashWrapper::createEphemeral();

		$pool->get('foo', function() {
			return 1;
		});
		$result = $pool->get('foo', function() {
			return 2;
		});
		$this->assertEquals(1, $result);
	}

	public function testGetRegeneratesValueFromCallbackOnMiss() {
		$pool = StashWrapper::createEphemeral();

		$result = $pool->get('foo', function() {
			return 1;
		});
		$this->assertEquals(1, $result);
	}

	public function testInvalidateForcesTheSpecifiedValueToBeRegenerated() {
		$pool = StashWrapper::createEphemeral();

		$result = $pool->get('foo', function() {
			return 1;
		});
		$this->assertEquals(1, $result);
		$pool->invalidate('foo');

		$result = $pool->get('foo', function() {
			return 2;
		});
		$this->assertEquals(2, $result);
	}

	public function testAcceptsStringAndIntKeys() {
		$pool = StashWrapper::createEphemeral();

		foreach (array('123', 123) as $key) {
			$pool->put($key, 'foo');
			$pool->get($key, function () {
				return 'foo';
			});
			$pool->invalidate($key);
		}
	}

	/**
	 * @dataProvider invalidKeyProvider
	 */
	public function testPutComplainsAboutInvalidKeys($key) {
		$pool = StashWrapper::createEphemeral();
		$this->setExpectedException('PHPUnit_Framework_Error_Warning', 'assert');
		$pool->put($key, 'foo');
	}

	/**
	 * @dataProvider invalidKeyProvider
	 */
	public function testGetComplainsAboutInvalidKeys($key) {
		$pool = StashWrapper::createEphemeral();
		$this->setExpectedException('PHPUnit_Framework_Error_Warning', 'assert');
		$pool->get($key, function () {
			return 'foo';
		});
	}

	/**
	 * @dataProvider invalidKeyProvider
	 */
	public function testInvalidateComplainsAboutInvalidKeys($key) {
		$pool = StashWrapper::createEphemeral();
		$this->setExpectedException('PHPUnit_Framework_Error_Warning', 'assert');
		$pool->invalidate($key);
	}

	public function invalidKeyProvider() {
		return array(
			array(123.1),
			array(true),
			array(array()),
			array(new \stdClass()),
		);
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
		$pool = StashWrapper::createEphemeral();
		$this->markTestIncomplete();
	}

}
