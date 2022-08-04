<?php

namespace Elgg;

use PHPUnit\Framework\TestCase;

class TestCaseUnitTest extends TestCase {

	public function testPHPUnitCorrectlyBootstrapped() {
		$this->assertInstanceOf(Di\InternalContainer::class, _elgg_services());
	}
}
