<?php

namespace Elgg\Integration;

use Elgg\Database\Clauses\AccessWhereClause;
use Elgg\Database\Clauses\EntityWhereClause;
use Elgg\Database\QueryBuilder;
use Elgg\Database\Select;
use Elgg\UnitTestCase;

/**
 * @group QueryBuilder
 * @group QueryBuilderWhere
 */
class EntityWhereClauseTest extends UnitTestCase {

	/**
	 * @var QueryBuilder
	 */
	protected $qb;

	public function up() {
		$this->qb = Select::fromTable('entities', 'alias');
	}

	public function down() {

	}

	public function testBuildEmptyQuery() {

		$expected = null;

		$query = new EntityWhereClause();
		$query->ignore_access = true;
		$query->use_enabled_clause = false;

		$qb = Select::fromTable('entities', 'alias');
		$actual = $query->prepare($qb, 'alias');

		$this->assertEquals($expected, $actual);
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testBuildQueryFromGuid() {

		$parts = [];
		$parts[] = $this->qb->expr()->eq('alias.guid', ':qb1');
		$this->qb->param(1, ELGG_VALUE_INTEGER);
		$expected = $this->qb->merge($parts);

		$query = new EntityWhereClause();
		$query->ignore_access = true;
		$query->use_enabled_clause = false;
		$query->guids = 1;

		$qb = Select::fromTable('entities', 'alias');
		$actual = $query->prepare($qb, 'alias');

		$this->assertEquals($expected, $actual);
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testBuildQueryFromOwnerAndContainerGuid() {

		$parts = [];
		$parts[] = $this->qb->expr()->in('alias.owner_guid', ':qb1');
		$parts[] = $this->qb->expr()->in('alias.container_guid', ':qb2');
		$this->qb->param([2, 3], ELGG_VALUE_INTEGER);
		$this->qb->param([4, 5, 6], ELGG_VALUE_INTEGER);
		$expected = $this->qb->merge($parts);

		$query = new EntityWhereClause();
		$query->ignore_access = true;
		$query->use_enabled_clause = false;
		$query->owner_guids = [2, 3];
		$query->container_guids = [4, 5, 6];

		$qb = Select::fromTable('entities', 'alias');
		$actual = $query->prepare($qb, 'alias');

		$this->assertEquals($expected, $actual);
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testBuildQueryFromTimeCreated() {

		$after = (new \DateTime())->modify('-1 day');
		$before = (new \DateTime())->modify('+1 day');

		$parts = [];

		$time_parts = [];
		$time_parts[] = $this->qb->expr()->gte('alias.time_created', ':qb1');
		$time_parts[] = $this->qb->expr()->lte('alias.time_created', ':qb2');
		$this->qb->param($after->getTimestamp(), ELGG_VALUE_INTEGER);
		$this->qb->param($before->getTimestamp(), ELGG_VALUE_INTEGER);
		$parts[] = $this->qb->merge($time_parts);

		$expected = $this->qb->merge($parts);

		$query = new EntityWhereClause();
		$query->ignore_access = true;
		$query->use_enabled_clause = false;
		$query->created_after = $after;
		$query->created_before = $before;

		$qb = Select::fromTable('entities', 'alias');
		$actual = $query->prepare($qb, 'alias');

		$this->assertEquals($expected, $actual);
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());

	}

	public function testBuildQueryFromTimeUpdated() {

		$after = (new \DateTime())->modify('-1 day');
		$before = (new \DateTime())->modify('+1 day');

		$parts = [];

		$time_parts = [];
		$time_parts[] = $this->qb->expr()->gte('alias.time_updated', ':qb1');
		$time_parts[] = $this->qb->expr()->lte('alias.time_updated', ':qb2');
		$this->qb->param($after->getTimestamp(), ELGG_VALUE_INTEGER);
		$this->qb->param($before->getTimestamp(), ELGG_VALUE_INTEGER);
		$parts[] = $this->qb->merge($time_parts);

		$expected = $this->qb->merge($parts);

		$query = new EntityWhereClause();
		$query->ignore_access = true;
		$query->use_enabled_clause = false;
		$query->updated_after = $after;
		$query->updated_before = $before;

		$qb = Select::fromTable('entities', 'alias');
		$actual = $query->prepare($qb, 'alias');

		$this->assertEquals($expected, $actual);
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());

	}

	public function testBuildQueryFromLastAction() {

		$after = (new \DateTime())->modify('-1 day');
		$before = (new \DateTime())->modify('+1 day');

		$parts = [];

		$time_parts = [];
		$time_parts[] = $this->qb->expr()->gte('alias.last_action', ':qb1');
		$time_parts[] = $this->qb->expr()->lte('alias.last_action', ':qb2');
		$this->qb->param($after->getTimestamp(), ELGG_VALUE_INTEGER);
		$this->qb->param($before->getTimestamp(), ELGG_VALUE_INTEGER);
		$parts[] = $this->qb->merge($time_parts);

		$expected = $this->qb->merge($parts);

		$query = new EntityWhereClause();
		$query->ignore_access = true;
		$query->use_enabled_clause = false;
		$query->last_action_after = $after;
		$query->last_action_before = $before;

		$qb = Select::fromTable('entities', 'alias');
		$actual = $query->prepare($qb, 'alias');

		$this->assertEquals($expected, $actual);
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());

	}

	public function testBuildQueryFromEnabled() {

		$parts = [];
		$parts[] = $this->qb->expr()->eq('alias.enabled', ':qb1');
		$this->qb->param('no', ELGG_VALUE_STRING);
		$expected = $this->qb->merge($parts);

		$query = new EntityWhereClause();
		$query->ignore_access = true;
		$query->use_enabled_clause = false;
		$query->enabled = 'no';

		$qb = Select::fromTable('entities', 'alias');
		$actual = $query->prepare($qb, 'alias');

		$this->assertEquals($expected, $actual);
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testBuildQueryFromAccessId() {

		$parts = [];
		$parts[] = $this->qb->expr()->eq('alias.access_id', ':qb1');
		$this->qb->param(ACCESS_PUBLIC, ELGG_VALUE_INTEGER);
		$expected = $this->qb->merge($parts);

		$query = new EntityWhereClause();
		$query->ignore_access = true;
		$query->use_enabled_clause = false;
		$query->access_ids = ACCESS_PUBLIC;

		$qb = Select::fromTable('entities', 'alias');
		$actual = $query->prepare($qb, 'alias');

		$this->assertEquals($expected, $actual);
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testBuildQueryFromTypeSubtypePairs() {

		$parts = [];

		$type_parts = [];
		$type_where = [];
		$type_where[] = $this->qb->merge([
			$this->qb->expr()->eq('alias.type', ':qb1'),
			$this->qb->expr()->in('alias.subtype', ':qb2'),
		]);
		$type_where[] = $this->qb->merge([
			$this->qb->expr()->eq('alias.type', ':qb3'),
			$this->qb->expr()->eq('alias.subtype', ':qb4'),
		]);
		$this->qb->param('object', ELGG_VALUE_STRING);
		$this->qb->param(['blog', 'file'], ELGG_VALUE_STRING);
		$this->qb->param('group', ELGG_VALUE_STRING);
		$this->qb->param('community', ELGG_VALUE_STRING);
		$type_parts[] = $this->qb->merge($type_where, 'OR');
		$parts[] = $this->qb->merge($type_parts);

		$parts[] = $this->qb->expr()->eq('alias.guid', ':qb5');
		$this->qb->param(1, ELGG_VALUE_INTEGER);

		$expected = $this->qb->merge($parts);

		$query = new EntityWhereClause();
		$query->ignore_access = true;
		$query->use_enabled_clause = false;
		$query->type_subtype_pairs = [
			'object' => ['blog', 'file'],
			'group' => ['community'],
		];
		$query->guids = 1;

		$qb = Select::fromTable('entities', 'alias');
		$actual = $query->prepare($qb, 'alias');

		$this->assertEquals($expected, $actual);
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testBuildQueryWithAccessContraint() {

		$parts = [];

		$access = new AccessWhereClause();
		$access->viewer_guid = 5;
		$parts[] = $access->prepare($this->qb, 'alias');

		$parts[] = $this->qb->expr()->eq('alias.guid', ':qb4');
		$this->qb->param(1, ELGG_VALUE_INTEGER);

		$expected = $this->qb->merge($parts);

		$query = new EntityWhereClause();
		$query->viewer_guid = 5;
		$query->guids = 1;

		$qb = Select::fromTable('entities', 'alias');
		$actual = $query->prepare($qb, 'alias');

		$this->assertEquals($expected, $actual);
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}
}

