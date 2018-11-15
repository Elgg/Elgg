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

	public function down() {

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
			'sort' => 'time_created',
			'order' => 'desc',
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
		], $svc->getParams());
	}

	public function testReturnsTypeSubtypePairs() {

		$search = new Search();

		$pairs = $search->getTypeSubtypePairs();

		$this->assertEquals(get_registered_entity_types(), $pairs);
	}

	public function testReturnsSearchType() {

		$search = new Search();

		$types = $search->getSearchTypes();

		$this->assertEquals([], $types);
	}

	public function testCanPrepareEntity() {

		elgg_register_plugin_hook_handler('search:fields', 'object', function (\Elgg\Hook $hook) {
			$value = $hook->getValue();
			$value['metadata'][] = 'foo';

			return $value;
		});

		$lorem = 'Lorem ipsum dolor sit amet consectetur adipiscing elit';
		$lorem_long = 'Lorem ipsum dolor sit amet consectetur adipiscing elit. Aenean vel tempor purus. In dapibus diam ac enim accumsan blandit. Ut sit amet iaculis felis. Donec et porttitor nunc. Fusce tellus nisl, volutpat a maximus vel, tempus ac felis. Ut id lacus varius, faucibus nibh in, consectetur diam. Sed gravida est ac malesuada porta.
		Sed ullamcorper velit non eros aliquet pulvinar. In metus justo, pharetra eu rhoncus sit amet, vehicula ut ipsum. Maecenas eget diam massa. Integer rutrum scelerisque arcu, vitae pellentesque risus faucibus quis. In hac habitasse platea dictumst. Vestibulum placerat purus commodo aliquet maximus. Pellentesque eget feugiat nunc. Sed rutrum dignissim est, quis fringilla eros placerat eu. Mauris dapibus malesuada quam a maximus. Nullam cursus aliquet maximus. Donec sed malesuada velit, vitae pharetra quam. Etiam fringilla nisl ligula, quis facilisis tellus sodales convallis. Etiam efficitur mauris quis sem ultrices venenatis a sit amet augue. Cras sollicitudin ultrices neque, ac vestibulum eros egestas a. Duis porta hendrerit pellentesque. Proin ac erat vestibulum, varius elit a, commodo lorem.';

		$matched_lorem = '<span class="search-highlight search-highlight-color1">Lorem</span> <span class="search-highlight search-highlight-color2">ipsum</span> dolor sit amet consectetur adipiscing elit';

		$entity = $this->createObject([], [
			'title' => $lorem,
			'description' => $lorem_long,
			'foo' => $lorem,
		]);

		// prepare search params
		$search_params = [
			'query' => 'lorem ipsum',
			'type' => 'object',
			'subtype' => $entity->getSubtype(),
			'search_type' => 'custom',
		];
		// normalize params as they get sent to the view in a normalized form
		$search_params = _elgg_services()->search->normalizeOptions($search_params);
		
		$search = new Search($search_params);

		$search->prepareEntity($entity);

		$this->assertEquals($matched_lorem, $entity->getVolatileData('search_matched_title'));
		$this->assertContains($matched_lorem, $entity->getVolatileData('search_matched_description'));
		$this->assertContains($matched_lorem, $entity->getVolatileData('search_matched_extra'));

		$this->assertEquals($entity->time_created, $entity->getVolatileData('search_time'));
		$this->assertEquals(elgg_view_entity_icon($entity, 'small'), $entity->getVolatileData('search_icon'));
		$this->assertEquals($entity->getURL(), $entity->getVolatileData('search_url'));
	}
}
