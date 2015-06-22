<?php
namespace Elgg\Cache\Pool;

interface TestCase {
	public function testGetDoesNotRegenerateValueFromCallbackOnHit();

	public function testGetRegeneratesValueFromCallbackOnMiss();
	
	public function testInvalidateForcesTheSpecifiedValueToBeRegenerated();
}