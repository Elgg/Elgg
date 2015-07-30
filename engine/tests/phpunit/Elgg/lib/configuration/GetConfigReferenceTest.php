<?php
namespace Elgg\lib\configuration;

class GetConfigReferenceTest extends \PHPUnit_Framework_TestCase {

	function testCanReferenceArray() {

		// direct array usage!
		elgg_get_config_reference('foo:bar')[] = 'hello';

		// store ref in variable
		$ref = &elgg_get_config_reference('foo:bar');
		$ref[] = 'world';

		$this->assertEquals(['hello', 'world'], elgg_get_config_reference('foo:bar'));
	}

	function testReturnsDefault() {
		$this->assertEquals([], elgg_get_config_reference('not:set:', []));
	}
}
