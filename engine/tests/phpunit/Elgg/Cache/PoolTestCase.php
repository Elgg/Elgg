<?php
namespace Elgg\Cache;

interface PoolTestCase {
	public function testGetDoesNotRegenerateValueFromCallbackOnHit();

	public function testGetRegeneratesValueFromCallbackOnMiss();
	
	public function testInvalidateForcesTheSpecifiedValueToBeRegenerated();
}