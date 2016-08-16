<?php

namespace Elgg\Database;

/**
 * @group ElggMetadata
 */
class MetastringsTest extends \Elgg\TestCase {

	public function setUp() {
		$this->setupMockServices();
	}

	public function testCanGetIdCaseSensitive() {
		$id = elgg_get_metastring_id('abcdef');
		$this->assertEquals($id, elgg_get_metastring_id('abcdef'));
		$this->assertNotEquals($id, elgg_get_metastring_id('Abcdef'));
	}

	public function testCanGetIdCaseInsensitive() {
		$ids = [
			elgg_get_metastring_id('abcdef123'),
			elgg_get_metastring_id('Abcdef123'),
			elgg_get_metastring_id('aBcdef123'),
			elgg_get_metastring_id('abCdef123'),
		];

		$this->assertEquals($ids, elgg_get_metastring_id('abcdef123', false));
	}

	public function testCanGetMap() {

		$map = [
			'foo' => elgg_get_metastring_id('foo'),
			'bar' => elgg_get_metastring_id('bar'),
		];

		// Add some strings to make sure we get case-sensitive map
		elgg_get_metastring_id('Foo');
		elgg_get_metastring_id('Bar');

		$dbprefix = elgg_get_config('dbprefix');
		_elgg_services()->db->addQuerySpec([
			'sql' => "SELECT * FROM {$dbprefix}metastrings WHERE string IN (BINARY 'foo',BINARY 'bar')",
			'results' => function() use ($map) {
				$return = [];
				foreach ($map as $string => $id) {
					$return[] = (object) [
						'id' => $id,
						'string' => $string,
					];
				}
				return $return;
			}
		]);

		$this->assertEquals($map, elgg_get_metastring_map(['foo', 'bar']));
	}

}
