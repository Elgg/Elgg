<?php

namespace Elgg\Search;

use Elgg\IntegrationTestCase;

/**
 * @group Search
 */
class SearchServiceIntegrationTest extends IntegrationTestCase {

	public function up() {
		elgg_register_plugin_hook_handler('search:fields', 'entities', [$this, 'setupFields']);
	}

	public function down() {
		elgg_unregister_plugin_hook_handler('search:fields', 'entities', [$this, 'setupFields']);
	}

	public function setupFields(\Elgg\Hook $hook) {
		return [
			'metadata' => ['haystack'],
			'annotations' => ['haystack'],
			'private_settings' => ['haystack'],
		];
	}
	/**
	 * @dataProvider searchDataProvider
	 */
	public function testSearch($needle, $haystack, $tokenize, $partial, $count, $entity_type, $property) {
		$entity = $this->createOne($entity_type);

		switch ($property) {
			case 'metadata' :
				$entity->haystack = $haystack;
				$fields = [
					'metadata' => ['haystack']
				];
				break;

			case 'annotation' :
				$entity->annotate('haystack', $haystack, ACCESS_PUBLIC);
				$fields = [
					'annotations' => ['haystack']
				];
				break;

			case 'private_setting' :
				$entity->setPrivateSetting('haystack', $haystack);
				$fields = [
					'private_settings' => ['haystack']
				];
				break;
		}

		$results = elgg_search([
			'query' => $needle,
			'tokenize' => $tokenize,
			'partial_match' => $partial,
			'fields' => $fields,
			'guids' => $entity->guid,
			'count' => true,
		]);

		$this->assertEquals($count, $results);

		$entity->delete();
	}

	public function searchDataProvider() {
		$haystack = 'Lorem ipsum dolor sit amet consectetur adipiscing elit';

		$tests = [
			// [needle, haystack, tokenize, partial, count]
			['dolor', $haystack, true, true, 1],
			['dolor', $haystack, false, true, 1],
			['dolor', $haystack, true, false, 0],
			['dolor', $haystack, false, false, 0],

			['consec', $haystack, true, true, 1],
			['consec', $haystack, false, true, 1],
			['consec', $haystack, true, false, 0],
			['consec', $haystack, false, false, 0],

			['sit amet', $haystack, true, true, 1],
			['sit amet', $haystack, false, true, 1],
			['sit amet', $haystack, true, false, 0],
			['sit amet', $haystack, false, false, 0],

			['sit ipsum', $haystack, true, true, 1],
			['sit ipsum', $haystack, false, true, 0],
			['sit ipsum', $haystack, true, false, 0],
			['sit ipsum', $haystack, false, false, 0],

			['ips consec', $haystack, true, true, 1],
			['ips consec', $haystack, false, true, 0],
			['ips consec', $haystack, true, false, 0],
			['ips consec', $haystack, false, false, 0],
		];

		$provider = [];
		foreach (['user', 'object', 'group'] as $type) {
			foreach (['metadata', 'annotation', 'private_setting'] as $prop) {
				foreach ($tests as $test) {
					$provider[] = array_merge($test, [$type, $prop]);
				}
			}
		}

		return $provider;
	}

}