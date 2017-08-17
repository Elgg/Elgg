<?php

namespace Elgg\lib\elgglib;

/**
 * @group Elgglib
 * @group UnitTests
 */
class ElggExtractClassUnitTest extends \Elgg\UnitTestCase {

	public function up() {

	}

	public function down() {

	}

	function testCanExtract() {
		$this->assertSame(['b'], elgg_extract_class(['class' => 'b']));
		$this->assertSame(['b'], elgg_extract_class(['class' => ['b']]));
		$this->assertSame(['a', 'b'], elgg_extract_class(['class' => ['a', 'b']]));
	}

	function testCanMergeUniquely() {
		$this->assertSame(['a', 'b'], elgg_extract_class(['class' => 'b'], 'a'));
		$this->assertSame(['a', 'b'], elgg_extract_class(['class' => ['a', 'b']], 'a'));
		$this->assertSame(['a', 'b', 'c', 'd'], elgg_extract_class(['class' => ['c', 'd']], ['a', 'b']));
	}

	function testDefaultIsEmptyArray() {
		$this->assertSame([], elgg_extract_class([]));
	}
}
