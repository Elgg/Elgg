<?php

namespace Elgg;

class EntityCapabilitiesServiceIntegrationTest extends \Elgg\IntegrationTestCase {

	function testEntitySetCapability() {
		$this->assertFalse(elgg_entity_has_capability('object', 'blog', 'foo'));
		$this->assertFalse(elgg_entity_has_capability('object', 'blog', 'foo2'));
		
		elgg_entity_enable_capability('object', 'blog', 'foo');
		elgg_entity_enable_capability('object', 'blog', 'foo2');
		
		$this->assertTrue(elgg_entity_has_capability('object', 'blog', 'foo'));
		$this->assertTrue(elgg_entity_has_capability('object', 'blog', 'foo2'));

		elgg_entity_disable_capability('object', 'blog', 'foo2');
		
		$this->assertTrue(elgg_entity_has_capability('object', 'blog', 'foo'));
		$this->assertFalse(elgg_entity_has_capability('object', 'blog', 'foo2'));
	}
	
	function testGetTypesWithCapability() {
		elgg_entity_enable_capability('object', 'blog', 'custom_capability');
		elgg_entity_enable_capability('object', 'page', 'custom_capability');
		elgg_entity_enable_capability('group', 'project', 'custom_capability');

		$this->assertEquals([
			'object' => [
				'blog',
				'page',
			],
			'group' => [
				'project',
			],
		], elgg_entity_types_with_capability('custom_capability'));
	}
}
