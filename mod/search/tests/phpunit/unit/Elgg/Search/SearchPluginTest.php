<?php

namespace Elgg\Search;

use Elgg\UnitTestCase;

/**
 * @group Search
 * @group SearchPlugin
 */
class SearchPluginTest extends UnitTestCase {

	public function up() {
		$this->startPlugin();
	}

	public function testParameterInitialization() {
		set_input('fields', [
			'metadata' => [
				'private',
			],
		]);

		set_input('q', '"literal needle"');

		set_input('entity_type', 'object');
		set_input('entity_subtype', 'blog');
		set_input('search_type', 'custom');

		$svc = new Search();

		$this->assertEquals([
			'query' => 'literal needle',
			'query_parts' => ['literal needle'],
			'offset' => 0,
			'limit' => 10,
			'sort' => null,
			'order' => null,
			'search_type' => 'custom',
			'fields' => [
				'metadata' => [],
				'annotations' => [],
				'private_settings' => [],
				'attributes' => [],
			],
			'partial_match' => true,
			'tokenize' => false,
			'type' => 'object',
			'subtype' => 'blog',
			'owner_guid' => null,
			'container_guid' => null,
			'pagination' => true,
			'sort_by' => [
				[
					'property_type' => 'attribute',
					'property' => 'time_created',
					'direction' => 'desc',
				],
			],
		], $svc->getParams());
	}

	public function testReturnsTypeSubtypePairs() {

		$search = new Search();

		$pairs = $search->getTypeSubtypePairs();

		$this->assertEquals(elgg_entity_types_with_capability('searchable'), $pairs);
	}

	public function testReturnsSearchType() {

		$search = new Search();

		$types = $search->getSearchTypes();

		$this->assertEquals([], $types);
	}
}
