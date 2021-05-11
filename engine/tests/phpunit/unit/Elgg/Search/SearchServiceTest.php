<?php

namespace Elgg\Search;

use Elgg\Database\Clauses\AnnotationWhereClause;
use Elgg\Database\Clauses\AttributeWhereClause;
use Elgg\Database\Clauses\EntitySortByClause;
use Elgg\Database\Clauses\EntityWhereClause;
use Elgg\Database\Clauses\MetadataWhereClause;
use Elgg\Database\Clauses\PrivateSettingWhereClause;
use Elgg\Database\Select;
use Elgg\Exceptions\InvalidParameterException;
use Elgg\UnitTestCase;

/**
 * @group Search
 */
class SearchServiceTest extends UnitTestCase {

	public function up() {
		_elgg_services()->hooks->backup();
	}

	public function down() {
		_elgg_services()->hooks->restore();
	}

	public function testEmptyReturnWithMissingQueryParts() {

		$options = [
			'query' => '  ',
		];

		$result = elgg_search($options);

		$this->assertFalse($result);
	}

	/**
	 * @todo We need a data provider for XSS vectors => sanitized outputs,
	 *       so we can use them across tests
	 */
	public function testSanitizesSearchQueryAgainstXssAttack() {
		$options = [
			'query' => "'';!--\"<XSS>=&{()}",
		];

		$options = _elgg_services()->search->normalizeOptions($options);

		$this->assertEquals("&#39;&#39;;!--&#34;=&{()}", $options['query']);

		$this->markTestIncomplete();
	}

	public function testStripsTagsFromQuery() {
		$options = [
			'query' => "<span>find</span> me",
		];

		$options = _elgg_services()->search->normalizeOptions($options);

		$this->assertEquals("find me", $options['query']);
		$this->assertEquals(["find", "me"], $options['query_parts']);
	}

	public function testQueryPartsWithTokenizationDisabled() {
		$options = [
			'query' => "<span class=\"find\">find</span> me<br me />",
			'tokenize' => false,
		];

		$options = _elgg_services()->search->normalizeOptions($options);

		$this->assertEquals("find me", $options['query']);
		$this->assertEquals(["find me"], $options['query_parts']);
	}

	public function testCanNormalizeSearchType() {

		$options = [
			'search_type' => '',
		];

		$options = _elgg_services()->search->normalizeOptions($options);

		$this->assertEquals('entities', $options['search_type']);
	}

	public function testThrowsOnInvalidEntityType() {

		$handler = function (\Elgg\Hook $hook) {
			return [
				'metadata' => ['allowed1', 'allowed2'],
				'annotations' => ['allowed3', 'allowed4'],
				'attributes' => false,
				'private_settings' => null,
			];
		};

		elgg_register_plugin_hook_handler('search:fields', 'foo', $handler);

		$options = [
			'type' => 'foo',
			'query' => 'bar',
		];

		$this->expectException(InvalidParameterException::class);
		_elgg_services()->search->search($options);
	}
	
	public function testFalseInvalidEntityType() {

		$handler = function (\Elgg\Hook $hook) {
			return [
				'metadata' => ['allowed1', 'allowed2'],
				'annotations' => ['allowed3', 'allowed4'],
				'attributes' => false,
				'private_settings' => null,
			];
		};

		elgg_register_plugin_hook_handler('search:fields', 'foo', $handler);

		$options = [
			'type' => 'foo',
			'query' => 'bar',
		];

		$this->assertFalse(elgg_search($options));
	}

	public function testCanFilterParamsWithAHook() {

		$handler = function (\Elgg\Hook $hook) {
			return [
				'query' => 'altered query',
			];
		};

		elgg_register_plugin_hook_handler('search:params', 'entities', $handler);

		$options = _elgg_services()->search->normalizeOptions([]);

		$this->assertEquals([
			'query' => 'altered query',
			'fields' => [
				'metadata' => [],
				'attributes' => [],
				'annotations' => [],
				'private_settings' => [],
			],
			'query_parts' => ['altered', 'query'],
			'_elgg_search_service_normalize_options' => true,
		], $options);
	}

	public function testCanRegisterAndNormalizeFields() {

		$handler = function (\Elgg\Hook $hook) {
			return [
				'metadata' => ['allowed1', 'allowed2'],
				'annotations' => ['allowed3', 'allowed4'],
				'attributes' => false,
				'private_settings' => null,
			];
		};

		elgg_register_plugin_hook_handler('search:fields', 'object', $handler);

		$options = _elgg_services()->search->normalizeOptions([
			'type' => 'object',
			'fields' => [
				'metadata' => ['unknown1', 'allowed2'],
			]
		]);

		$this->assertEquals([
			'type' => 'object',
			'search_type' => 'entities',
			'fields' => [
				'metadata' => ['allowed2'],
				'attributes' => [],
				'annotations' => [],
				'private_settings' => [],
			],
			'query' => '',
			'query_parts' => [],
			'_elgg_search_service_normalize_options' => true,
		], $options);
	}

	public function testCanSearchWithEmptyFields() {
		// We don't have any allowed fields defined via
		$this->assertFalse(elgg_search([
			'query' => 'hello',
		]));

		$handler = function (\Elgg\Hook $hook) {
			return [
				'metadata' => ['allowed1', 'allowed2'],
				'annotations' => ['allowed3', 'allowed4'],
				'attributes' => false,
				'private_settings' => null,
			];
		};

		elgg_register_plugin_hook_handler('search:fields', 'object:blog', $handler);

		$options = _elgg_services()->search->normalizeOptions([
			'type' => 'object',
			'subtype' => 'blog',
		]);

		$this->assertEquals([
			'type' => 'object',
			'subtype' => 'blog',
			'search_type' => 'entities',
			'fields' => [
				'metadata' => ['allowed1', 'allowed2'],
				'annotations' => ['allowed3', 'allowed4'],
				'attributes' => [],
				'private_settings' => [],
			],
			'query' => '',
			'query_parts' => [],
			'_elgg_search_service_normalize_options' => true,
		], $options);
	}

	public function testIgnoresSearchWithEmptyFields() {
		// We don't have any allowed fields defined via
		$this->assertFalse(elgg_search([
			'query' => 'hello',
		]));
	}

	public function testCanPrepareSortOptions() {

		$options = _elgg_services()->search->prepareSearchOptions([
			'sort' => 'prop',
			'order' => 'desc',
		]);

		$sort = array_shift($options['order_by']);
		/* @var $sort EntitySortByClause */

		$this->assertInstanceOf(EntitySortByClause::class, $sort);

		$this->assertEquals('prop', $sort->property);
		$this->assertEquals('desc', strtolower($sort->direction));

	}

	public function testCanPrepareSortOptionsFromArray() {

		$options = _elgg_services()->search->prepareSearchOptions([
			'sort' => [
				'property' => 'prop',
				'property_type' => 'annotation',
				'direction' => 'desc',
				'signed' => true,
			]
		]);

		$sort = array_shift($options['order_by']);
		/* @var $sort EntitySortByClause */

		$this->assertInstanceOf(EntitySortByClause::class, $sort);

		$this->assertEquals('prop', $sort->property);
		$this->assertEquals('desc', strtolower($sort->direction));
		$this->assertEquals('annotation', $sort->property_type);
		$this->assertEquals(true, $sort->signed);

	}

	public function testCanSortDefaultsToTimeCreated() {

		$options = _elgg_services()->search->prepareSearchOptions([]);

		$sort = array_shift($options['order_by']);
		/* @var $sort EntitySortByClause */

		$this->assertInstanceOf(EntitySortByClause::class, $sort);

		$this->assertEquals('time_created', $sort->property);
		$this->assertEquals('attribute', $sort->property_type);
		$this->assertEquals('desc', strtolower($sort->direction));

	}


	public function testEndToEndSearchForAnnotationsWithExactMatchAndWithoutTokenization() {

		elgg_register_plugin_hook_handler('search:fields', 'object', function (\Elgg\Hook $hook) {
			$value = $hook->getValue();
			$value['annotations'][] = 'foo1';

			return $value;
		});

		elgg_register_plugin_hook_handler('search:fields', 'object:blog', function (\Elgg\Hook $hook) {
			$value = $hook->getValue();
			$value['annotations'][] = 'foo2';

			return $value;
		});

		elgg_register_plugin_hook_handler('search:fields', 'custom', function (\Elgg\Hook $hook) {
			$value = $hook->getValue();
			$value['annotations'][] = 'foo3';

			return $value;
		});

		$select = Select::fromTable('entities', 'e');
		$select->select('DISTINCT e.*');

		$alias = $select->joinAnnotationTable('e', 'guid', ['foo1', 'foo2', 'foo3'], 'left');

		$property = new AnnotationWhereClause();
		$property->values = 'query1 query2 query3';
		$property->comparison = "LIKE";
		$property->case_sensitive = false;

		$select->andWhere($property->prepare($select, $alias));

		$where = new EntityWhereClause();
		$where->type_subtype_pairs = [
			'object' => ['blog'],
		];
		$select->addClause($where);

		$select->setMaxResults(10);
		$select->setFirstResult(0);

		$select->orderBy('e.time_created', 'desc');

		$rows = $this->getRows(5);
		$spec = _elgg_services()->db->addQuerySpec([
			'sql' => $select->getSQL(),
			'params' => $select->getParameters(),
			'results' => $rows,
		]);

		$options = [
			'callback' => false,
			'query' => 'query1 query2 query3',
			'type' => 'object',
			'subtype' => 'blog',
			'search_type' => 'custom',
			'tokenize' => false,
			'partial_match' => false,
			'fields' => [
				'annotations' => ['foo1', 'foo2', 'foo3', 'foo4'],
			],
		];

		$find = elgg_search($options);

		$this->assertEquals($rows, $find);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testEndToEndSearchForAnnotationsWithExactMatchAndTokenization() {

		elgg_register_plugin_hook_handler('search:fields', 'object', function (\Elgg\Hook $hook) {
			$value = $hook->getValue();
			$value['annotations'][] = 'foo1';

			return $value;
		});

		elgg_register_plugin_hook_handler('search:fields', 'object:blog', function (\Elgg\Hook $hook) {
			$value = $hook->getValue();
			$value['annotations'][] = 'foo2';

			return $value;
		});

		elgg_register_plugin_hook_handler('search:fields', 'custom', function (\Elgg\Hook $hook) {
			$value = $hook->getValue();
			$value['annotations'][] = 'foo3';

			return $value;
		});

		$select = Select::fromTable('entities', 'e');
		$select->select('DISTINCT e.*');

		$alias = $select->joinAnnotationTable('e', 'guid', ['foo1', 'foo2', 'foo3'], 'left');

		$query_parts = ['query1', 'query2', 'query3'];

		$wheres = [];

		foreach ($query_parts as $part) {
			$property = new AnnotationWhereClause();
			$property->values = $part;
			$property->comparison = "LIKE";
			$property->case_sensitive = false;
			$wheres[] = $property->prepare($select, $alias);
		}

		$select->andWhere($select->merge($wheres, 'AND'));

		$where = new EntityWhereClause();
		$where->type_subtype_pairs = [
			'object' => ['blog'],
		];
		$select->addClause($where);

		$select->setMaxResults(10);
		$select->setFirstResult(0);

		$select->orderBy('e.time_created', 'desc');

		$rows = $this->getRows(5);
		$spec = _elgg_services()->db->addQuerySpec([
			'sql' => $select->getSQL(),
			'params' => $select->getParameters(),
			'results' => $rows,
		]);

		$options = [
			'callback' => false,
			'query' => 'query1 query2 query3',
			'type' => 'object',
			'subtype' => 'blog',
			'search_type' => 'custom',
			'tokenize' => true,
			'partial_match' => false,
			'fields' => [
				'annotations' => ['foo1', 'foo2', 'foo3', 'foo4'],
			],
		];

		$find = elgg_search($options);

		$this->assertEquals($rows, $find);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testEndToEndSearchForAnnotationsWithPartialMatchAndTokenization() {

		elgg_register_plugin_hook_handler('search:fields', 'object', function (\Elgg\Hook $hook) {
			$value = $hook->getValue();
			$value['annotations'][] = 'foo1';

			return $value;
		});

		elgg_register_plugin_hook_handler('search:fields', 'object:blog', function (\Elgg\Hook $hook) {
			$value = $hook->getValue();
			$value['annotations'][] = 'foo2';

			return $value;
		});

		elgg_register_plugin_hook_handler('search:fields', 'custom', function (\Elgg\Hook $hook) {
			$value = $hook->getValue();
			$value['annotations'][] = 'foo3';

			return $value;
		});

		$select = Select::fromTable('entities', 'e');
		$select->select('DISTINCT e.*');

		$alias = $select->joinAnnotationTable('e', 'guid', ['foo1', 'foo2', 'foo3'], 'left');

		$query_parts = ['query1', 'query2', 'query3'];

		$wheres = [];

		foreach ($query_parts as $part) {
			$property = new AnnotationWhereClause();
			$property->values = "%{$part}%";
			$property->comparison = "LIKE";
			$property->case_sensitive = false;
			$wheres[] = $property->prepare($select, $alias);
		}

		$select->andWhere($select->merge($wheres, 'AND'));

		$where = new EntityWhereClause();
		$where->type_subtype_pairs = [
			'object' => ['blog'],
		];
		$select->addClause($where);

		$select->setMaxResults(10);
		$select->setFirstResult(0);

		$select->orderBy('e.time_created', 'desc');

		$rows = $this->getRows(5);
		$spec = _elgg_services()->db->addQuerySpec([
			'sql' => $select->getSQL(),
			'params' => $select->getParameters(),
			'results' => $rows,
		]);

		$options = [
			'callback' => false,
			'query' => 'query1 query2 query3',
			'type' => 'object',
			'subtype' => 'blog',
			'search_type' => 'custom',
			'tokenize' => true,
			'partial_match' => true,
			'fields' => [
				'annotations' => ['foo1', 'foo2', 'foo3', 'foo4'],
			],
		];

		$find = elgg_search($options);

		$this->assertEquals($rows, $find);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testEndToEndSearchForMetadataWithExactMatchAndWithoutTokenization() {

		elgg_register_plugin_hook_handler('search:fields', 'object', function (\Elgg\Hook $hook) {
			$value = $hook->getValue();
			$value['metadata'][] = 'foo1';

			return $value;
		});

		elgg_register_plugin_hook_handler('search:fields', 'object:blog', function (\Elgg\Hook $hook) {
			$value = $hook->getValue();
			$value['metadata'][] = 'foo2';

			return $value;
		});

		elgg_register_plugin_hook_handler('search:fields', 'custom', function (\Elgg\Hook $hook) {
			$value = $hook->getValue();
			$value['metadata'][] = 'foo3';

			return $value;
		});

		$select = Select::fromTable('entities', 'e');
		$select->select('DISTINCT e.*');

		$alias = $select->joinMetadataTable('e', 'guid', ['foo1', 'foo2', 'foo3'], 'left');

		$property = new MetadataWhereClause();
		$property->values = 'query1 query2 query3';
		$property->comparison = "LIKE";
		$property->case_sensitive = false;

		$select->andWhere($property->prepare($select, $alias));

		$where = new EntityWhereClause();
		$where->type_subtype_pairs = [
			'object' => ['blog'],
		];
		$select->addClause($where);

		$select->setMaxResults(10);
		$select->setFirstResult(0);

		$select->orderBy('e.time_created', 'desc');

		$rows = $this->getRows(5);
		$spec = _elgg_services()->db->addQuerySpec([
			'sql' => $select->getSQL(),
			'params' => $select->getParameters(),
			'results' => $rows,
		]);

		$options = [
			'callback' => false,
			'query' => 'query1 query2 query3',
			'type' => 'object',
			'subtype' => 'blog',
			'search_type' => 'custom',
			'tokenize' => false,
			'partial_match' => false,
			'fields' => [
				'metadata' => ['foo1', 'foo2', 'foo3', 'foo4'],
			],
		];

		$find = elgg_search($options);

		$this->assertEquals($rows, $find);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testEndToEndSearchForMetadataWithExactMatchAndTokenization() {

		elgg_register_plugin_hook_handler('search:fields', 'object', function (\Elgg\Hook $hook) {
			$value = $hook->getValue();
			$value['metadata'][] = 'foo1';

			return $value;
		});

		elgg_register_plugin_hook_handler('search:fields', 'object:blog', function (\Elgg\Hook $hook) {
			$value = $hook->getValue();
			$value['metadata'][] = 'foo2';

			return $value;
		});

		elgg_register_plugin_hook_handler('search:fields', 'custom', function (\Elgg\Hook $hook) {
			$value = $hook->getValue();
			$value['metadata'][] = 'foo3';

			return $value;
		});

		$select = Select::fromTable('entities', 'e');
		$select->select('DISTINCT e.*');

		$alias = $select->joinMetadataTable('e', 'guid', ['foo1', 'foo2', 'foo3'], 'left');

		$query_parts = ['query1', 'query2', 'query3'];

		$wheres = [];

		foreach ($query_parts as $part) {
			$property = new MetadataWhereClause();
			$property->values = $part;
			$property->comparison = "LIKE";
			$property->case_sensitive = false;
			$wheres[] = $property->prepare($select, $alias);
		}

		$select->andWhere($select->merge($wheres, 'AND'));

		$where = new EntityWhereClause();
		$where->type_subtype_pairs = [
			'object' => ['blog'],
		];
		$select->addClause($where);

		$select->setMaxResults(10);
		$select->setFirstResult(0);

		$select->orderBy('e.time_created', 'desc');

		$rows = $this->getRows(5);
		$spec = _elgg_services()->db->addQuerySpec([
			'sql' => $select->getSQL(),
			'params' => $select->getParameters(),
			'results' => $rows,
		]);

		$options = [
			'callback' => false,
			'query' => 'query1 query2 query3',
			'type' => 'object',
			'subtype' => 'blog',
			'search_type' => 'custom',
			'tokenize' => true,
			'partial_match' => false,
			'fields' => [
				'metadata' => ['foo1', 'foo2', 'foo3', 'foo4'],
			],
		];

		$find = elgg_search($options);

		$this->assertEquals($rows, $find);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testEndToEndSearchForMetadataWithPartialMatchAndTokenization() {

		elgg_register_plugin_hook_handler('search:fields', 'object', function (\Elgg\Hook $hook) {
			$value = $hook->getValue();
			$value['metadata'][] = 'foo1';

			return $value;
		});

		elgg_register_plugin_hook_handler('search:fields', 'object:blog', function (\Elgg\Hook $hook) {
			$value = $hook->getValue();
			$value['metadata'][] = 'foo2';

			return $value;
		});

		elgg_register_plugin_hook_handler('search:fields', 'custom', function (\Elgg\Hook $hook) {
			$value = $hook->getValue();
			$value['metadata'][] = 'foo3';

			return $value;
		});

		$select = Select::fromTable('entities', 'e');
		$select->select('DISTINCT e.*');

		$alias = $select->joinMetadataTable('e', 'guid', ['foo1', 'foo2', 'foo3'], 'left');

		$query_parts = ['query1', 'query2', 'query3'];

		$wheres = [];

		foreach ($query_parts as $part) {
			$property = new MetadataWhereClause();
			$property->values = "%{$part}%";
			$property->comparison = "LIKE";
			$property->case_sensitive = false;
			$wheres[] = $property->prepare($select, $alias);
		}

		$select->andWhere($select->merge($wheres, 'AND'));

		$where = new EntityWhereClause();
		$where->type_subtype_pairs = [
			'object' => ['blog'],
		];
		$select->addClause($where);

		$select->setMaxResults(10);
		$select->setFirstResult(0);

		$select->orderBy('e.time_created', 'desc');

		$rows = $this->getRows(5);
		$spec = _elgg_services()->db->addQuerySpec([
			'sql' => $select->getSQL(),
			'params' => $select->getParameters(),
			'results' => $rows,
		]);

		$options = [
			'callback' => false,
			'query' => 'query1 query2 query3',
			'type' => 'object',
			'subtype' => 'blog',
			'search_type' => 'custom',
			'tokenize' => true,
			'partial_match' => true,
			'fields' => [
				'metadata' => ['foo1', 'foo2', 'foo3', 'foo4'],
			],
		];

		$find = elgg_search($options);

		$this->assertEquals($rows, $find);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testEndToEndSearchForPrivateSettingsWithExactMatchAndWithoutTokenization() {

		elgg_register_plugin_hook_handler('search:fields', 'object', function (\Elgg\Hook $hook) {
			$value = $hook->getValue();
			$value['private_settings'][] = 'foo1';

			return $value;
		});

		elgg_register_plugin_hook_handler('search:fields', 'object:blog', function (\Elgg\Hook $hook) {
			$value = $hook->getValue();
			$value['private_settings'][] = 'foo2';

			return $value;
		});

		elgg_register_plugin_hook_handler('search:fields', 'custom', function (\Elgg\Hook $hook) {
			$value = $hook->getValue();
			$value['private_settings'][] = 'foo3';

			return $value;
		});

		$select = Select::fromTable('entities', 'e');
		$select->select('DISTINCT e.*');

		$alias = $select->joinPrivateSettingsTable('e', 'guid', ['foo1', 'foo2', 'foo3'], 'left');

		$property = new PrivateSettingWhereClause();
		$property->values = 'query1 query2 query3';
		$property->comparison = "LIKE";
		$property->case_sensitive = false;

		$select->andWhere($property->prepare($select, $alias));

		$where = new EntityWhereClause();
		$where->type_subtype_pairs = [
			'object' => ['blog'],
		];
		$select->addClause($where);

		$select->setMaxResults(10);
		$select->setFirstResult(0);

		$select->orderBy('e.time_created', 'desc');

		$rows = $this->getRows(5);
		$spec = _elgg_services()->db->addQuerySpec([
			'sql' => $select->getSQL(),
			'params' => $select->getParameters(),
			'results' => $rows,
		]);

		$options = [
			'callback' => false,
			'query' => 'query1 query2 query3',
			'type' => 'object',
			'subtype' => 'blog',
			'search_type' => 'custom',
			'tokenize' => false,
			'partial_match' => false,
			'fields' => [
				'private_settings' => ['foo1', 'foo2', 'foo3', 'foo4'],
			],
		];

		$find = elgg_search($options);

		$this->assertEquals($rows, $find);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testEndToEndSearchForPrivateSettingsWithExactMatchAndTokenization() {

		elgg_register_plugin_hook_handler('search:fields', 'object', function (\Elgg\Hook $hook) {
			$value = $hook->getValue();
			$value['private_settings'][] = 'foo1';

			return $value;
		});

		elgg_register_plugin_hook_handler('search:fields', 'object:blog', function (\Elgg\Hook $hook) {
			$value = $hook->getValue();
			$value['private_settings'][] = 'foo2';

			return $value;
		});

		elgg_register_plugin_hook_handler('search:fields', 'custom', function (\Elgg\Hook $hook) {
			$value = $hook->getValue();
			$value['private_settings'][] = 'foo3';

			return $value;
		});

		$select = Select::fromTable('entities', 'e');
		$select->select('DISTINCT e.*');

		$alias = $select->joinPrivateSettingsTable('e', 'guid', ['foo1', 'foo2', 'foo3'], 'left');

		$query_parts = ['query1', 'query2', 'query3'];

		$wheres = [];

		foreach ($query_parts as $part) {
			$property = new PrivateSettingWhereClause();
			$property->values = $part;
			$property->comparison = "LIKE";
			$property->case_sensitive = false;
			$wheres[] = $property->prepare($select, $alias);
		}

		$select->andWhere($select->merge($wheres, 'AND'));

		$where = new EntityWhereClause();
		$where->type_subtype_pairs = [
			'object' => ['blog'],
		];
		$select->addClause($where);

		$select->setMaxResults(10);
		$select->setFirstResult(0);

		$select->orderBy('e.time_created', 'desc');

		$rows = $this->getRows(5);
		$spec = _elgg_services()->db->addQuerySpec([
			'sql' => $select->getSQL(),
			'params' => $select->getParameters(),
			'results' => $rows,
		]);

		$options = [
			'callback' => false,
			'query' => 'query1 query2 query3',
			'type' => 'object',
			'subtype' => 'blog',
			'search_type' => 'custom',
			'tokenize' => true,
			'partial_match' => false,
			'fields' => [
				'private_settings' => ['foo1', 'foo2', 'foo3', 'foo4'],
			],
		];

		$find = elgg_search($options);

		$this->assertEquals($rows, $find);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testEndToEndSearchForPrivateSettingsWithPartialMatchAndTokenization() {

		elgg_register_plugin_hook_handler('search:fields', 'object', function (\Elgg\Hook $hook) {
			$value = $hook->getValue();
			$value['private_settings'][] = 'foo1';

			return $value;
		});

		elgg_register_plugin_hook_handler('search:fields', 'object:blog', function (\Elgg\Hook $hook) {
			$value = $hook->getValue();
			$value['private_settings'][] = 'foo2';

			return $value;
		});

		elgg_register_plugin_hook_handler('search:fields', 'custom', function (\Elgg\Hook $hook) {
			$value = $hook->getValue();
			$value['private_settings'][] = 'foo3';

			return $value;
		});

		$select = Select::fromTable('entities', 'e');
		$select->select('DISTINCT e.*');

		$alias = $select->joinPrivateSettingsTable('e', 'guid', ['foo1', 'foo2', 'foo3'], 'left');

		$query_parts = ['query1', 'query2', 'query3'];

		$wheres = [];

		foreach ($query_parts as $part) {
			$property = new PrivateSettingWhereClause();
			$property->values = "%{$part}%";
			$property->comparison = "LIKE";
			$property->case_sensitive = false;
			$wheres[] = $property->prepare($select, $alias);
		}

		$select->andWhere($select->merge($wheres, 'AND'));

		$where = new EntityWhereClause();
		$where->type_subtype_pairs = [
			'object' => ['blog'],
		];
		$select->addClause($where);

		$select->setMaxResults(10);
		$select->setFirstResult(0);

		$select->orderBy('e.time_created', 'desc');

		$rows = $this->getRows(5);
		$spec = _elgg_services()->db->addQuerySpec([
			'sql' => $select->getSQL(),
			'params' => $select->getParameters(),
			'results' => $rows,
		]);

		$options = [
			'callback' => false,
			'query' => 'query1 query2 query3',
			'type' => 'object',
			'subtype' => 'blog',
			'search_type' => 'custom',
			'tokenize' => true,
			'partial_match' => true,
			'fields' => [
				'private_settings' => ['foo1', 'foo2', 'foo3', 'foo4'],
			],
		];

		$find = elgg_search($options);

		$this->assertEquals($rows, $find);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testEndToEndSearchWithMultipleProperties() {

		elgg_register_plugin_hook_handler('search:fields', 'custom', function (\Elgg\Hook $hook) {
			return [
				'attributes' => ['type', 'subtype'],
				'metadata' => ['foo1'],
				'annotations' => ['foo2'],
				'private_settings' => ['foo3'],
			];
		});


		$select = Select::fromTable('entities', 'e');
		$select->select('DISTINCT e.*');

		$query_parts = ['query1', 'query2', 'query3'];

		$ors = [];

		foreach (['type', 'subtype'] as $attr) {
			$wheres = [];
			foreach ($query_parts as $part) {
				$attribute = new AttributeWhereClause();
				$attribute->names = $attr;
				$attribute->values = "%{$part}%";
				$attribute->comparison = "LIKE";
				$attribute->case_sensitive = false;
				$wheres[] = $attribute->prepare($select, 'e');
			}
			$ors[] = $select->merge($wheres, 'AND');
		}

		$md_alias = $select->joinMetadataTable('e', 'guid', ['foo1'], 'left');
		$wheres = [];
		foreach ($query_parts as $part) {
			$metadata = new MetadataWhereClause();
			$metadata->values = "%{$part}%";
			$metadata->comparison = "LIKE";
			$metadata->case_sensitive = false;
			$wheres[] = $metadata->prepare($select, $md_alias);
		}
		$ors[] = $select->merge($wheres, 'AND');

		$an_alias = $select->joinAnnotationTable('e', 'guid', ['foo2'], 'left');
		$wheres = [];
		foreach ($query_parts as $part) {
			$annotation = new AnnotationWhereClause();
			$annotation->values = "%{$part}%";
			$annotation->comparison = "LIKE";
			$annotation->case_sensitive = false;
			$wheres[] = $annotation->prepare($select, $an_alias);
		}
		$ors[] = $select->merge($wheres, 'AND');

		$ps_alias = $select->joinPrivateSettingsTable('e', 'guid', ['foo3'], 'left');
		$wheres = [];
		foreach ($query_parts as $part) {
			$private_setting = new PrivateSettingWhereClause();
			$private_setting->values = "%{$part}%";
			$private_setting->comparison = "LIKE";
			$private_setting->case_sensitive = false;
			$wheres[] = $private_setting->prepare($select, $ps_alias);
		}
		$ors[] = $select->merge($wheres, 'AND');

		$select->andWhere($select->merge($ors, 'OR'));

		$where = new EntityWhereClause();
		$where->type_subtype_pairs = [
			'object' => ['blog'],
		];
		$select->addClause($where);

		$select->setMaxResults(10);
		$select->setFirstResult(0);

		$select->orderBy('e.time_created', 'desc');

		$rows = $this->getRows(5);
		$spec = _elgg_services()->db->addQuerySpec([
			'sql' => $select->getSQL(),
			'params' => $select->getParameters(),
			'results' => $rows,
		]);

		$options = [
			'callback' => false,
			'query' => 'query1 query2 query3',
			'type' => 'object',
			'subtype' => 'blog',
			'search_type' => 'custom',
			'tokenize' => true,
			'partial_match' => true,
			'fields' => [
				'attributes' => ['type', 'subtype'],
				'metadata' => ['foo1', 'bar1'],
				'annotations' => ['foo2', 'bar2'],
				'private_settings' => ['foo3', 'bar3'],
				'whatever' => ['a', 'b', 'c'],
			],
		];

		$find = elgg_search($options);

		$this->assertEquals($rows, $find);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testCanAlterOptions() {

		elgg_register_plugin_hook_handler('search:fields', 'custom', function (\Elgg\Hook $hook) {
			return [
				'attributes' => ['type', 'subtype'],
				'metadata' => ['foo1'],
				'annotations' => ['foo2'],
				'private_settings' => ['foo3'],
			];
		});

		$calls = 0;

		$handler = function (\Elgg\Hook $hook) use (&$calls) {
			$calls++;
		};

		elgg_register_plugin_hook_handler('search:options', 'object', $handler);
		elgg_register_plugin_hook_handler('search:options', 'object:blog', $handler);
		elgg_register_plugin_hook_handler('search:options', 'custom', $handler);

		$options = [
			'query' => 'query1 query2 query3',
			'type' => 'object',
			'subtype' => 'blog',
			'search_type' => 'custom',
		];

		elgg_search($options);

		$this->assertEquals(3, $calls);
	}

	public function testCanUseCustomResultsProvider() {

		elgg_register_plugin_hook_handler('search:fields', 'custom', function (\Elgg\Hook $hook) {
			return [
				'attributes' => ['type', 'subtype'],
				'metadata' => ['foo1'],
				'annotations' => ['foo2'],
				'private_settings' => ['foo3'],
			];
		});

		$expected = $this->getRows(2);

		$handler = function (\Elgg\Hook $hook) use ($expected) {
			return $expected;
		};

		elgg_register_plugin_hook_handler('search:results', 'custom', $handler);

		$options = [
			'query' => 'query1 query2 query3',
			'type' => 'object',
			'subtype' => 'blog',
			'search_type' => 'custom',
		];

		$results = elgg_search($options);

		$this->assertEquals($expected, $results);
	}

	public function testRegisteredUserFields() {
		elgg_register_plugin_hook_handler('search:fields', 'user', \Elgg\Search\UserSearchFieldsHandler::class);

		$options = _elgg_services()->search->normalizeOptions([
			'type' => 'user',
		]);

		$this->assertEquals(['username', 'name', 'description'], $options['fields']['metadata']);
		
		$fields = elgg()->fields->get('user', 'user');
		$profile_fields = [];
		foreach ($fields as $field) {
			$profile_fields[] = "profile:{$field['name']}";
		}
		
		$this->assertEquals($profile_fields, $options['fields']['annotations']);

	}

	public function testRegisteredGroupFields() {
		elgg_register_plugin_hook_handler('search:fields', 'group', \Elgg\Search\GroupSearchFieldsHandler::class);

		$options = _elgg_services()->search->normalizeOptions([
			'type' => 'group',
		]);

		$this->assertEquals(['name', 'description'], $options['fields']['metadata']);
	}

	public function testRegisteredObjectFields() {
		elgg_register_plugin_hook_handler('search:fields', 'object', \Elgg\Search\ObjectSearchFieldsHandler::class);

		$options = _elgg_services()->search->normalizeOptions([
			'type' => 'object',
		]);

		$this->assertEquals(['title', 'description'], $options['fields']['metadata']);
	}

	public function getRows($limit = 10) {
		$rows = [];
		for ($i = 0; $i < $limit; $i++) {
			$row = (object) [
				'guid' => $i,
				'owner_guid' => rand(100, 999),
				'container_guid' => rand(100, 999),
				'enabled' => 'yes',
				'type' => 'object',
				'subtype' => 'foo',
				'access_id' => ACCESS_PUBLIC,
				'time_created' => time(),
				'time_updated' => null,
				'last_action' => null,
			];
			$rows[] = $row;
		}

		return $rows;
	}

}
