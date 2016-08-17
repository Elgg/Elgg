<?php

namespace Elgg\Cache\Pool;

class InMemoryTest extends \Elgg\TestCase implements TestCase {

	public function testGetDoesNotRegenerateValueFromCallbackOnHit() {
		$pool = new InMemory();

		$pool->get('foo', function() {
			return 1;
		});
		$result = $pool->get('foo', function() {
			return 2;
		});
		$this->assertEquals(1, $result);
	}

	public function testGetRegeneratesValueFromCallbackOnMiss() {
		$pool = new InMemory();

		$result = $pool->get('foo', function() {
			return 1;
		});
		$this->assertEquals(1, $result);
	}

	public function testInvalidateForcesTheSpecifiedValueToBeRegenerated() {
		$pool = new InMemory();

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

	public function testPutOverridesGetCallback() {
		$pool = new InMemory();

		$result = $pool->get('foo', function() {
			return 1;
		});
		$this->assertEquals(1, $result);

		$pool->put('foo', 2);

		$result = $pool->get('foo', function() {
			return 3;
		});
		$this->assertEquals(2, $result);
	}

}
