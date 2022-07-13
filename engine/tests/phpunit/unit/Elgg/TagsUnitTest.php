<?php

namespace Elgg;

class TagsUnitTest extends \Elgg\UnitTestCase {

	/**
	 * When providing a string to the elgg_string_to_array function it should explode it based on a comma and return an array
	 */
	public function testStringToArrayReturnsArray() {

		// test if it returns an array with 0 items
		$no_tag = elgg_string_to_array('');
		$this->assertEquals([], $no_tag);

		// test if it returns an array with 1 item
		$single_tag = elgg_string_to_array('a');
		$this->assertEquals(['a'], $single_tag);

		// test if it returns an array with 3 items
		$multiple_tags = elgg_string_to_array('a,b,c');
		$this->assertEquals(['a', 'b', 'c'], $multiple_tags);

		// test if it returns an array with correctly stripped items
		$some_empty_tags = elgg_string_to_array('  a,,  ,0 0 , ,b,0,c,  ');
		$this->assertEquals(['a', '0 0', 'b', '0', 'c'], $some_empty_tags);

		// test if it returns unique items
		$not_unique_tags = elgg_string_to_array('a,a,b,c,c,c,d');
		$this->assertEquals(['a', 'b', 'c', 'd'], $not_unique_tags);
	}
}
