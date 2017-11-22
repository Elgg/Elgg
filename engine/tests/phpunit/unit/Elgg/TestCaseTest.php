<?php

namespace Elgg;

use PHPUnit\Framework\TestCase;

/**
 * @group Testing
 * @group UnitTests
 */
class TestCaseTest extends TestCase {

	/**
	 * Test that legacy bootstrap has been autoloaded and
	 * stay BC with older test cases
	 */
	public function testIsBoostrapped() {
		$this->assertInstanceOf(Di\ServiceProvider::class, _elgg_services());
		$this->assertInstanceOf(Application::class, _elgg_testing_application());
		$this->assertInstanceof(Config::class, _elgg_testing_config());
		$this->assertInstanceOf(Http\Request::class, _elgg_testing_request());
	}

}
