<?php

namespace Elgg\lib\elgglib;

class ElggExtractUnitTest extends \Elgg\UnitTestCase {

	function testCanExtract() {
		$this->assertSame('b', elgg_extract('a', ['a' => 'b']));
		$this->assertSame('b', elgg_extract(1, [1 => 'b']));
	}

	function testUsesDefault() {
		$this->assertSame(null, elgg_extract('f', []));
		$this->assertSame('default', elgg_extract('a', [], 'default'));

		$this->assertSame('default', elgg_extract(null, ['a' => 'b'], 'default'));
		$this->assertSame('default', elgg_extract(-1, ['a' => 'b'], 'default'));
		$this->assertSame('default', elgg_extract(3.0, ['a' => 'b'], 'default'));
		$this->assertSame('default', elgg_extract(true, [1 => 'b'], 'default'));
	}

	function testStrictIgnoresEmptiness() {
		$this->assertSame(false, elgg_extract('a', ['a' => false]));
		$this->assertSame(null, elgg_extract('a', ['a' => false], null, false));
	}

	function testCantHandleNull() {
		$this->assertSame('default', elgg_extract('a', ['a' => null], 'default'));
		$this->assertSame('default', elgg_extract('a', ['a' => null], 'default', false));
	}
}
