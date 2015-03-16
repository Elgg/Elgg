<?php
namespace Elgg;

class MethodMatcherTest extends \PHPUnit_Framework_TestCase {

	public function testMatchesStrings() {
		$matcher = new MethodMatcher('stdClass', 'bar');

		$this->assertTrue($matcher->matches('stdClass::bar'));
		$this->assertTrue($matcher->matches('\STDClass::BAR'));
		$this->assertFalse($matcher->matches('foooo::bar'));
		$this->assertFalse($matcher->matches('foo\bar'));
	}

	public function testMatchesStaticArrays() {
		$matcher = new MethodMatcher('stdClass', 'bar');

		$this->assertTrue($matcher->matches(['stdClass', 'bar']));
		$this->assertTrue($matcher->matches(['\STDClass', 'BAR']));
		$this->assertFalse($matcher->matches(['foooo', 'bar']));
	}

	public function testMatchesDynamicArrays() {
		$matcher = new MethodMatcher('stdClass', 'bar');

		$this->assertTrue($matcher->matches([new \stdClass(), 'bar']));
		$this->assertTrue($matcher->matches([new \stdClass(), 'BAR']));
		$this->assertFalse($matcher->matches([new MethodMatcherTestObject, 'bar']));
	}
}

class MethodMatcherTestObject {}
