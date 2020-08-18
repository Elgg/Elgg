<?php

namespace Elgg\Database\Clauses;

use Elgg\Database\QueryBuilder;
use Elgg\Database\Select;
use Elgg\UnitTestCase;
use ElggUser;

/**
 * @group QueryBuilder
 * @group QueryBuilderWhere
 */
class AccessWhereClauseUnitTest extends UnitTestCase {

	/**
	 * @var ElggUser
	 */
	protected $user;

	/**
	 * @var QueryBuilder
	 */
	protected $qb;

	public function up() {
		$this->qb = Select::fromTable('entities', 'alias');

		$this->user = $this->createUser();
		_elgg_services()->session->setLoggedInUser($this->user);
		_elgg_services()->hooks->backup();
	}

	public function down() {
		_elgg_services()->session->removeLoggedInUser();
		_elgg_services()->hooks->restore();
	}

	public function testCanBuildAccessSqlClausesWithIgnoredAccess() {

		$parts = [];
		$parts[] = $this->qb->expr()->eq('alias.enabled', ':qb1');
		$this->qb->param('yes', ELGG_VALUE_STRING);
		$expected = $this->qb->merge($parts);

		$query = new AccessWhereClause();
		$query->ignore_access = true;

		$qb = Select::fromTable('entities', 'alias');
		$actual = $query->prepare($qb, 'alias');

		$this->assertEquals($expected, $actual);
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testCanBuildAccessSqlClausesWithIgnoredAccessWithoutDisabledEntities() {

		$expected = null;

		$query = new AccessWhereClause();
		$query->ignore_access = true;
		$query->use_enabled_clause = false;

		$qb = Select::fromTable('entities', 'alias');
		$actual = $query->prepare($qb, 'alias');

		$this->assertEquals($expected, $actual);
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testCanBuildAccessSqlForLoggedInUser() {
		$this->assertFalse(elgg_is_admin_logged_in());

		$parts = [];

		$ors = $this->qb->merge([
			$this->getOwnerClause($this->user->guid, 'alias'),
			$this->getLoggedInAccessListClause('alias'),
		], 'OR');

		$parts[] = $this->qb->compare('alias.enabled', '=', 'yes', ELGG_VALUE_STRING);
		$parts[] = $ors;

		$expected = $this->qb->merge($parts);

		$query = new AccessWhereClause();

		$qb = Select::fromTable('entities', 'alias');
		$actual = $query->prepare($qb, 'alias');

		$this->assertEquals($expected, $actual);
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testCanBuildAccessSqlWithNoTableAlias() {

		$parts = [];

		$ors = $this->qb->merge([
			$this->getOwnerClause($this->user->guid, ''),
			$this->getLoggedInAccessListClause(''),
		], 'OR');

		$parts[] = $this->qb->compare('enabled', '=', 'yes', ELGG_VALUE_STRING);
		$parts[] = $ors;

		$expected = $this->qb->merge($parts);

		$query = new AccessWhereClause();

		$qb = Select::fromTable('entities', '');
		$actual = $query->prepare($qb, '');

		$this->assertEquals($expected, $actual);
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testCanBuildAccessSqlWithCustomGuidColumn() {
		$parts = [];

		$ors = $this->qb->merge([
			$this->getOwnerClause($this->user->guid, 'alias', 'unit_test'),
			$this->getLoggedInAccessListClause('alias'),
		], 'OR');

		$parts[] = $this->qb->compare('alias.enabled', '=', 'yes', ELGG_VALUE_STRING);
		$parts[] = $ors;

		$expected = $this->qb->merge($parts);

		$query = new AccessWhereClause();
		$query->owner_guid_column = 'unit_test';

		$qb = Select::fromTable('entities', 'alias');
		$actual = $query->prepare($qb, 'alias');

		$this->assertEquals($expected, $actual);
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testCanBuildAccessSqlForLoggedOutUser() {

		$user = _elgg_services()->session->getLoggedInUser();
		_elgg_services()->session->removeLoggedInUser();

		$parts = [];

		$ors = $this->qb->merge([
			$this->getLoggedOutAccessListClause('alias'),
		], 'OR');

		$parts[] = $this->qb->compare('alias.enabled', '=', 'yes', ELGG_VALUE_STRING);
		$parts[] = $ors;

		$expected = $this->qb->merge($parts);

		$query = new AccessWhereClause();

		$qb = Select::fromTable('entities', 'alias');
		$actual = $query->prepare($qb, 'alias');

		$this->assertEquals($expected, $actual);
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());

		_elgg_services()->session->setLoggedInUser($user);
	}

	public function testAccessPluginHookRemoveEnabled() {
		$handler = function (\Elgg\Hook $hook) {
			$clauses = $hook->getValue();
			$clauses['ands'] = [];

			return $clauses;
		};

		elgg_register_plugin_hook_handler('get_sql', 'access', $handler);

		// Even though the clause is removed, the parameter is still in the QB parameter list
		$this->qb->param('yes', ELGG_VALUE_STRING);
		$expected = null;

		$query = new AccessWhereClause();
		$query->ignore_access = true;

		$qb = Select::fromTable('entities', 'alias');
		$actual = $query->prepare($qb, 'alias');

		$this->assertEquals($expected, $actual);
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());

		elgg_unregister_plugin_hook_handler('get_sql', 'access', $handler);
	}

	public function testAccessPluginHookRemoveOrs() {
		$handler = function (\Elgg\Hook $hook) {
			$clauses = $hook->getValue();
			$clauses['ors'] = [];

			return $clauses;
		};

		elgg_register_plugin_hook_handler('get_sql', 'access', $handler);

		$parts = [];
		$parts[] = $this->qb->compare('alias.enabled', '=', 'yes', ELGG_VALUE_STRING);
		$expected = $this->qb->merge($parts);

		$query = new AccessWhereClause();
		$query->ignore_access = true;

		$qb = Select::fromTable('entities', 'alias');
		$actual = $query->prepare($qb, 'alias');

		$this->assertEquals($expected, $actual);
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());

		elgg_unregister_plugin_hook_handler('get_sql', 'access', $handler);
	}

	public function testAccessPluginHookAddOr() {
		$handler = function (\Elgg\Hook $hook) {
			$clauses = $hook->getValue();
			$qb = $hook->getParam('query_builder');
			$clauses['ors'][] = $qb->compare($qb->param(57, ELGG_VALUE_INTEGER), '>', $qb->param(37, ELGG_VALUE_INTEGER));

			return $clauses;
		};

		elgg_register_plugin_hook_handler('get_sql', 'access', $handler);

		$parts = [];
		$parts[] = $this->qb->compare('alias.enabled', '=', 'yes', ELGG_VALUE_STRING);
		$parts[] = $this->qb->merge([
			$this->qb->compare(
				$this->qb->param(57, ELGG_VALUE_INTEGER),
				'>',
				$this->qb->param(37, ELGG_VALUE_INTEGER)
			)
		], 'OR');

		$expected = $this->qb->merge($parts);

		$query = new AccessWhereClause();
		$query->ignore_access = true;

		$qb = Select::fromTable('entities', 'alias');
		$actual = $query->prepare($qb, 'alias');

		$this->assertEquals($expected, $actual);
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());

		elgg_unregister_plugin_hook_handler('get_sql', 'access', $handler);
	}

	public function testAccessPluginHookAddAnd() {
		$handler = function (\Elgg\Hook $hook) {
			$clauses = $hook->getValue();
			$qb = $hook->getParam('query_builder');
			$clauses['ands'][] = $qb->compare($qb->param(57, ELGG_VALUE_INTEGER), '>', $qb->param(37, ELGG_VALUE_INTEGER));

			return $clauses;
		};

		elgg_register_plugin_hook_handler('get_sql', 'access', $handler);

		$parts = [];
		$parts[] = $this->qb->compare('alias.enabled', '=', 'yes', ELGG_VALUE_STRING);
		$parts[] = $this->qb->compare(
			$this->qb->param(57, ELGG_VALUE_INTEGER),
			'>',
			$this->qb->param(37, ELGG_VALUE_INTEGER)
		);

		$expected = $this->qb->merge($parts);

		$query = new AccessWhereClause();
		$query->ignore_access = true;

		$qb = Select::fromTable('entities', 'alias');
		$actual = $query->prepare($qb, 'alias');

		$this->assertEquals($expected, $actual);
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());

		elgg_unregister_plugin_hook_handler('get_sql', 'access', $handler);
	}

	protected function getOwnerClause($user_guid, $table_alias, $owner_guid = 'owner_guid') {
		$alias = function ($column) use ($table_alias) {
			return $table_alias ? "{$table_alias}.{$column}" : $column;
		};

		return $this->qb->compare($alias($owner_guid), '=', $user_guid, ELGG_VALUE_INTEGER);
	}

	protected function getLoggedInAccessListClause($table_alias) {
		$alias = function ($column) use ($table_alias) {
			return $table_alias ? "{$table_alias}.{$column}" : $column;
		};

		return $this->qb->compare($alias('access_id'), '=', [ACCESS_PUBLIC, ACCESS_LOGGED_IN], ELGG_VALUE_INTEGER);
	}

	protected function getLoggedOutAccessListClause($table_alias) {
		$alias = function ($column) use ($table_alias) {
			return $table_alias ? "{$table_alias}.{$column}" : $column;
		};

		return $this->qb->compare($alias('access_id'), '=', [ACCESS_PUBLIC], ELGG_VALUE_INTEGER);
	}

}
