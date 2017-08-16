<?php

namespace Elgg\lib\elgglib;

/**
 * @group Elgglib
 * @group UnitTests
 */
class ElggExtractUnitTest extends \Elgg\UnitTestCase {

	public function up() {

	}

	public function down() {

	}

	function testCanExtract() {
		$this->assertSame('b', elgg_extract('a', ['a' => 'b']));
	}

	function testUsesDefault() {
		$this->assertSame(null, elgg_extract('f', []));
		$this->assertSame('default', elgg_extract('a', [], 'default'));
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
