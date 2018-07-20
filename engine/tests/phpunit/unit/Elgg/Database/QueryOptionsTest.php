<?php
/**
 *
 */

namespace Elgg\Database;


use Elgg\Database\Clauses\AnnotationWhereClause;
use Elgg\Database\Clauses\AttributeWhereClause;
use Elgg\Database\Clauses\EntitySortByClause;
use Elgg\Database\Clauses\GroupByClause;
use Elgg\Database\Clauses\HavingClause;
use Elgg\Database\Clauses\JoinClause;
use Elgg\Database\Clauses\MetadataWhereClause;
use Elgg\Database\Clauses\OrderByClause;
use Elgg\Database\Clauses\PrivateSettingWhereClause;
use Elgg\Database\Clauses\RelationshipWhereClause;
use Elgg\Database\Clauses\SelectClause;
use Elgg\Database\Clauses\WhereClause;
use Elgg\UnitTestCase;

/**
 * @group QueryBuilder
 */
class QueryOptionsTest extends UnitTestCase {

	/**
	 * @var QueryOptions
	 */
	private $options;

	public function up() {
		$this->options = new QueryOptions([], \ArrayObject::ARRAY_AS_PROPS);
	}

	public function down() {

	}

	public function testCanNormalizeEmptyArray() {
		$options = $this->options->normalizeOptions([]);

		$this->assertNotEmpty($options);
	}

	public function testNormalizesEmptyGuidOptionsFromSingulars() {
		$options = $this->options->normalizeOptions([
			'guid' => '',
			'container_guid' => false,
			'owner_guid' => null,
		]);

		$this->assertEquals([], $options['guids']);
		$this->assertEquals([], $options['container_guids']);
		$this->assertEquals(null, $options['owner_guids']);
	}

	public function testNormalizesZeroGuidOptionsFromSingulars() {
		$options = $this->options->normalizeOptions([
			'guid' => 0,
			'container_guid' => 0,
			'owner_guid' => 0,
		]);

		$this->assertEquals([0], $options['guids']);
		$this->assertEquals([0], $options['container_guids']);
		$this->assertEquals([0], $options['owner_guids']);
	}

	public function testNormalizesEmptyGuidOptions() {
		$options = $this->options->normalizeOptions([
			'guids' => '',
			'container_guids' => false,
			'owner_guids' => null,
		]);

		$this->assertEquals([], $options['guids']);
		$this->assertEquals([], $options['container_guids']);
		$this->assertEquals(null, $options['owner_guids']);
	}

	public function testNormalizesZeroGuidOptions() {
		$options = $this->options->normalizeOptions([
			'guids' => 0,
			'container_guids' => 0,
			'owner_guids' => 0,
		]);

		$this->assertEquals([0], $options['guids']);
		$this->assertEquals([0], $options['container_guids']);
		$this->assertEquals([0], $options['owner_guids']);
	}

	/* ACCESS */
	
	public function testNormalizesAccessOptions() {
		$options = $this->options->normalizeOptions([
			'access_id' => ['1', 2],
		]);

		$this->assertEquals([1, 2], $options['access_ids']);
	}
	
	/* TYPE SUBTYPE PAIRS */

	public function testNormalizesTypeSubtypeOptionsFromTypeSubtypeSingulars() {
		$options = $this->options->normalizeOptions([
			'type' => 'object',
			'subtype' => 'blog',
		]);

		$this->assertEquals([
			'object' => ['blog']
		], $options['type_subtype_pairs']);
	}

	public function testNormalizesTypeSubtypeOptionsFromPairSingulars() {
		$options = $this->options->normalizeOptions([
			'type_subtype_pair' => ['object' => ['blog']],
		]);

		$this->assertEquals([
			'object' => ['blog']
		], $options['type_subtype_pairs']);
	}

	public function testNormalizesTypeSubtypeOptionsFromPairAndNonPairSingulars() {
		$options = $this->options->normalizeOptions([
			'type' => 'group',
			'subtype' => 'community',
			'type_subtype_pair' => ['object' => ['blog']],
		]);

		$this->assertEquals([
			'object' => ['blog'],
		], $options['type_subtype_pairs']);
	}

	public function testNormalizesTypeSubtypeOptionsWithoutSubtype() {
		$options = $this->options->normalizeOptions([
			'type' => 'group',
		]);

		$this->assertEquals([
			'group' => null,
		], $options['type_subtype_pairs']);
	}

	public function testNormalizesTypeSubtypeOptionsFromPairSingularAndPairPlural() {
		$options = $this->options->normalizeOptions([
			'type_subtype_pair' => ['group' => 'community'],
			'type_subtype_pairs' => ['object' => 'blog'],
		]);

		$this->assertEquals([
			'group' => ['community'],
			'object' => ['blog'],
		], $options['type_subtype_pairs']);
	}

	public function testNormalizesTypeSubtypeOptionsFromPairPlural() {
		$options = $this->options->normalizeOptions([
			'type_subtype_pairs' => ['object' => 'blog'],
		]);

		$this->assertEquals([
			'object' => ['blog'],
		], $options['type_subtype_pairs']);
	}

	public function testNormalizesTypeSubtypeOptionsFromInvalidType() {
		_elgg_services()->logger->disable();

		$options = $this->options->normalizeOptions([
			'type_subtype_pairs' => ['invalid' => 'blog'],
		]);

		$this->assertEquals([
			'invalid' => ['blog'],
		], $options['type_subtype_pairs']);

		$messages = _elgg_services()->logger->enable();

		$this->assertNotEmpty($messages);
	}

	/* METADATA PAIRS */
	
	public function testNormalizesMetadataOptionsFromSingulars() {

		$options = $this->options->normalizeOptions([
			'metadata_id' => 5,
			'metadata_name' => 'status',
			'metadata_value' => 'draft',
		]);

		$this->assertEquals(1, count($options['metadata_name_value_pairs']));

		$pair = array_shift($options['metadata_name_value_pairs']);

		$this->assertInstanceOf(MetadataWhereClause::class, $pair);
		$this->assertEquals([5], $pair->ids);
		$this->assertEquals(['status'], $pair->names);
		$this->assertEquals(['draft'], $pair->values);
	}

	public function testNormalizesMetadataOptionsFromPlurals() {

		$options = $this->options->normalizeOptions([
			'metadata_ids' => 5,
			'metadata_names' => 'status',
			'metadata_values' => 'draft',
		]);

		$this->assertEquals(1, count($options['metadata_name_value_pairs']));

		$pair = array_shift($options['metadata_name_value_pairs']);

		$this->assertInstanceOf(MetadataWhereClause::class, $pair);
		$this->assertEquals([5], $pair->ids);
		$this->assertEquals(['status'], $pair->names);
		$this->assertEquals(['draft'], $pair->values);
	}

	public function testNormalizesMetadataOptionsFromTimeOptions() {

		$after = (new \DateTime())->modify('-1 day');
		$before = (new \DateTime());

		$options = $this->options->normalizeOptions([
			'metadata_id' => 5,
			'metadata_name_value_pair' => ['status' => 'draft'],
			'metadata_created_time_lower' => $after,
			'metadata_created_time_upper' => $before,
		]);

		$this->assertEquals(1, count($options['metadata_name_value_pairs']));

		$pair = array_shift($options['metadata_name_value_pairs']);

		$this->assertInstanceOf(MetadataWhereClause::class, $pair);
		$this->assertEquals([5], $pair->ids);
		$this->assertEquals(['status'], $pair->names);
		$this->assertEquals(['draft'], $pair->values);
		$this->assertEquals($after, $pair->created_after);
		$this->assertEquals($before, $pair->created_before);
	}

	/**
	 * @group Nesting
	 */
	public function testNormalizesMetadataOptionsFromTimeOptionsWithNestedPair() {

		$after = (new \DateTime())->modify('-1 day');
		$before = (new \DateTime());

		$options = $this->options->normalizeOptions([
			'metadata_id' => 5,
			'metadata_name_value_pair' => [
				['status' => 'draft']
			],
			'metadata_created_time_lower' => $after,
			'metadata_created_time_upper' => $before,
		]);

		$this->assertEquals(1, count($options['metadata_name_value_pairs']));

		$pair = array_shift($options['metadata_name_value_pairs']);

		$this->assertInstanceOf(MetadataWhereClause::class, $pair);
		$this->assertEquals([5], $pair->ids);
		$this->assertEquals(['status'], $pair->names);
		$this->assertEquals(['draft'], $pair->values);
		$this->assertEquals($after, $pair->created_after);
		$this->assertEquals($before, $pair->created_before);
	}

	public function testNormalizesMetadataOptionsFromTimeOptionsForMultiplePairs() {

		$after = (new \DateTime())->modify('-1 day');
		$before = (new \DateTime());

		$options = $this->options->normalizeOptions([
			'metadata_id' => [5, 6, 7],
			'metadata_name_value_pair' => [
				'status' => 'draft',
				'category' => ['foo', 'bar'],
			],
			'metadata_created_time_lower' => $after,
			'metadata_created_time_upper' => $before,
		]);

		$this->assertEquals(2, count($options['metadata_name_value_pairs']));

		$pair = array_shift($options['metadata_name_value_pairs']);

		$this->assertInstanceOf(MetadataWhereClause::class, $pair);
		$this->assertEquals([5, 6, 7], $pair->ids);
		$this->assertEquals(['status'], $pair->names);
		$this->assertEquals(['draft'], $pair->values);
		$this->assertEquals($after, $pair->created_after);
		$this->assertEquals($before, $pair->created_before);

		$pair = array_shift($options['metadata_name_value_pairs']);

		$this->assertInstanceOf(MetadataWhereClause::class, $pair);
		$this->assertEquals([5, 6, 7], $pair->ids);
		$this->assertEquals(['category'], $pair->names);
		$this->assertEquals(['foo', 'bar'], $pair->values);
		$this->assertEquals($after, $pair->created_after);
		$this->assertEquals($before, $pair->created_before);

	}

	public function testNormalizesMetadataOptionsForMultiplePairs() {

		$after = (new \DateTime())->modify('-1 day');
		$before = (new \DateTime());

		$options = $this->options->normalizeOptions([
			'guid' => 1,
			'metadata_id' => [5, 6, 7],
			'metadata_name_value_pairs' => [
				'status' => 'draft',
				'category' => ['foo', 'bar'],
				[
					'name' => 'priority',
					'value' => ['100', '200'],
					'operand' => '!=',
					'case_sensitive' => true,
				],
				'tags' => "'tag1', 'tag2'",
			],
			'metadata_created_time_lower' => $after,
			'metadata_created_time_upper' => $before,
			'metadata_case_sensitive' => false,
		]);

		$this->assertEquals(4, count($options['metadata_name_value_pairs']));

		$pair = array_shift($options['metadata_name_value_pairs']);

		$this->assertInstanceOf(MetadataWhereClause::class, $pair);
		$this->assertEquals([5, 6, 7], $pair->ids);
		$this->assertEquals(['status'], $pair->names);
		$this->assertEquals(['draft'], $pair->values);
		$this->assertEquals($after, $pair->created_after);
		$this->assertEquals($before, $pair->created_before);
		$this->assertEquals('=', $pair->comparison);
		$this->assertFalse($pair->case_sensitive);
		$this->assertEquals([1], $pair->entity_guids);

		$pair = array_shift($options['metadata_name_value_pairs']);

		$this->assertInstanceOf(MetadataWhereClause::class, $pair);
		$this->assertEquals([5, 6, 7], $pair->ids);
		$this->assertEquals(['category'], $pair->names);
		$this->assertEquals(['foo', 'bar'], $pair->values);
		$this->assertEquals($after, $pair->created_after);
		$this->assertEquals($before, $pair->created_before);
		$this->assertEquals('=', $pair->comparison);
		$this->assertFalse($pair->case_sensitive);
		$this->assertEquals([1], $pair->entity_guids);

		$pair = array_shift($options['metadata_name_value_pairs']);

		$this->assertInstanceOf(MetadataWhereClause::class, $pair);
		$this->assertEquals([5, 6, 7], $pair->ids);
		$this->assertEquals(['priority'], $pair->names);
		$this->assertEquals(['100', '200'], $pair->values);
		$this->assertEquals($after, $pair->created_after);
		$this->assertEquals($before, $pair->created_before);
		$this->assertEquals('!=', $pair->comparison);
		$this->assertTrue($pair->case_sensitive);
		$this->assertEquals([1], $pair->entity_guids);

		$pair = array_shift($options['metadata_name_value_pairs']);

		$this->assertInstanceOf(MetadataWhereClause::class, $pair);
		$this->assertEquals([5, 6, 7], $pair->ids);
		$this->assertEquals(['tags'], $pair->names);
		$this->assertEquals(['tag1', 'tag2'], $pair->values);
		$this->assertEquals($after, $pair->created_after);
		$this->assertEquals($before, $pair->created_before);
		$this->assertEquals('=', $pair->comparison);
		$this->assertFalse($pair->case_sensitive);
		$this->assertEquals([1], $pair->entity_guids);
	}

	public function testNormalizesMetadataOptionsForMultipleMixedPairs() {

		$after = (new \DateTime())->modify('-1 day');
		$before = (new \DateTime());

		$options = $this->options->normalizeOptions([
			'metadata_id' => [5, 6, 7],
			'metadata_name_value_pairs' => [
				'status' => 'draft',
				new MetadataWhereClause(),
				'owner_guid' => 1,
			],
			'metadata_created_time_lower' => $after,
			'metadata_created_time_upper' => $before,
			'metadata_case_sensitive' => false,
		]);

		$this->assertEquals(3, count($options['metadata_name_value_pairs']));

		$pair = array_shift($options['metadata_name_value_pairs']);

		$this->assertInstanceOf(MetadataWhereClause::class, $pair);
		$this->assertEquals([5, 6, 7], $pair->ids);
		$this->assertEquals(['status'], $pair->names);
		$this->assertEquals(['draft'], $pair->values);
		$this->assertEquals($after, $pair->created_after);
		$this->assertEquals($before, $pair->created_before);
		$this->assertEquals('=', $pair->comparison);
		$this->assertFalse($pair->case_sensitive);

		$pair = array_shift($options['metadata_name_value_pairs']);

		$this->assertInstanceOf(MetadataWhereClause::class, $pair);
		$this->assertEquals(null, $pair->ids);
		$this->assertEquals(null, $pair->names);
		$this->assertEquals(null, $pair->values);
		$this->assertEquals(null, $pair->created_after);
		$this->assertEquals(null, $pair->created_before);

		$pair = array_shift($options['metadata_name_value_pairs']);

		$this->assertInstanceOf(AttributeWhereClause::class, $pair);
		$this->assertEquals(['owner_guid'], $pair->names);
		$this->assertEquals([1], $pair->values);
	}

	public function testNormalizesMetadataOptionsForSinglePairInRoot() {

		$after = (new \DateTime())->modify('-1 day');
		$before = (new \DateTime());

		$options = $this->options->normalizeOptions([
			'metadata_name_value_pairs' => [
				'name' => 'status',
				'value' => 'draft',
			],
		]);

		$this->assertEquals(1, count($options['metadata_name_value_pairs']));

		$pair = array_shift($options['metadata_name_value_pairs']);

		$this->assertInstanceOf(MetadataWhereClause::class, $pair);
		$this->assertEquals(['status'], $pair->names);
		$this->assertEquals(['draft'], $pair->values);
	}

	public function testNormalizesOrderByMetadataOption() {
		$options = $this->options->normalizeOptions([
			'order_by_metadata' => [
				'name' => 'priority',
				'direction' => 'asc',
				'as' => 'integer',
			],
			'order_by' => 'e.guid desc',
		]);

		$this->assertEquals(1, count($options['order_by']));

		$clause = array_shift($options['order_by']);
		/* @var $clause EntitySortByClause */

		$this->assertInstanceOf(EntitySortByClause::class, $clause);

		$this->assertEquals('priority', $clause->property);
		$this->assertEquals('metadata', $clause->property_type);
		$this->assertEquals('ASC', $clause->direction);
		$this->assertEquals('inner', $clause->join_type);
		$this->assertTrue($clause->signed);

	}

	public function testNormalizesMetadataOptionsOnMultipleRuns() {

		$after = (new \DateTime())->modify('-1 day');
		$before = (new \DateTime());

		$options = $this->options->normalizeOptions([
			'metadata_id' => [5, 6, 7],
			'metadata_name_value_pair' => [
				'status' => 'draft',
				'category' => ['foo', 'bar'],
			],
			'metadata_created_time_lower' => $after,
			'metadata_created_time_upper' => $before,
		]);

		$this->assertEquals($options, $this->options->normalizeOptions($options));

	}

	/* METADATA SEARCH PAIRS */

	public function testNormalizesSearchMetadataOptionsFromTimeOptionsWithNestedPair() {

		$options = $this->options->normalizeOptions([
			'search_name_value_pair' => [
				['status' => 'draft']
			],
		]);

		$this->assertEquals(1, count($options['search_name_value_pairs']));

		$pair = array_shift($options['search_name_value_pairs']);

		$this->assertInstanceOf(MetadataWhereClause::class, $pair);
		$this->assertEquals(['status'], $pair->names);
		$this->assertEquals(['draft'], $pair->values);
	}

	public function testNormalizesSearchMetadataOptionsFromTimeOptionsForMultiplePairs() {

		$options = $this->options->normalizeOptions([
			'search_name_value_pair' => [
				'status' => 'draft',
				'category' => ['foo', 'bar'],
			],
		]);

		$this->assertEquals(2, count($options['search_name_value_pairs']));

		$pair = array_shift($options['search_name_value_pairs']);

		$this->assertInstanceOf(MetadataWhereClause::class, $pair);
		$this->assertEquals(['status'], $pair->names);
		$this->assertEquals(['draft'], $pair->values);;

		$pair = array_shift($options['search_name_value_pairs']);

		$this->assertInstanceOf(MetadataWhereClause::class, $pair);
		$this->assertEquals(['category'], $pair->names);
		$this->assertEquals(['foo', 'bar'], $pair->values);

	}

	public function testNormalizesSearchMetadataOptionsForMultiplePairs() {

		$after = (new \DateTime())->modify('-1 day');
		$before = (new \DateTime());

		$options = $this->options->normalizeOptions([
			'guid' => 1,
			'search_name_value_pairs' => [
				'status' => 'draft',
				'category' => ['foo', 'bar'],
				[
					'name' => 'priority',
					'value' => ['100', '200'],
					'operand' => '!=',
					'case_sensitive' => false,
					'created_after' => $after,
					'created_before' => $before,
					'ids' => [5, 6, 7],
				],
				'tags' => "'tag1', 'tag2'",
			],
		]);

		$this->assertEquals(4, count($options['search_name_value_pairs']));

		$pair = array_shift($options['search_name_value_pairs']);

		$this->assertInstanceOf(MetadataWhereClause::class, $pair);
		$this->assertEquals(['status'], $pair->names);
		$this->assertEquals(['draft'], $pair->values);
		$this->assertEquals('=', $pair->comparison);
		$this->assertTrue($pair->case_sensitive);
		$this->assertEquals([1], $pair->entity_guids);

		$pair = array_shift($options['search_name_value_pairs']);

		$this->assertInstanceOf(MetadataWhereClause::class, $pair);
		$this->assertEquals(['category'], $pair->names);
		$this->assertEquals(['foo', 'bar'], $pair->values);
		$this->assertEquals('=', $pair->comparison);
		$this->assertTrue($pair->case_sensitive);
		$this->assertEquals([1], $pair->entity_guids);

		$pair = array_shift($options['search_name_value_pairs']);

		$this->assertInstanceOf(MetadataWhereClause::class, $pair);
		$this->assertEquals([5, 6, 7], $pair->ids);
		$this->assertEquals(['priority'], $pair->names);
		$this->assertEquals(['100', '200'], $pair->values);
		$this->assertEquals($after, $pair->created_after);
		$this->assertEquals($before, $pair->created_before);
		$this->assertEquals('!=', $pair->comparison);
		$this->assertFalse($pair->case_sensitive);
		$this->assertEquals([1], $pair->entity_guids);

		$pair = array_shift($options['search_name_value_pairs']);

		$this->assertInstanceOf(MetadataWhereClause::class, $pair);
		$this->assertEquals(['tags'], $pair->names);
		$this->assertEquals(['tag1', 'tag2'], $pair->values);
		$this->assertEquals('=', $pair->comparison);
		$this->assertTrue($pair->case_sensitive);
		$this->assertEquals([1], $pair->entity_guids);
	}

	public function testNormalizesSearchMetadataOptionsForMultipleMixedPairs() {
		$options = $this->options->normalizeOptions([
			'search_name_value_pairs' => [
				'status' => 'draft',
				new MetadataWhereClause(),
				'owner_guid' => 1,
			],
		]);

		$this->assertEquals(3, count($options['search_name_value_pairs']));

		$pair = array_shift($options['search_name_value_pairs']);

		$this->assertInstanceOf(MetadataWhereClause::class, $pair);
		$this->assertEquals(['status'], $pair->names);
		$this->assertEquals(['draft'], $pair->values);
		$this->assertEquals('=', $pair->comparison);
		$this->assertEquals(null, $pair->created_after);
		$this->assertEquals(null, $pair->created_before);

		$pair = array_shift($options['search_name_value_pairs']);

		$this->assertInstanceOf(MetadataWhereClause::class, $pair);
		$this->assertEquals(null, $pair->ids);
		$this->assertEquals(null, $pair->names);
		$this->assertEquals(null, $pair->values);
		$this->assertEquals(null, $pair->created_after);
		$this->assertEquals(null, $pair->created_before);

		$pair = array_shift($options['search_name_value_pairs']);

		$this->assertInstanceOf(AttributeWhereClause::class, $pair);
		$this->assertEquals(['owner_guid'], $pair->names);
		$this->assertEquals([1], $pair->values);
	}

	public function testNormalizesSearchMetadataOptionsForSinglePairInRoot() {

		$options = $this->options->normalizeOptions([
			'search_name_value_pairs' => [
				'name' => 'status',
				'value' => 'draft',
			],
		]);

		$this->assertEquals(1, count($options['search_name_value_pairs']));

		$pair = array_shift($options['search_name_value_pairs']);

		$this->assertInstanceOf(MetadataWhereClause::class, $pair);
		$this->assertEquals(['status'], $pair->names);
		$this->assertEquals(['draft'], $pair->values);
	}

	public function testNormalizesSearchMetadataOptionsOnMultipleRuns() {

		$after = (new \DateTime())->modify('-1 day');
		$before = (new \DateTime());

		$options = $this->options->normalizeOptions([
			'metadata_id' => [5, 6, 7],
			'search_name_value_pair' => [
				'status' => 'draft',
				'category' => ['foo', 'bar'],
			],
			'metadata_created_time_lower' => $after,
			'metadata_created_time_upper' => $before,
		]);

		$this->assertEquals($options, $this->options->normalizeOptions($options));

	}
	
	/* ANNOTATIONS */

	public function testNormalizesAnnotationsOptionsFromSingulars() {

		$options = $this->options->normalizeOptions([
			'annotation_id' => 5,
			'annotation_name' => 'status',
			'annotation_value' => 'draft',
		]);

		$this->assertEquals(1, count($options['annotation_name_value_pairs']));

		$pair = array_shift($options['annotation_name_value_pairs']);

		$this->assertInstanceOf(AnnotationWhereClause::class, $pair);
		$this->assertEquals([5], $pair->ids);
		$this->assertEquals(['status'], $pair->names);
		$this->assertEquals(['draft'], $pair->values);
	}

	public function testNormalizesAnnotationsOptionsFromPlurals() {

		$options = $this->options->normalizeOptions([
			'annotation_ids' => 5,
			'annotation_names' => 'status',
			'annotation_values' => 'draft',
		]);

		$this->assertEquals(1, count($options['annotation_name_value_pairs']));

		$pair = array_shift($options['annotation_name_value_pairs']);

		$this->assertInstanceOf(AnnotationWhereClause::class, $pair);
		$this->assertEquals([5], $pair->ids);
		$this->assertEquals(['status'], $pair->names);
		$this->assertEquals(['draft'], $pair->values);
	}

	public function testNormalizesAnnotationsOptionsFromTimeOptions() {

		$after = (new \DateTime())->modify('-1 day');
		$before = (new \DateTime());

		$options = $this->options->normalizeOptions([
			'annotation_id' => 5,
			'annotation_name_value_pair' => ['status' => 'draft'],
			'annotation_created_time_lower' => $after,
			'annotation_created_time_upper' => $before,
		]);

		$this->assertEquals(1, count($options['annotation_name_value_pairs']));

		$pair = array_shift($options['annotation_name_value_pairs']);

		$this->assertInstanceOf(AnnotationWhereClause::class, $pair);
		$this->assertEquals([5], $pair->ids);
		$this->assertEquals(['status'], $pair->names);
		$this->assertEquals(['draft'], $pair->values);
		$this->assertEquals($after, $pair->created_after);
		$this->assertEquals($before, $pair->created_before);
	}

	public function testNormalizesAnnotationsOptionsFromTimeOptionsForMultiplePairs() {

		$after = (new \DateTime())->modify('-1 day');
		$before = (new \DateTime());

		$options = $this->options->normalizeOptions([
			'annotation_id' => [5, 6, 7],
			'annotation_name_value_pair' => [
				'status' => 'draft',
				'category' => ['foo', 'bar'],
			],
			'annotation_created_time_lower' => $after,
			'annotation_created_time_upper' => $before,
		]);

		$this->assertEquals(2, count($options['annotation_name_value_pairs']));

		$pair = array_shift($options['annotation_name_value_pairs']);

		$this->assertInstanceOf(AnnotationWhereClause::class, $pair);
		$this->assertEquals([5, 6, 7], $pair->ids);
		$this->assertEquals(['status'], $pair->names);
		$this->assertEquals(['draft'], $pair->values);
		$this->assertEquals($after, $pair->created_after);
		$this->assertEquals($before, $pair->created_before);

		$pair = array_shift($options['annotation_name_value_pairs']);

		$this->assertInstanceOf(AnnotationWhereClause::class, $pair);
		$this->assertEquals([5, 6, 7], $pair->ids);
		$this->assertEquals(['category'], $pair->names);
		$this->assertEquals(['foo', 'bar'], $pair->values);
		$this->assertEquals($after, $pair->created_after);
		$this->assertEquals($before, $pair->created_before);

	}

	public function testNormalizesAnnotationsOptionsForMultiplePairs() {

		$after = (new \DateTime())->modify('-1 day');
		$before = (new \DateTime());

		$options = $this->options->normalizeOptions([
			'guid' => 1,
			'annotation_id' => [5, 6, 7],
			'annotation_name_value_pairs' => [
				'status' => 'draft',
				'category' => ['foo', 'bar'],
				[
					'name' => 'priority',
					'value' => ['100', '200'],
					'operand' => '!=',
					'case_sensitive' => true,
				],
			],
			'annotation_created_time_lower' => $after,
			'annotation_created_time_upper' => $before,
			'annotation_case_sensitive' => false,
			'annotation_owner_guid' => 15,
		]);

		$this->assertEquals(3, count($options['annotation_name_value_pairs']));

		$pair = array_shift($options['annotation_name_value_pairs']);

		$this->assertInstanceOf(AnnotationWhereClause::class, $pair);
		$this->assertEquals([5, 6, 7], $pair->ids);
		$this->assertEquals(['status'], $pair->names);
		$this->assertEquals(['draft'], $pair->values);
		$this->assertEquals($after, $pair->created_after);
		$this->assertEquals($before, $pair->created_before);
		$this->assertEquals('=', $pair->comparison);
		$this->assertFalse($pair->case_sensitive);
		$this->assertEquals([1], $pair->entity_guids);
		$this->assertEquals([15], $pair->owner_guids);

		$pair = array_shift($options['annotation_name_value_pairs']);

		$this->assertInstanceOf(AnnotationWhereClause::class, $pair);
		$this->assertEquals([5, 6, 7], $pair->ids);
		$this->assertEquals(['category'], $pair->names);
		$this->assertEquals(['foo', 'bar'], $pair->values);
		$this->assertEquals($after, $pair->created_after);
		$this->assertEquals($before, $pair->created_before);
		$this->assertEquals('=', $pair->comparison);
		$this->assertFalse($pair->case_sensitive);
		$this->assertEquals([1], $pair->entity_guids);
		$this->assertEquals([15], $pair->owner_guids);

		$pair = array_shift($options['annotation_name_value_pairs']);

		$this->assertInstanceOf(AnnotationWhereClause::class, $pair);
		$this->assertEquals([5, 6, 7], $pair->ids);
		$this->assertEquals(['priority'], $pair->names);
		$this->assertEquals(['100', '200'], $pair->values);
		$this->assertEquals($after, $pair->created_after);
		$this->assertEquals($before, $pair->created_before);
		$this->assertEquals('!=', $pair->comparison);
		$this->assertTrue($pair->case_sensitive);
		$this->assertEquals([1], $pair->entity_guids);
		$this->assertEquals([15], $pair->owner_guids);

	}

	public function testNormalizesAnnotationsOptionsForMultipleMixedPairs() {

		$after = (new \DateTime())->modify('-1 day');
		$before = (new \DateTime());

		$options = $this->options->normalizeOptions([
			'annotation_id' => [5, 6, 7],
			'annotation_name_value_pairs' => [
				'status' => 'draft',
				new AnnotationWhereClause(),
			],
			'annotation_created_time_lower' => $after,
			'annotation_created_time_upper' => $before,
			'annotation_case_sensitive' => false,
		]);

		$this->assertEquals(2, count($options['annotation_name_value_pairs']));

		$pair = array_shift($options['annotation_name_value_pairs']);

		$this->assertInstanceOf(AnnotationWhereClause::class, $pair);
		$this->assertEquals([5, 6, 7], $pair->ids);
		$this->assertEquals(['status'], $pair->names);
		$this->assertEquals(['draft'], $pair->values);
		$this->assertEquals($after, $pair->created_after);
		$this->assertEquals($before, $pair->created_before);
		$this->assertEquals('=', $pair->comparison);
		$this->assertFalse($pair->case_sensitive);

		$pair = array_shift($options['annotation_name_value_pairs']);

		$this->assertInstanceOf(AnnotationWhereClause::class, $pair);
		$this->assertEquals(null, $pair->ids);
		$this->assertEquals(null, $pair->names);
		$this->assertEquals(null, $pair->values);
		$this->assertEquals(null, $pair->created_after);
		$this->assertEquals(null, $pair->created_before);
	}

	public function testNormalizesOrderByAnnotationsOption() {
		$options = $this->options->normalizeOptions([
			'order_by_annotation' => [
				'name' => 'priority',
				'direction' => 'asc',
				'as' => 'integer',
			],
			'order_by' => 'e.guid desc',
		]);

		$this->assertEquals(1, count($options['order_by']));

		$clause = array_shift($options['order_by']);
		/* @var $clause EntitySortByClause */

		$this->assertInstanceOf(EntitySortByClause::class, $clause);

		$this->assertEquals('priority', $clause->property);
		$this->assertEquals('annotation', $clause->property_type);
		$this->assertEquals('ASC', $clause->direction);
		$this->assertEquals('inner', $clause->join_type);
		$this->assertTrue($clause->signed);

	}

	public function testNormalizesAnnotationsOptionsOnMultipleRuns() {

		$after = (new \DateTime())->modify('-1 day');
		$before = (new \DateTime());

		$options = $this->options->normalizeOptions([
			'annotation_id' => [5, 6, 7],
			'annotation_name_value_pairs' => [
				'status' => 'draft',
				new AnnotationWhereClause(),
			],
			'annotation_created_time_lower' => $after,
			'annotation_created_time_upper' => $before,
			'annotation_case_sensitive' => false,
		]);

		$this->assertEquals($options, $this->options->normalizeOptions($options));
	}

	public function testNormalizesAnnotationSortingOptions() {

		$options = $this->options->normalizeOptions([
			'annotation_id' => 5,
			'annotation_name' => 'status',
			'annotation_value' => 'draft',
			'annotation_sort_by_calculation' => 'avg',
		]);

		$this->assertEquals(1, count($options['annotation_name_value_pairs']));

		$pair = array_shift($options['annotation_name_value_pairs']);

		$this->assertInstanceOf(AnnotationWhereClause::class, $pair);
		$this->assertEquals([5], $pair->ids);
		$this->assertEquals(['status'], $pair->names);
		$this->assertEquals(['draft'], $pair->values);
		$this->assertEquals('avg', $pair->sort_by_calculation);
		$this->assertEquals('desc', $pair->sort_by_direction);
	}

	public function testNormalizesAnnotationSortingOptionsWithOrderBy() {

		$options = $this->options->normalizeOptions([
			'annotation_id' => 5,
			'annotation_name' => 'status',
			'annotation_value' => 'draft',
			'annotation_sort_by_calculation' => 'avg',
			'order_by' => [
				new OrderByClause('annotation_calculation', 'asc'),
			]
		]);

		$this->assertEquals(1, count($options['annotation_name_value_pairs']));

		$pair = array_shift($options['annotation_name_value_pairs']);

		$this->assertInstanceOf(AnnotationWhereClause::class, $pair);
		$this->assertEquals([5], $pair->ids);
		$this->assertEquals(['status'], $pair->names);
		$this->assertEquals(['draft'], $pair->values);
		$this->assertEquals('avg', $pair->sort_by_calculation);
		$this->assertEquals(null, $pair->sort_by_direction);
	}

	public function testNormalizesPrivateSettingOptionsFromSingulars() {

		$options = $this->options->normalizeOptions([
			'private_setting_name' => 'status',
			'private_setting_value' => 'draft',
		]);

		$this->assertEquals(1, count($options['private_setting_name_value_pairs']));

		$pair = array_shift($options['private_setting_name_value_pairs']);

		$this->assertInstanceOf(PrivateSettingWhereClause::class, $pair);
		$this->assertEquals(['status'], $pair->names);
		$this->assertEquals(['draft'], $pair->values);
	}

	public function testNormalizesPrivateSettingOptionsFromPlurals() {

		$options = $this->options->normalizeOptions([
			'private_setting_names' => 'status',
			'private_setting_values' => 'draft',
		]);

		$this->assertEquals(1, count($options['private_setting_name_value_pairs']));

		$pair = array_shift($options['private_setting_name_value_pairs']);

		$this->assertInstanceOf(PrivateSettingWhereClause::class, $pair);
		$this->assertEquals(['status'], $pair->names);
		$this->assertEquals(['draft'], $pair->values);
	}

	public function testNormalizesPrivateSettingOptionsForMultiplePairs() {

		$options = $this->options->normalizeOptions([
			'guid' => 1,
			'private_setting_name_value_pairs' => [
				'status' => 'draft',
				'category' => ['foo', 'bar'],
				[
					'name' => 'priority',
					'value' => ['100', '200'],
					'operand' => '!=',
					'case_sensitive' => true,
				],
			],
		]);

		$this->assertEquals(3, count($options['private_setting_name_value_pairs']));

		$pair = array_shift($options['private_setting_name_value_pairs']);

		$this->assertInstanceOf(PrivateSettingWhereClause::class, $pair);
		$this->assertEquals(['status'], $pair->names);
		$this->assertEquals(['draft'], $pair->values);
		$this->assertEquals('=', $pair->comparison);
		$this->assertEquals([1], $pair->entity_guids);

		$pair = array_shift($options['private_setting_name_value_pairs']);

		$this->assertInstanceOf(PrivateSettingWhereClause::class, $pair);
		$this->assertEquals(['category'], $pair->names);
		$this->assertEquals(['foo', 'bar'], $pair->values);
		$this->assertEquals('=', $pair->comparison);
		$this->assertEquals([1], $pair->entity_guids);

		$pair = array_shift($options['private_setting_name_value_pairs']);

		$this->assertInstanceOf(PrivateSettingWhereClause::class, $pair);
		$this->assertEquals(['priority'], $pair->names);
		$this->assertEquals(['100', '200'], $pair->values);
		$this->assertEquals('!=', $pair->comparison);
		$this->assertEquals([1], $pair->entity_guids);
	}

	public function testNormalizesPrivateSettingOptionsForMultipleMixedPairs() {

		$options = $this->options->normalizeOptions([
			'private_setting_name_value_pairs' => [
				'status' => 'draft',
				new PrivateSettingWhereClause(),
			],
		]);

		$this->assertEquals(2, count($options['private_setting_name_value_pairs']));

		$pair = array_shift($options['private_setting_name_value_pairs']);

		$this->assertInstanceOf(PrivateSettingWhereClause::class, $pair);
		$this->assertEquals(['status'], $pair->names);
		$this->assertEquals(['draft'], $pair->values);
		$this->assertEquals('=', $pair->comparison);

		$pair = array_shift($options['private_setting_name_value_pairs']);

		$this->assertInstanceOf(PrivateSettingWhereClause::class, $pair);
		$this->assertEquals(null, $pair->ids);
		$this->assertEquals(null, $pair->names);
		$this->assertEquals(null, $pair->values);
	}

	public function testNormalizesPrivateSettingOptionsOnMultipleRuns() {

		$options = $this->options->normalizeOptions([
			'private_setting_name_value_pair' => [
				'status' => 'draft',
				'category' => ['foo', 'bar'],
			],
		]);

		$this->assertEquals($options, $this->options->normalizeOptions($options));
	}

	public function testNormalizesPrivateSettingOptionsFromSingularsWithPrefix() {

		$options = $this->options->normalizeOptions([
			'private_setting_name' => 'status',
			'private_setting_value' => 'draft',
			'private_setting_name_prefix' => 'prefixed:',
		]);

		$this->assertEquals(1, count($options['private_setting_name_value_pairs']));

		$pair = array_shift($options['private_setting_name_value_pairs']);

		$this->assertInstanceOf(PrivateSettingWhereClause::class, $pair);
		$this->assertEquals(['prefixed:status'], $pair->names);
		$this->assertEquals(['draft'], $pair->values);

	}

	public function testNormalizesPrivateSettingOptionsFromPluralsWithPrefix() {

		$options = $this->options->normalizeOptions([
			'private_setting_names' => 'status',
			'private_setting_values' => 'draft',
			'private_setting_name_prefix' => 'prefixed:',
		]);

		$this->assertEquals(1, count($options['private_setting_name_value_pairs']));

		$pair = array_shift($options['private_setting_name_value_pairs']);

		$this->assertInstanceOf(PrivateSettingWhereClause::class, $pair);
		$this->assertEquals(['prefixed:status'], $pair->names);
		$this->assertEquals(['draft'], $pair->values);
	}

	public function testNormalizesPrivateSettingOptionsForMultiplePairsWithPrefix() {

		$options = $this->options->normalizeOptions([
			'guid' => 1,
			'private_setting_name_prefix' => 'prefixed:',
			'private_setting_name_value_pairs' => [
				'status' => 'draft',
				'category' => ['foo', 'bar'],
				[
					'name' => 'priority',
					'value' => ['100', '200'],
					'operand' => '!=',
					'case_sensitive' => true,
				],
			],
		]);

		$this->assertEquals(3, count($options['private_setting_name_value_pairs']));

		$pair = array_shift($options['private_setting_name_value_pairs']);

		$this->assertInstanceOf(PrivateSettingWhereClause::class, $pair);
		$this->assertEquals(['prefixed:status'], $pair->names);
		$this->assertEquals(['draft'], $pair->values);
		$this->assertEquals('=', $pair->comparison);
		$this->assertEquals([1], $pair->entity_guids);

		$pair = array_shift($options['private_setting_name_value_pairs']);

		$this->assertInstanceOf(PrivateSettingWhereClause::class, $pair);
		$this->assertEquals(['prefixed:category'], $pair->names);
		$this->assertEquals(['foo', 'bar'], $pair->values);
		$this->assertEquals('=', $pair->comparison);
		$this->assertEquals([1], $pair->entity_guids);

		$pair = array_shift($options['private_setting_name_value_pairs']);

		$this->assertInstanceOf(PrivateSettingWhereClause::class, $pair);
		$this->assertEquals(['prefixed:priority'], $pair->names);
		$this->assertEquals(['100', '200'], $pair->values);
		$this->assertEquals('!=', $pair->comparison);
		$this->assertEquals([1], $pair->entity_guids);
	}

	public function testNormalizesPrivateSettingOptionsForMultipleMixedPairsWithPrefix() {

		$options = $this->options->normalizeOptions([
			'private_setting_name_prefix' => 'prefixed:',
			'private_setting_name_value_pairs' => [
				'status' => 'draft',
				new PrivateSettingWhereClause(),
			],
		]);

		$this->assertEquals(2, count($options['private_setting_name_value_pairs']));

		$pair = array_shift($options['private_setting_name_value_pairs']);

		$this->assertInstanceOf(PrivateSettingWhereClause::class, $pair);
		$this->assertEquals(['prefixed:status'], $pair->names);
		$this->assertEquals(['draft'], $pair->values);
		$this->assertEquals('=', $pair->comparison);

		$pair = array_shift($options['private_setting_name_value_pairs']);

		$this->assertInstanceOf(PrivateSettingWhereClause::class, $pair);
		$this->assertEquals(null, $pair->ids);
		$this->assertEquals(null, $pair->names);
		$this->assertEquals(null, $pair->values);
	}

	public function testNormalizesPrivateSettingOptionsOnMultipleRunsWithPrefix() {

		$options = $this->options->normalizeOptions([
			'private_setting_name_prefix' => 'prefixed:',
			'private_setting_name_value_pair' => [
				'status' => 'draft',
				'category' => ['foo', 'bar'],
			],
		]);

		$this->assertEquals($options, $this->options->normalizeOptions($options));
	}

	public function testNormalizeReltionshipOptions() {
		$after = (new \DateTime())->modify('-1 day');
		$before = (new \DateTime());

		$options = $this->options->normalizeOptions([
			'relationship_ids' => [1, 2, 3],
			'relationship' => ['friend', 'enemy'],
			'relationship_guid' => [15, '20', 21],
			'inverse_relationship' => false,
			'relationship_join_on' => 'owner_guid',
			'relationship_created_after' => $after,
			'relationship_created_before' => $before,
		]);

		$this->assertEquals(1, count($options['relationship_pairs']));

		$pair = array_shift($options['relationship_pairs']);
		/* @var $pair \Elgg\Database\Clauses\RelationshipWhereClause */

		$this->assertInstanceOf(RelationshipWhereClause::class, $pair);
		$this->assertEquals([1, 2, 3], $pair->ids);
		$this->assertEquals(['friend', 'enemy'], $pair->names);
		$this->assertEquals([15, 20, 21], $pair->subject_guids);
		$this->assertEquals(false, $pair->inverse);
		$this->assertEquals('owner_guid', $pair->join_on);
		$this->assertEquals($after, $pair->created_after);
		$this->assertEquals($before, $pair->created_before);
	}

	public function testNormalizeReltionshipOptionsForInverseRelationship() {
		$after = (new \DateTime())->modify('-1 day');
		$before = (new \DateTime());

		$options = $this->options->normalizeOptions([
			'relationship_ids' => [1, 2, 3],
			'relationship' => ['friend', 'enemy'],
			'relationship_guid' => [15, '20', 21],
			'inverse_relationship' => true,
			'relationship_join_on' => 'owner_guid',
			'relationship_created_after' => $after,
			'relationship_created_before' => $before,
		]);

		$this->assertEquals(1, count($options['relationship_pairs']));

		$pair = array_shift($options['relationship_pairs']);
		/* @var $pair \Elgg\Database\Clauses\RelationshipWhereClause */

		$this->assertInstanceOf(RelationshipWhereClause::class, $pair);
		$this->assertEquals([1, 2, 3], $pair->ids);
		$this->assertEquals(['friend', 'enemy'], $pair->names);
		$this->assertEquals([15, 20, 21], $pair->object_guids);
		$this->assertEquals(true, $pair->inverse);
		$this->assertEquals('owner_guid', $pair->join_on);
		$this->assertEquals($after, $pair->created_after);
		$this->assertEquals($before, $pair->created_before);
	}

	public function testNormalizeReltionshipOnMultipleCalls() {
		$after = (new \DateTime())->modify('-1 day');
		$before = (new \DateTime());

		$options = $this->options->normalizeOptions([
			'relationship_ids' => [1, 2, 3],
			'relationship' => ['friend', 'enemy'],
			'relationship_guid' => [15, '20', 21],
			'inverse_relationship' => true,
			'relationship_join_on' => 'owner_guid',
			'relationship_created_after' => $after,
			'relationship_created_before' => $before,
		]);

		$this->assertEquals($options, $this->options->normalizeOptions($options));
	}

	public function testNormalizeSelectClauses() {

		$clause = function (QueryBuilder $qb) {
			$qb->select('foo.bam');
		};

		$options = $this->options->normalizeOptions([
			'selects' => [
				'',
				'foo.bar',
				new SelectClause('foo.baz'),
				$clause,
			],
		]);

		$this->assertArrayNotHasKey(0, $options['selects']);
		$this->assertEquals(new SelectClause('foo.bar'), $options['selects'][1]);
		$this->assertEquals(new SelectClause('foo.baz'), $options['selects'][2]);
		$this->assertEquals(new SelectClause($clause), $options['selects'][3]);
	}

	public function testNormalizeWhereClauses() {

		$clause = function (QueryBuilder $qb) {
			return $qb->expr()->eq(1, 1);
		};

		$options = $this->options->normalizeOptions([
			'wheres' => [
				'',
				"foo.bar = 'bar'",
				new WhereClause("foo.baz = 'baz'"),
				$clause,
			],
		]);

		$this->assertArrayNotHasKey(0, $options['wheres']);
		$this->assertEquals(new WhereClause("foo.bar = 'bar'"), $options['wheres'][1]);
		$this->assertEquals(new WhereClause("foo.baz = 'baz'"), $options['wheres'][2]);
		$this->assertEquals(new WhereClause($clause), $options['wheres'][3]);
	}

	/**
	 * @group QueryBuilderJoins
	 */
	public function testNormalizeJoinClauses() {

		$dbprefix = elgg_get_config('dbprefix');
		$options = $this->options->normalizeOptions([
			'joins' => [
				'',
				'JOIN table_a a ON table_a.entity_guid = e.guid',
				"JOIN {$dbprefix}table_b AS b
				      ON b.entity_guid = e.guid
				     ",
				'LEFT JOIn table_c c    ON c.entity_guid = e.guid',
				new JoinClause('table_d', 'd', 'd.entity_guid = e.guid', 'right'),
				new JoinClause('table_f', 'f', function (QueryBuilder $qb) {
					return $qb->compare('f.entity_guid', '=', 'e.guid');
				})
			]
		]);

		$join = array_shift($options['joins']);
		/* @var $join \Elgg\Database\Clauses\JoinClause */

		$this->assertInstanceOf(JoinClause::class, $join);
		$this->assertEquals('table_a', $join->joined_table);
		$this->assertEquals('a', $join->joined_alias);
		$this->assertEquals('table_a.entity_guid = e.guid', $join->condition);
		$this->assertEquals('inner', $join->join_type);

		$join = array_shift($options['joins']);

		$this->assertInstanceOf(JoinClause::class, $join);
		$this->assertEquals('table_b', $join->joined_table);
		$this->assertEquals('b', $join->joined_alias);
		$this->assertEquals('b.entity_guid = e.guid', $join->condition);
		$this->assertEquals('inner', $join->join_type);

		$join = array_shift($options['joins']);

		$this->assertInstanceOf(JoinClause::class, $join);
		$this->assertEquals('table_c', $join->joined_table);
		$this->assertEquals('c', $join->joined_alias);
		$this->assertEquals('c.entity_guid = e.guid', $join->condition);
		$this->assertEquals('left', $join->join_type);

		$join = array_shift($options['joins']);

		$this->assertInstanceOf(JoinClause::class, $join);
		$this->assertEquals('table_d', $join->joined_table);
		$this->assertEquals('d', $join->joined_alias);
		$this->assertEquals('d.entity_guid = e.guid', $join->condition);
		$this->assertEquals('right', $join->join_type);

		$join = array_shift($options['joins']);

		$this->assertInstanceOf(JoinClause::class, $join);
		$this->assertEquals('table_f', $join->joined_table);
		$this->assertEquals('f', $join->joined_alias);
		$this->assertInstanceOf(\Closure::class, $join->condition);
		$this->assertEquals('inner', $join->join_type);
	}

	/**
	 * @group QueryBuilderOrder
	 */
	public function testNormalizeOrderByOptions() {

		$options = $this->options->normalizeOptions([
			'order_by' => 'foo.bar, foo.baz ASC,  sum(x) desc',
		]);

		$this->assertEquals(3, count($options['order_by']));

		$clause = array_shift($options['order_by']);
		/* @var $clause \Elgg\Database\Clauses\OrderByClause */

		$this->assertInstanceOf(OrderByClause::class, $clause);
		$this->assertEquals('foo.bar', $clause->expr);
		$this->assertEquals('ASC', $clause->direction);

		$clause = array_shift($options['order_by']);

		$this->assertInstanceOf(OrderByClause::class, $clause);
		$this->assertEquals('foo.baz', $clause->expr);
		$this->assertEquals('ASC', $clause->direction);

		$clause = array_shift($options['order_by']);

		$this->assertInstanceOf(OrderByClause::class, $clause);
		$this->assertEquals('sum(x)', $clause->expr);
		$this->assertEquals('DESC', $clause->direction);

	}

	/**
	 * @group QueryBuilderOrder
	 */
	public function testNormalizeOrderByOptionsAsArray() {

		$options = $this->options->normalizeOptions([
			'order_by' => [
				'foo.bar DESC',
				new OrderByClause('foo.baz', 'DESC'),
			],
		]);

		$this->assertEquals(2, count($options['order_by']));

		$clause = array_shift($options['order_by']);
		/* @var $clause \Elgg\Database\Clauses\OrderByClause */

		$this->assertInstanceOf(OrderByClause::class, $clause);
		$this->assertEquals('foo.bar', $clause->expr);
		$this->assertEquals('DESC', $clause->direction);

		$clause = array_shift($options['order_by']);

		$this->assertInstanceOf(OrderByClause::class, $clause);
		$this->assertEquals('foo.baz', $clause->expr);
		$this->assertEquals('DESC', $clause->direction);
	}

	public function testNormalizeOrderByOptionsAsSingleClause() {

		$options = $this->options->normalizeOptions([
			'order_by' => new OrderByClause('foo.baz', 'DESC'),
		]);

		$this->assertEquals(1, count($options['order_by']));

		$clause = array_shift($options['order_by']);
		/* @var $clause \Elgg\Database\Clauses\OrderByClause */

		$this->assertInstanceOf(OrderByClause::class, $clause);
		$this->assertEquals('foo.baz', $clause->expr);
		$this->assertEquals('DESC', $clause->direction);
	}

	public function testNormalizeGroupByOptions() {
		$options = $this->options->normalizeOptions([
			'having' => 'foo.bar = 1',
			'group_by' => 'foo.bar, foo.baz',
		]);

		$this->assertEquals(1, count($options['having']));

		$clause = array_shift($options['having']);
		/* @var $clause \Elgg\Database\Clauses\HavingClause */

		$this->assertInstanceOf(HavingClause::class, $clause);
		$this->assertEquals('foo.bar = 1', $clause->expr);

		$this->assertEquals(2, count($options['group_by']));

		$clause = array_shift($options['group_by']);
		/* @var $clause \Elgg\Database\Clauses\GroupByClause */

		$this->assertInstanceOf(GroupByClause::class, $clause);
		$this->assertEquals('foo.bar', $clause->expr);

		$clause = array_shift($options['group_by']);
		/* @var $clause \Elgg\Database\Clauses\GroupByClause */

		$this->assertInstanceOf(GroupByClause::class, $clause);
		$this->assertEquals('foo.baz', $clause->expr);
	}

	public function testNormalizeGroupByOptionsAsArray() {
		$options = $this->options->normalizeOptions([
			'having' => new HavingClause('foo.bar = 1'),
			'group_by' => [
				new GroupByClause('foo.bar'),
				'foo.baz',
			]
		]);

		$this->assertEquals(1, count($options['having']));

		$clause = array_shift($options['having']);
		/* @var $clause \Elgg\Database\Clauses\HavingClause */

		$this->assertInstanceOf(HavingClause::class, $clause);
		$this->assertEquals('foo.bar = 1', $clause->expr);

		$this->assertEquals(2, count($options['group_by']));

		$clause = array_shift($options['group_by']);
		/* @var $clause \Elgg\Database\Clauses\GroupByClause */

		$this->assertInstanceOf(GroupByClause::class, $clause);
		$this->assertEquals('foo.bar', $clause->expr);

		$clause = array_shift($options['group_by']);
		/* @var $clause \Elgg\Database\Clauses\GroupByClause */

		$this->assertInstanceOf(GroupByClause::class, $clause);
		$this->assertEquals('foo.baz', $clause->expr);
	}

	/**
	 * @group Setters
	 */
	public function testSetters() {

		$this->assertFalse(isset($this->options->x));

		$this->options->x = [];

		$this->assertTrue(isset($this->options->x));

		$this->assertEquals([], $this->options->x);

		$this->options->x[] = 1;

		$this->assertEquals([1], $this->options->x);

		unset($this->options->x);

		$this->assertFalse(isset($this->options->x));

	}

	public function testDistinct() {
		$this->options->distinct(false);
		$this->assertFalse($this->options->distinct);
	}

	public function testWhere() {
		$where = new WhereClause('x = y');
		$this->options->where($where);
		$this->assertEquals([$where], $this->options->wheres);
	}

	public function testSelect() {
		$select = new SelectClause('x as y');
		$this->options->select($select);
		$this->assertEquals([$select], $this->options->selects);
	}

	public function testJoin() {
		$join = new JoinClause('table', 'alias');
		$this->options->join($join);
		$this->assertEquals([$join], $this->options->joins);
	}

	public function testGroupBy() {
		$group_by = new GroupByClause('x');
		$this->options->groupBy($group_by);
		$this->assertEquals([$group_by], $this->options->group_by);
	}

	public function testHaving() {
		$having = new HavingClause('x = y');
		$this->options->having($having);
		$this->assertEquals([$having], $this->options->having);
	}

	public function testOrderBy() {
		$order_by = new OrderByClause('x', 'asc');
		$this->options->orderBy($order_by);
		$this->assertEquals([$order_by], $this->options->order_by);
	}

	public function testNomralizesNonArrayProps() {
		$join = new JoinClause('table', 'alias');
		$options = [
			'wheres' => 'x = y',
			'selects' => 'x AS y',
			'joins' => $join,
		];

		$options = new QueryOptions($options, \ArrayObject::ARRAY_AS_PROPS);

		$this->assertEquals([new WhereClause('x = y')], $options->wheres);
		$this->assertEquals([new SelectClause('x AS y')], $options->selects);
		$this->assertEquals([$join], $options->joins);
	}

}
