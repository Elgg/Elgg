<?php
namespace Elgg;

class CallableMatcherTest extends \PHPUnit_Framework_TestCase {

	public function testVanillaCallablesMatchAsExpected() {
		$obj = new \stdClass();
		$f = function () {};

		$matching_sets = [
			['\Foo\bar', 'Foo\bar'],
			['Foo::bar', '\Foo::bar', ['Foo', 'bar'], ['\Foo', 'bar']],
			[[$obj, 'bar']],
			[$f],
		];

		foreach ($matching_sets as $set_key => $set) {
			foreach ($set as $item_key => $item) {
				$matcher = new CallableMatcher($item);

				// all set items should match each other
				for ($i = 0; $i < count($set); $i++) {
					$message = "spec " . var_export($item, true) . " failed to match " . var_export($set[$i], true);
					$this->assertTrue($matcher->matches($set[$i]), $message);
				}

				// no item in set should match items in other sets
				for ($j = 0; $j < count($matching_sets); $j++) {
					if ($j == $set_key) {
						continue;
					}
					for ($k = 0; $k < count($matching_sets[$j]); $k++) {
						$message = "spec " . var_export($item, true) . " matched " . var_export($matching_sets[$j][$k], true);
						$this->assertFalse($matcher->matches($matching_sets[$j][$k]), $message);
					}
				}
			}
		}
	}

	public function testCanMatchDynamicMethodsByType() {
		$obj = new \stdClass();

		$matcher = new CallableMatcher('\stdClass->bar');
		$this->assertTrue($matcher->matches([$obj, 'bar']));
	}

	public function testCanMatchClosures() {
		$f = function () {
			$hello = 1;
		};
		$line = __LINE__ - 1;

		$matcher = new CallableMatcher('function /Elgg/CallableMatcherTest.php');
		$this->assertTrue($matcher->matches($f));

		$matcher = new CallableMatcher('function lableMatcherTest.php');
		$this->assertTrue($matcher->matches($f));

		$matcher = new CallableMatcher('function LableMATCHerTest.php');
		$this->assertFalse($matcher->matches($f));

		$matcher = new CallableMatcher('function /Elgg/CallableMatcherTest.php:' . $line);
		$this->assertTrue($matcher->matches($f));

		$matcher = new CallableMatcher('function /Elgg/CallableMatcherTest.php:' . ($line + 1));
		$this->assertFalse($matcher->matches($f));

		$matcher = new CallableMatcher('function /Elgg/CallableMatcherTest.php:' . ($line - 2) . '+-2');
		$this->assertTrue($matcher->matches($f));

		$matcher = new CallableMatcher('function /Elgg/CallableMatcherTest.php:' . ($line - 2) . '+-1');
		$this->assertFalse($matcher->matches($f));

		$matcher = new CallableMatcher('function /Elgg/CallableMatcherTest.php:' . ($line + 2) . '+-2');
		$this->assertTrue($matcher->matches($f));

		$matcher = new CallableMatcher('function /Elgg/CallableMatcherTest.php:' . ($line + 2) . '+-1');
		$this->assertFalse($matcher->matches($f));
	}

	public function testCanMatchOldLambdas() {
		$f = create_function('', '
			$hello = 1;
		');
		$line = __LINE__ - 1;

		$matcher = new CallableMatcher('function /Elgg/CallableMatcherTest.php');
		$this->assertTrue($matcher->matches($f));

		$matcher = new CallableMatcher('function lableMatcherTest.php');
		$this->assertTrue($matcher->matches($f));

		$matcher = new CallableMatcher('function LableMATCHerTest.php');
		$this->assertFalse($matcher->matches($f));

		$matcher = new CallableMatcher('function /Elgg/CallableMatcherTest.php:' . $line);
		$this->assertTrue($matcher->matches($f));

		$matcher = new CallableMatcher('function /Elgg/CallableMatcherTest.php:' . ($line + 1));
		$this->assertFalse($matcher->matches($f));

		$matcher = new CallableMatcher('function /Elgg/CallableMatcherTest.php:' . ($line - 2) . '+-2');
		$this->assertTrue($matcher->matches($f));

		$matcher = new CallableMatcher('function /Elgg/CallableMatcherTest.php:' . ($line - 2) . '+-1');
		$this->assertFalse($matcher->matches($f));

		$matcher = new CallableMatcher('function /Elgg/CallableMatcherTest.php:' . ($line + 2) . '+-2');
		$this->assertTrue($matcher->matches($f));

		$matcher = new CallableMatcher('function /Elgg/CallableMatcherTest.php:' . ($line + 2) . '+-1');
		$this->assertFalse($matcher->matches($f));
	}

	public function testCanMatchType() {
		$invokable = new CallableMatcherTest_invokable();

		$matcher = new CallableMatcher('instanceof CallableMatcherTest_invokable');
		$this->assertFalse($matcher->matches($invokable));

		$matcher = new CallableMatcher('instanceof Elgg\CallableMatcherTest_invokable');
		$this->assertTrue($matcher->matches($invokable));
	}
}

class CallableMatcherTest_invokable {
	function __invoke() {}
}
