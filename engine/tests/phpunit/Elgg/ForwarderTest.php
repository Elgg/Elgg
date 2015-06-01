<?php
namespace Elgg;

class ForwarderTest extends \PHPUnit_Framework_TestCase {

	public function testForward() {
		try {
			forward();
		} catch (ForwardException $e) {
			$this->assertEquals('', $e->getLocation());
			$this->assertEquals('system', $e->getReason());
			return;
		}

		$this->fail('Did not throw ' . ForwardException::class);
	}
}
