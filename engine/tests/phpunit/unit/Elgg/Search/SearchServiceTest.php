<?php

namespace Elgg\Search;

use Elgg\Database\Clauses\AnnotationWhereClause;
use Elgg\Database\Clauses\AttributeWhereClause;
use Elgg\Database\Clauses\EntityWhereClause;
use Elgg\Database\Clauses\MetadataWhereClause;
use Elgg\Database\EntityTable;
use Elgg\Database\Select;
use Elgg\Exceptions\DomainException;
use Elgg\UnitTestCase;

class SearchServiceTest extends UnitTestCase {

	public function up() {
		_elgg_services()->events->backup();
		_elgg_services()->logger->disable();
	}

	public function down() {
		_elgg_services()->events->restore();
		_elgg_services()->logger->enable();
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

		$this->assertEquals("&#039;&#039;;!--&quot;=&amp;{()}", $options['query']);

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
		$handler = function (\Elgg\Event $event) {
			return [
				'metadata' => ['allowed1', 'allowed2'],
				'annotations' => ['allowed3', 'allowed4'],
				'attributes' => false,
			];
		};

		elgg_register_event_handler('search:fields', 'foo', $handler);

		$options = [
			'type' => 'foo',
			'query' => 'bar',
		];

		$this->expectException(DomainException::class);
		_elgg_services()->search->search($options);
	}
	
	public function testFalseInvalidEntityType() {
		$handler = function (\Elgg\Event $event) {
			return [
				'metadata' => ['allowed1', 'allowed2'],
				'annotations' => ['allowed3', 'allowed4'],
				'attributes' => false,
			];
		};

		elgg_register_event_handler('search:fields', 'foo', $handler);

		$options = [
			'type' => 'foo',
			'query' => 'bar',
		];

		$this->assertFalse(elgg_search($options));
	}

	public function testCanFilterParamsWithEvent() {
		$handler = function (\Elgg\Event $event) {
			return [
				'query' => 'altered query',
			];
		};

		elgg_register_event_handler('search:params', 'entities', $handler);

		$options = _elgg_services()->search->normalizeOptions([]);

		$this->assertEquals([
			'query' => 'altered query',
			'fields' => [
				'metadata' => [],
				'attributes' => [],
				'annotations' => [],
			],
			'query_parts' => ['altered', 'query'],
			'_elgg_search_service_normalize_options' => true,
		], $options);
	}

	public function testCanRegisterAndNormalizeFields() {
		$handler = function (\Elgg\Event $event) {
			return [
				'metadata' => ['allowed1', 'allowed2'],
				'annotations' => ['allowed3', 'allowed4'],
				'attributes' => false,
			];
		};

		elgg_register_event_handler('search:fields', 'object', $handler);

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

		$handler = function (\Elgg\Event $event) {
			return [
				'metadata' => ['allowed1', 'allowed2'],
				'annotations' => ['allowed3', 'allowed4'],
				'attributes' => false,
			];
		};

		elgg_register_event_handler('search:fields', 'object:blog', $handler);

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

	public function testEndToEndSearchForAnnotationsWithExactMatchAndWithoutTokenization() {
		elgg_register_event_handler('search:fields', 'object', function (\Elgg\Event $event) {
			$value = $event->getValue();
			$value['annotations'][] = 'foo1';

			return $value;
		});

		elgg_register_event_handler('search:fields', 'object:blog', function (\Elgg\Event $event) {
			$value = $event->getValue();
			$value['annotations'][] = 'foo2';

			return $value;
		});

		elgg_register_event_handler('search:fields', 'custom', function (\Elgg\Event $event) {
			$value = $event->getValue();
			$value['annotations'][] = 'foo3';

			return $value;
		});

		$select = Select::fromTable(EntityTable::TABLE_NAME, EntityTable::DEFAULT_JOIN_ALIAS);
		$select->select("DISTINCT {$select->getTableAlias()}.*");

		$alias = $select->joinAnnotationTable($select->getTableAlias(), 'guid', ['foo1', 'foo2', 'foo3'], 'left');

		$wheres = [];
		
		$property = new AnnotationWhereClause();
		$property->values = 'query1 query2 query3';
		$property->comparison = 'LIKE';
		$property->case_sensitive = false;

		$wheres[] = $property->prepare($select, $alias);

		$where = new EntityWhereClause();
		$where->type_subtype_pairs = [
			'object' => ['blog'],
		];
		
		$wheres[] = $where->prepare($select, EntityTable::DEFAULT_JOIN_ALIAS);
		
		$select->andWhere($select->merge($wheres));

		$select->setMaxResults(10);
		$select->setFirstResult(0);

		$select->addOrderBy("{$select->getTableAlias()}.time_created", 'desc');
		$select->addOrderBy("{$select->getTableAlias()}.guid", 'desc');

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
		elgg_register_event_handler('search:fields', 'object', function (\Elgg\Event $event) {
			$value = $event->getValue();
			$value['annotations'][] = 'foo1';

			return $value;
		});

		elgg_register_event_handler('search:fields', 'object:blog', function (\Elgg\Event $event) {
			$value = $event->getValue();
			$value['annotations'][] = 'foo2';

			return $value;
		});

		elgg_register_event_handler('search:fields', 'custom', function (\Elgg\Event $event) {
			$value = $event->getValue();
			$value['annotations'][] = 'foo3';

			return $value;
		});

		$select = Select::fromTable(EntityTable::TABLE_NAME, EntityTable::DEFAULT_JOIN_ALIAS);
		$select->select("DISTINCT {$select->getTableAlias()}.*");

		$alias = $select->joinAnnotationTable($select->getTableAlias(), 'guid', ['foo1', 'foo2', 'foo3'], 'left');

		$query_parts = ['query1', 'query2', 'query3'];

		$wheres = [];
		$q_wheres = [];

		foreach ($query_parts as $part) {
			$property = new AnnotationWhereClause();
			$property->values = $part;
			$property->comparison = 'LIKE';
			$property->case_sensitive = false;
			$q_wheres[] = $property->prepare($select, $alias);
		}

		$wheres[] = $select->merge($q_wheres);
		
		$where = new EntityWhereClause();
		$where->type_subtype_pairs = [
			'object' => ['blog'],
		];
		
		$wheres[] = $where->prepare($select, EntityTable::DEFAULT_JOIN_ALIAS);
		
		$select->andWhere($select->merge($wheres));
		
		$select->setMaxResults(10);
		$select->setFirstResult(0);

		$select->addOrderBy("{$select->getTableAlias()}.time_created", 'desc');
		$select->addOrderBy("{$select->getTableAlias()}.guid", 'desc');

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
		elgg_register_event_handler('search:fields', 'object', function (\Elgg\Event $event) {
			$value = $event->getValue();
			$value['annotations'][] = 'foo1';

			return $value;
		});

		elgg_register_event_handler('search:fields', 'object:blog', function (\Elgg\Event $event) {
			$value = $event->getValue();
			$value['annotations'][] = 'foo2';

			return $value;
		});

		elgg_register_event_handler('search:fields', 'custom', function (\Elgg\Event $event) {
			$value = $event->getValue();
			$value['annotations'][] = 'foo3';

			return $value;
		});

		$select = Select::fromTable(EntityTable::TABLE_NAME, EntityTable::DEFAULT_JOIN_ALIAS);
		$select->select("DISTINCT {$select->getTableAlias()}.*");

		$alias = $select->joinAnnotationTable($select->getTableAlias(), 'guid', ['foo1', 'foo2', 'foo3'], 'left');

		$query_parts = ['query1', 'query2', 'query3'];

		$wheres = [];
		$q_wheres = [];

		foreach ($query_parts as $part) {
			$property = new AnnotationWhereClause();
			$property->values = "%{$part}%";
			$property->comparison = 'LIKE';
			$property->case_sensitive = false;
			$q_wheres[] = $property->prepare($select, $alias);
		}

		$wheres[] = $select->merge($q_wheres);

		$where = new EntityWhereClause();
		$where->type_subtype_pairs = [
			'object' => ['blog'],
		];
		$wheres[] = $where->prepare($select, EntityTable::DEFAULT_JOIN_ALIAS);
		
		$select->andWhere($select->merge($wheres));

		$select->setMaxResults(10);
		$select->setFirstResult(0);

		$select->addOrderBy("{$select->getTableAlias()}.time_created", 'desc');
		$select->addOrderBy("{$select->getTableAlias()}.guid", 'desc');

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
		elgg_register_event_handler('search:fields', 'object', function (\Elgg\Event $event) {
			$value = $event->getValue();
			$value['metadata'][] = 'foo1';

			return $value;
		});

		elgg_register_event_handler('search:fields', 'object:blog', function (\Elgg\Event $event) {
			$value = $event->getValue();
			$value['metadata'][] = 'foo2';

			return $value;
		});

		elgg_register_event_handler('search:fields', 'custom', function (\Elgg\Event $event) {
			$value = $event->getValue();
			$value['metadata'][] = 'foo3';

			return $value;
		});

		$select = Select::fromTable(EntityTable::TABLE_NAME, EntityTable::DEFAULT_JOIN_ALIAS);
		$select->select("DISTINCT {$select->getTableAlias()}.*");

		$alias = $select->joinMetadataTable($select->getTableAlias(), 'guid', ['foo1', 'foo2', 'foo3'], 'left');

		$property = new MetadataWhereClause();
		$property->values = 'query1 query2 query3';
		$property->comparison = 'LIKE';
		$property->case_sensitive = false;

		$select->andWhere($property->prepare($select, $alias));

		$where = new EntityWhereClause();
		$where->type_subtype_pairs = [
			'object' => ['blog'],
		];
		$select->addClause($where);

		$select->setMaxResults(10);
		$select->setFirstResult(0);

		$select->addOrderBy("{$select->getTableAlias()}.time_created", 'desc');
		$select->addOrderBy("{$select->getTableAlias()}.guid", 'desc');

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
		elgg_register_event_handler('search:fields', 'object', function (\Elgg\Event $event) {
			$value = $event->getValue();
			$value['metadata'][] = 'foo1';

			return $value;
		});

		elgg_register_event_handler('search:fields', 'object:blog', function (\Elgg\Event $event) {
			$value = $event->getValue();
			$value['metadata'][] = 'foo2';

			return $value;
		});

		elgg_register_event_handler('search:fields', 'custom', function (\Elgg\Event $event) {
			$value = $event->getValue();
			$value['metadata'][] = 'foo3';

			return $value;
		});

		$select = Select::fromTable(EntityTable::TABLE_NAME, EntityTable::DEFAULT_JOIN_ALIAS);
		$select->select("DISTINCT {$select->getTableAlias()}.*");

		$alias = $select->joinMetadataTable($select->getTableAlias(), 'guid', ['foo1', 'foo2', 'foo3'], 'left');

		$query_parts = ['query1', 'query2', 'query3'];

		$wheres = [];
		$q_wheres = [];

		foreach ($query_parts as $part) {
			$property = new MetadataWhereClause();
			$property->values = $part;
			$property->comparison = 'LIKE';
			$property->case_sensitive = false;
			$q_wheres[] = $property->prepare($select, $alias);
		}

		$wheres[] = $select->merge($q_wheres);

		$where = new EntityWhereClause();
		$where->type_subtype_pairs = [
			'object' => ['blog'],
		];
		
		$wheres[] = $where->prepare($select, EntityTable::DEFAULT_JOIN_ALIAS);
		
		$select->andWhere($select->merge($wheres));

		$select->setMaxResults(10);
		$select->setFirstResult(0);

		$select->addOrderBy("{$select->getTableAlias()}.time_created", 'desc');
		$select->addOrderBy("{$select->getTableAlias()}.guid", 'desc');

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
		elgg_register_event_handler('search:fields', 'object', function (\Elgg\Event $event) {
			$value = $event->getValue();
			$value['metadata'][] = 'foo1';

			return $value;
		});

		elgg_register_event_handler('search:fields', 'object:blog', function (\Elgg\Event $event) {
			$value = $event->getValue();
			$value['metadata'][] = 'foo2';

			return $value;
		});

		elgg_register_event_handler('search:fields', 'custom', function (\Elgg\Event $event) {
			$value = $event->getValue();
			$value['metadata'][] = 'foo3';

			return $value;
		});

		$select = Select::fromTable(EntityTable::TABLE_NAME, EntityTable::DEFAULT_JOIN_ALIAS);
		$select->select("DISTINCT {$select->getTableAlias()}.*");

		$alias = $select->joinMetadataTable($select->getTableAlias(), 'guid', ['foo1', 'foo2', 'foo3'], 'left');

		$query_parts = ['query1', 'query2', 'query3'];

		$wheres = [];
		$q_wheres = [];

		foreach ($query_parts as $part) {
			$property = new MetadataWhereClause();
			$property->values = "%{$part}%";
			$property->comparison = 'LIKE';
			$property->case_sensitive = false;
			$q_wheres[] = $property->prepare($select, $alias);
		}

		$wheres[] = $select->merge($q_wheres);

		$where = new EntityWhereClause();
		$where->type_subtype_pairs = [
			'object' => ['blog'],
		];
		
		$wheres[] = $where->prepare($select, EntityTable::DEFAULT_JOIN_ALIAS);
		
		$select->andWhere($select->merge($wheres));

		$select->setMaxResults(10);
		$select->setFirstResult(0);

		$select->addOrderBy("{$select->getTableAlias()}.time_created", 'desc');
		$select->addOrderBy("{$select->getTableAlias()}.guid", 'desc');

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

	public function testEndToEndSearchWithMultipleProperties() {
		elgg_register_event_handler('search:fields', 'custom', function (\Elgg\Event $event) {
			return [
				'attributes' => ['type', 'subtype'],
				'metadata' => ['foo1'],
				'annotations' => ['foo2'],
			];
		});

		$select = Select::fromTable(EntityTable::TABLE_NAME, EntityTable::DEFAULT_JOIN_ALIAS);
		$select->select("DISTINCT {$select->getTableAlias()}.*");

		$query_parts = ['query1', 'query2', 'query3'];

		$ors = [];

		foreach (['type', 'subtype'] as $attr) {
			$wheres = [];
			foreach ($query_parts as $part) {
				$attribute = new AttributeWhereClause();
				$attribute->names = $attr;
				$attribute->values = "%{$part}%";
				$attribute->comparison = 'LIKE';
				$attribute->case_sensitive = false;
				$wheres[] = $attribute->prepare($select, $select->getTableAlias());
			}
			$ors[] = $select->merge($wheres, 'AND');
		}

		$md_alias = $select->joinMetadataTable($select->getTableAlias(), 'guid', ['foo1'], 'left');
		$wheres = [];
		foreach ($query_parts as $part) {
			$metadata = new MetadataWhereClause();
			$metadata->values = "%{$part}%";
			$metadata->comparison = 'LIKE';
			$metadata->case_sensitive = false;
			$wheres[] = $metadata->prepare($select, $md_alias);
		}
		$ors[] = $select->merge($wheres, 'AND');

		$an_alias = $select->joinAnnotationTable($select->getTableAlias(), 'guid', ['foo2'], 'left');
		$wheres = [];
		foreach ($query_parts as $part) {
			$annotation = new AnnotationWhereClause();
			$annotation->values = "%{$part}%";
			$annotation->comparison = 'LIKE';
			$annotation->case_sensitive = false;
			$wheres[] = $annotation->prepare($select, $an_alias);
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

		$select->addOrderBy("{$select->getTableAlias()}.time_created", 'desc');
		$select->addOrderBy("{$select->getTableAlias()}.guid", 'desc');

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
				'whatever' => ['a', 'b', 'c'],
			],
		];

		$find = elgg_search($options);

		$this->assertEquals($rows, $find);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testCanAlterOptions() {
		elgg_register_event_handler('search:fields', 'custom', function (\Elgg\Event $event) {
			return [
				'attributes' => ['type', 'subtype'],
				'metadata' => ['foo1'],
				'annotations' => ['foo2'],
			];
		});

		$calls = 0;

		$handler = function (\Elgg\Event $event) use (&$calls) {
			$calls++;
		};

		elgg_register_event_handler('search:options', 'object', $handler);
		elgg_register_event_handler('search:options', 'object:blog', $handler);
		elgg_register_event_handler('search:options', 'custom', $handler);

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
		elgg_register_event_handler('search:fields', 'custom', function (\Elgg\Event $event) {
			return [
				'attributes' => ['type', 'subtype'],
				'metadata' => ['foo1'],
				'annotations' => ['foo2'],
			];
		});

		$expected = $this->getRows(2);

		$handler = function (\Elgg\Event $event) use ($expected) {
			return $expected;
		};

		elgg_register_event_handler('search:results', 'custom', $handler);

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
		elgg_register_event_handler('search:fields', 'user', \Elgg\Search\UserSearchFieldsHandler::class);

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
		elgg_register_event_handler('search:fields', 'group', \Elgg\Search\GroupSearchFieldsHandler::class);

		$options = _elgg_services()->search->normalizeOptions([
			'type' => 'group',
		]);

		$this->assertEquals(['name', 'description'], $options['fields']['metadata']);
	}

	public function testRegisteredObjectFields() {
		elgg_register_event_handler('search:fields', 'object', \Elgg\Search\ObjectSearchFieldsHandler::class);

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
