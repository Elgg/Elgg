<?php

namespace Elgg;

/**
 * @group UnitTests
 */
class TagsUnitTest extends \Elgg\UnitTestCase {

	public function up() {

	}

	public function down() {

	}

	/**
	 * When providing a string to the string_to_tag_array function it should explode it based on a comma and return an array
	 */
	public function testStringToTagArrayReturnsArray() {

		// test if it returns an array with 1 item
		$single_tag = string_to_tag_array("a");
		$this->assertCount(1, $single_tag);

		// test if it returns an array with 3 item
		$multiple_tags = string_to_tag_array("a,b,c");
		$this->assertCount(3, $multiple_tags);
	}

	/**
	 * When providing a non-string the string_to_tag_array function should return the original value
	 */
	public function testStringToTagArrayReturnsOriginalValue() {

		// test if the original value (int) 1 is returned
		$result = string_to_tag_array(1);
		$this->assertEquals(1, $result);
	}

}
