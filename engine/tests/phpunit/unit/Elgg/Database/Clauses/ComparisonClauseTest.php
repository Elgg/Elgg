<?php

namespace Elgg\Integration;

use Elgg\Database\Clauses\ComparisonClause;
use Elgg\Database\QueryBuilder;
use Elgg\Database\Select;
use Elgg\UnitTestCase;

/**
 * @group QueryBuilder
 * @group QueryBuilderComparison
 */
class ComparisonClauseTest extends UnitTestCase {

	/**
	 * @var QueryBuilder
	 */
	protected $qb;

	public function up() {
		$this->qb = Select::fromTable('entities', 'alias');
	}

	public function down() {

	}

	/**
	 * @dataProvider operators
	 */
	public function testBuildEmptyClause($operator) {

		$clause = new ComparisonClause('x', $operator);

		$this->assertEquals(null, $clause->prepare($this->qb));
	}

	public function operators() {
		return [
			['eq'],
			['in'],
			['neq'],
			['not in'],
			['like'],
			['not like'],
			['gt'],
			['lt'],
			['gte'],
			['lte'],
		];
	}

	public function testCanBuildClauseWithoutTypeCasting() {

		$expr = $this->qb->expr()->eq('x', 'y');
		$this->qb->where($expr);

		$qb = Select::fromTable('entities', 'alias');
		$qb->where($qb->compare('x', '=', 'y'));

		$this->assertEquals($this->qb->getSQL(), $qb->getSQL());
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testCanNormalizeDateTime() {

		$dt = new \DateTime();

		$expr = $this->qb->expr()->eq('x', ':qb1');
		$this->qb->param($dt->getTimestamp(), ELGG_VALUE_INTEGER);
		$this->qb->where($expr);

		$qb = Select::fromTable('entities', 'alias');
		$qb->where($qb->compare('x', '=', $dt, ELGG_VALUE_TIMESTAMP));

		$this->assertEquals($this->qb->getSQL(), $qb->getSQL());
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testCanNormalizeDateString() {

		$date = 'July 20, 2017';

		$expr = $this->qb->expr()->eq('x', ':qb1');
		$this->qb->param((new \DateTime($date))->getTimestamp(), ELGG_VALUE_INTEGER);
		$this->qb->where($expr);

		$qb = Select::fromTable('entities', 'alias');
		$qb->where($qb->compare('x', '=', $date, ELGG_VALUE_TIMESTAMP));

		$this->assertEquals($this->qb->getSQL(), $qb->getSQL());
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testCanNormalizeTimestamp() {

		$date = time();

		$expr = $this->qb->expr()->eq('x', ':qb1');
		$this->qb->param($date, ELGG_VALUE_INTEGER);
		$this->qb->where($expr);

		$qb = Select::fromTable('entities', 'alias');
		$qb->where($qb->compare('x', '=', $date, ELGG_VALUE_TIMESTAMP));

		$this->assertEquals($this->qb->getSQL(), $qb->getSQL());
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testCanNormalizeGuids() {

		$object = (object) ['guid' => 25];
		$entity = $this->createObject();
		$input = [
			'10',
			$entity->guid,
			20,
			$object,
			[30, 35],
		];

		$guids = [
			10, $entity->guid, 20, 25, 30, 35
		];

		$expr = $this->qb->expr()->in('x', ':qb1');
		$this->qb->param($guids, ELGG_VALUE_INTEGER);
		$this->qb->where($expr);

		$qb = Select::fromTable('entities', 'alias');
		$qb->where($qb->compare('x', '=', $input, ELGG_VALUE_GUID));

		$this->assertEquals($this->qb->getSQL(), $qb->getSQL());
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testCanNormalizeIds() {

		$object = (object) ['id' => 20];
		$input = [
			'10',
			15,
			$object,
			[25, 30]
		];

		$ids = [
			10, 15, 20, 25, 30
		];

		$expr = $this->qb->expr()->in('x', ':qb1');
		$this->qb->param($ids, ELGG_VALUE_INTEGER);
		$this->qb->where($expr);

		$qb = Select::fromTable('entities', 'alias');
		$qb->where($qb->compare('x', '=', $input, ELGG_VALUE_ID));

		$this->assertEquals($this->qb->getSQL(), $qb->getSQL());
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testCanCompareCaseInsensitive() {
		$input = 'Abc';

		$expr = $this->qb->expr()->eq('x', ':qb1');
		$this->qb->param($input, ELGG_VALUE_STRING);
		$this->qb->where($expr);

		$qb = Select::fromTable('entities', 'alias');
		$qb->where($qb->compare('x', '=', $input, ELGG_VALUE_STRING, false));

		$this->assertEquals($this->qb->getSQL(), $qb->getSQL());
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testIgnoresCaseSensitiveFlagWithNonStringValue() {
		$input = '2';
		$int = 2;

		$expr = $this->qb->expr()->eq('x', ':qb1');
		$this->qb->param($int, ELGG_VALUE_INTEGER);
		$this->qb->where($expr);

		$qb = Select::fromTable('entities', 'alias');
		$qb->where($qb->compare('x', '=', $input, ELGG_VALUE_INTEGER, false));

		$this->assertEquals($this->qb->getSQL(), $qb->getSQL());
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testCanCompareArrayCaseInsensitive() {
		$input = [
			'Abc',
			'dEf',
		];

		$expr = $this->qb->expr()->in('x', ':qb1');
		$this->qb->param($input, ELGG_VALUE_STRING);
		$this->qb->where($expr);

		$qb = Select::fromTable('entities', 'alias');
		$qb->where($qb->compare('x', '=', $input, ELGG_VALUE_STRING, false));

		$this->assertEquals($this->qb->getSQL(), $qb->getSQL());
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testIgnoresCaseSensitiveFlagWithNonStringArray() {
		$input = [
			1,
			'2',
		];

		$ints = [
			1,
			2
		];

		$expr = $this->qb->expr()->in('x', ':qb1');
		$this->qb->param($ints, ELGG_VALUE_INTEGER);
		$this->qb->where($expr);

		$qb = Select::fromTable('entities', 'alias');
		$qb->where($qb->compare('x', '=', $input, ELGG_VALUE_INTEGER, false));

		$this->assertEquals($this->qb->getSQL(), $qb->getSQL());
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testIgnoresCaseSensitiveFlagWithNonStringValues() {
		$input = [
			1,
			'2',
		];

		$ints = [
			1,
			2
		];

		$expr = $this->qb->expr()->in('x', ':qb1');
		$this->qb->param($ints, ELGG_VALUE_INTEGER);
		$this->qb->where($expr);

		$qb = Select::fromTable('entities', 'alias');
		$qb->where($qb->compare('x', '=', $input, ELGG_VALUE_INTEGER, false));

		$this->assertEquals($this->qb->getSQL(), $qb->getSQL());
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testCanCompareValueUsingIn() {
		$input = 5;

		$expr = $this->qb->expr()->in('x', ':qb1');
		$this->qb->param($input, ELGG_VALUE_INTEGER);
		$this->qb->where($expr);

		$qb = Select::fromTable('entities', 'alias');
		$qb->where($qb->compare('x', 'in', $input, ELGG_VALUE_INTEGER));

		$this->assertEquals($this->qb->getSQL(), $qb->getSQL());
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testCanCompareValueUsingEq() {
		$input = 5;

		$expr = $this->qb->expr()->eq('x', ':qb1');
		$this->qb->param($input, ELGG_VALUE_INTEGER);
		$this->qb->where($expr);

		$qb = Select::fromTable('entities', 'alias');
		$qb->where($qb->compare('x', 'eq', $input, ELGG_VALUE_INTEGER));

		$this->assertEquals($this->qb->getSQL(), $qb->getSQL());
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testCanCompareValueUsingEqualSign() {
		$input = 5;

		$expr = $this->qb->expr()->eq('x', ':qb1');
		$this->qb->param($input, ELGG_VALUE_INTEGER);
		$this->qb->where($expr);

		$qb = Select::fromTable('entities', 'alias');
		$qb->where($qb->compare('x', '=', $input, ELGG_VALUE_INTEGER));

		$this->assertEquals($this->qb->getSQL(), $qb->getSQL());
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testCanCompareArrayUsingIn() {
		$input = [5, 6];

		$expr = $this->qb->expr()->in('x', ':qb1');
		$this->qb->param($input, ELGG_VALUE_INTEGER);
		$this->qb->where($expr);

		$qb = Select::fromTable('entities', 'alias');
		$qb->where($qb->compare('x', 'in', $input, ELGG_VALUE_INTEGER));

		$this->assertEquals($this->qb->getSQL(), $qb->getSQL());
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testCanCompareArrayUsingEq() {
		$input = [5, 6];

		$expr = $this->qb->expr()->in('x', ':qb1');
		$this->qb->param($input, ELGG_VALUE_INTEGER);
		$this->qb->where($expr);

		$qb = Select::fromTable('entities', 'alias');
		$qb->where($qb->compare('x', 'eq', $input, ELGG_VALUE_INTEGER));

		$this->assertEquals($this->qb->getSQL(), $qb->getSQL());
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testCanCompareArrayUsingEqualSign() {
		$input = [5, 6];

		$expr = $this->qb->expr()->in('x', ':qb1');
		$this->qb->param($input, ELGG_VALUE_INTEGER);
		$this->qb->where($expr);

		$qb = Select::fromTable('entities', 'alias');
		$qb->where($qb->compare('x', 'eq', $input, ELGG_VALUE_INTEGER));

		$this->assertEquals($this->qb->getSQL(), $qb->getSQL());
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testCanCompareValueUsingNotIn() {
		$input = 5;

		$expr = $this->qb->expr()->notIn('x', ':qb1');
		$this->qb->param($input, ELGG_VALUE_INTEGER);
		$this->qb->where($expr);

		$qb = Select::fromTable('entities', 'alias');
		$qb->where($qb->compare('x', 'not in', $input, ELGG_VALUE_INTEGER));

		$this->assertEquals($this->qb->getSQL(), $qb->getSQL());
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testCanCompareValueUsingNeq() {
		$input = 5;

		$expr = $this->qb->expr()->neq('x', ':qb1');
		$this->qb->param($input, ELGG_VALUE_INTEGER);
		$this->qb->where($expr);

		$qb = Select::fromTable('entities', 'alias');
		$qb->where($qb->compare('x', 'neq', $input, ELGG_VALUE_INTEGER));

		$this->assertEquals($this->qb->getSQL(), $qb->getSQL());
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testCanCompareValueUsingNotEqualSign() {
		$input = 5;

		$expr = $this->qb->expr()->neq('x', ':qb1');
		$this->qb->param($input, ELGG_VALUE_INTEGER);
		$this->qb->where($expr);

		$qb = Select::fromTable('entities', 'alias');
		$qb->where($qb->compare('x', '!=', $input, ELGG_VALUE_INTEGER));

		$this->assertEquals($this->qb->getSQL(), $qb->getSQL());
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testCanCompareArrayUsingNotIn() {
		$input = [5, 6];

		$expr = $this->qb->expr()->notIn('x', ':qb1');
		$this->qb->param($input, ELGG_VALUE_INTEGER);
		$this->qb->where($expr);

		$qb = Select::fromTable('entities', 'alias');
		$qb->where($qb->compare('x', 'not in', $input, ELGG_VALUE_INTEGER));

		$this->assertEquals($this->qb->getSQL(), $qb->getSQL());
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testCanCompareArrayUsingNeq() {
		$input = [5, 6];

		$expr = $this->qb->expr()->notIn('x', ':qb1');
		$this->qb->param($input, ELGG_VALUE_INTEGER);
		$this->qb->where($expr);

		$qb = Select::fromTable('entities', 'alias');
		$qb->where($qb->compare('x', 'neq', $input, ELGG_VALUE_INTEGER));

		$this->assertEquals($this->qb->getSQL(), $qb->getSQL());
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testCanCompareArrayUsingNotEqualSign() {
		$input = [5, 6];

		$expr = $this->qb->expr()->notIn('x', ':qb1');
		$this->qb->param($input, ELGG_VALUE_INTEGER);
		$this->qb->where($expr);

		$qb = Select::fromTable('entities', 'alias');
		$qb->where($qb->compare('x', 'neq', $input, ELGG_VALUE_INTEGER));

		$this->assertEquals($this->qb->getSQL(), $qb->getSQL());
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	/**
	 * @dataProvider operatorComparison
	 */
	public function testCanCompareUsingOtherOperators($input, $type, $normalized_input, $operator, $method, $boolean) {
		$parts = [];
		if (is_array($normalized_input)) {
			foreach ($normalized_input as $val) {
				$key = $this->qb->param($val, $type);
				$parts[] = $this->qb->expr()->$method('x', $key);
			}
		} else {
			$key = $this->qb->param($normalized_input, $type);
			$parts[] = $this->qb->expr()->$method('x', $key);
		}

		$expr = $this->qb->merge($parts, $boolean);
		$this->qb->where($expr);

		$qb = Select::fromTable('entities', 'alias');
		$qb->where($qb->compare('x', $operator, $input, $type));

		$this->assertEquals($this->qb->getSQL(), $qb->getSQL());
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function operatorComparison() {
		return [
			['%elgg', ELGG_VALUE_STRING, '%elgg', 'LIKE', 'like', 'or'],
			['%elgg', ELGG_VALUE_STRING, '%elgg', 'like', 'like', 'or'],
			[5, ELGG_VALUE_STRING, '5', 'like', 'like', 'or'],
			[['%elgg', 'elgg%'], ELGG_VALUE_STRING, ['%elgg', 'elgg%'], 'like', 'like', 'or'],
			[[5, '6'], ELGG_VALUE_STRING, ['5', '6'], 'like', 'like', 'or'],

			['%elgg', ELGG_VALUE_STRING, '%elgg', 'not like', 'notLike', 'and'],
			[5, ELGG_VALUE_STRING, '5', 'not like', 'notLike', 'and'],
			[['%elgg', 'elgg%'], ELGG_VALUE_STRING, ['%elgg', 'elgg%'], 'not like', 'notLike', 'and'],
			[[5, '6'], ELGG_VALUE_STRING, ['5', '6'], 'not like', 'notLike', 'and'],

			['5', ELGG_VALUE_INTEGER, 5, '>', 'gt', 'or'],
			[['5', 6], ELGG_VALUE_INTEGER, [5, 6], '>', 'gt', 'or'],

			['5', ELGG_VALUE_INTEGER, 5, 'gt', 'gt', 'or'],
			[['5', 6], ELGG_VALUE_INTEGER, [5, 6], 'gt', 'gt', 'or'],

			['5', ELGG_VALUE_INTEGER, 5, '<', 'lt', 'or'],
			[['5', 6], ELGG_VALUE_INTEGER, [5, 6], '<', 'lt', 'or'],

			['5', ELGG_VALUE_INTEGER, 5, 'lt', 'lt', 'or'],
			[['5', 6], ELGG_VALUE_INTEGER, [5, 6], 'lt', 'lt', 'or'],

			['5', ELGG_VALUE_INTEGER, 5, '>=', 'gte', 'or'],
			[['5', 6], ELGG_VALUE_INTEGER, [5, 6], '>=', 'gte', 'or'],

			['5', ELGG_VALUE_INTEGER, 5, 'gte', 'gte', 'or'],
			[['5', 6], ELGG_VALUE_INTEGER, [5, 6], 'gte', 'gte', 'or'],
		];
	}

	public function testCanCompareArrayUsingIsNull() {
		$expr = $this->qb->expr()->isNull('x');
		$this->qb->where($expr);

		$qb = Select::fromTable('entities', 'alias');
		$qb->where($qb->compare('x', 'IS NULL'));

		$qb = Select::fromTable('entities', 'alias');
		$qb->where($qb->compare('x', 'is null', 'y'));

		$this->assertEquals($this->qb->getSQL(), $qb->getSQL());
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testCanCompareArrayUsingIsNotNull() {
		$expr = $this->qb->expr()->isNotNull('x');
		$this->qb->where($expr);

		$qb = Select::fromTable('entities', 'alias');
		$qb->where($qb->compare('x', 'IS NOT NULL'));

		$qb = Select::fromTable('entities', 'alias');
		$qb->where($qb->compare('x', 'is not null', 'y'));

		$this->assertEquals($this->qb->getSQL(), $qb->getSQL());
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	/**
	 * @expectedException \InvalidParameterException
	 */
	public function testThrowsOnInvalidComparison() {
		$qb = Select::fromTable('entities', 'alias');
		$qb->where($qb->compare('x', 'INVALID'));
	}

}
