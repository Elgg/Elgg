<?php

namespace Elgg\Integration;

use Elgg\LegacyIntegrationTestCase;

/**
 * Access SQL tests
 *
 * @group IntegrationTests
 */
class ElggCoreAccessSQLTest extends LegacyIntegrationTestCase {

	/** @var \ElggUser */
	protected $user;

	public function up() {
		$this->user = $this->createOne('user');
		_elgg_services()->session->setLoggedInUser($this->user);
		_elgg_services()->hooks->backup();
	}

	public function down() {
		_elgg_services()->session->removeLoggedInUser();
		$this->user->delete();
		_elgg_services()->hooks->restore();
	}

	public function testCanBuildAccessSqlClausesWithIgnoredAccess() {
		$sql = _elgg_get_access_where_sql([
			'ignore_access' => true,
		]);
		$ans = "((1 = 1) AND (e.enabled = 'yes'))";
		$this->assertSqlEqual($ans, $sql, "$sql does not match $ans");
	}

	public function testCanBuildAccessSqlClausesWithIgnoredAccessWithoutDisabledEntities() {
		$sql = _elgg_get_access_where_sql([
			'use_enabled_clause' => false,
			'ignore_access' => true,
		]);
		$ans = "((1 = 1))";
		$this->assertSqlEqual($ans, $sql, "$sql does not match $ans");
	}

	public function testCanBuildAccessSqlForLoggedInUser() {

		$this->assertFalse(elgg_is_admin_logged_in());

		$sql = _elgg_get_access_where_sql();

		$friends_clause = $this->getFriendsClause($this->user->guid, 'e');
		$owner_clause = $this->getOwnerClause($this->user->guid, 'e');
		$access_clause = $this->getLoggedInAccessListClause('e');

		$ans = "(($friends_clause OR $owner_clause OR $access_clause) AND (e.enabled = 'yes'))";

		$this->assertSqlEqual($ans, $sql, "$sql does not match $ans");
	}

	public function testCanBuildAccessSqlWithCustomTableAlias() {
		$sql = _elgg_get_access_where_sql([
			'table_alias' => 'foo',
		]);

		$friends_clause = $this->getFriendsClause($this->user->guid, 'foo');
		$owner_clause = $this->getOwnerClause($this->user->guid, 'foo');
		$access_clause = $this->getLoggedInAccessListClause('foo');
		$ans = "(($friends_clause OR $owner_clause OR $access_clause) AND (foo.enabled = 'yes'))";

		$this->assertSqlEqual($ans, $sql, "$sql does not match $ans");

		// test with no alias
		$sql = _elgg_get_access_where_sql([
			'user_guid' => $this->user->guid,
			'table_alias' => '',
		]);

		$friends_clause = $this->getFriendsClause($this->user->guid, '');
		$owner_clause = $this->getOwnerClause($this->user->guid, '');
		$access_clause = $this->getLoggedInAccessListClause('');
		$ans = "(($friends_clause OR $owner_clause OR $access_clause) AND (enabled = 'yes'))";

		$this->assertSqlEqual($ans, $sql, "$sql does not match $ans");
	}

	public function testCanBuildAccessSqlWithCustomGuidColumn() {
		$sql = _elgg_get_access_where_sql([
			'owner_guid_column' => 'unit_test',
		]);

		$friends_clause = $this->getFriendsClause($this->user->guid, 'e', 'unit_test');
		$owner_clause = $this->getOwnerClause($this->user->guid, 'e', 'unit_test');
		$access_clause = $this->getLoggedInAccessListClause('e');
		$ans = "(($friends_clause OR $owner_clause OR $access_clause) AND (e.enabled = 'yes'))";

		$this->assertSqlEqual($ans, $sql, "$sql does not match $ans");
	}

	public function testCanBuildAccessSqlForLoggedOutUser() {

		$user = _elgg_services()->session->getLoggedInUser();
		_elgg_services()->session->removeLoggedInUser();

		$sql = _elgg_get_access_where_sql();
		$access_clause = $this->getLoggedOutAccessListClause('e');
		$ans = "(($access_clause) AND (e.enabled = 'yes'))";

		$this->assertSqlEqual($ans, $sql, "$sql does not match $ans");

		_elgg_services()->session->setLoggedInUser($user);
	}

	public function testAccessPluginHookRemoveEnabled() {
		elgg_register_plugin_hook_handler('get_sql', 'access', [
			$this,
			'removeEnabledCallback'
		]);
		$sql = _elgg_get_access_where_sql([
			'ignore_access' => true,
		]);
		$ans = "((1 = 1))";
		$this->assertSqlEqual($ans, $sql, "$sql does not match $ans");
	}

	public function removeEnabledCallback($hook, $type, $clauses, $params) {
		$clauses['ands'] = [];

		return $clauses;
	}

	public function testAccessPluginHookRemoveOrs() {
		elgg_register_plugin_hook_handler('get_sql', 'access', [
			$this,
			'removeOrsCallback'
		]);
		$sql = _elgg_get_access_where_sql([
			'ignore_access' => true,
		]);
		$ans = "((e.enabled = 'yes'))";
		$this->assertSqlEqual($ans, $sql, "$sql does not match $ans");
	}

	public function removeOrsCallback($hook, $type, $clauses, $params) {
		$clauses['ors'] = [];

		return $clauses;
	}

	public function testAccessPluginHookAddOr() {
		elgg_register_plugin_hook_handler('get_sql', 'access', [
			$this,
			'addOrCallback'
		]);
		$sql = _elgg_get_access_where_sql([
			'ignore_access' => true,
		]);
		$ans = "((1 = 1 OR 57 > 32) AND (e.enabled = 'yes'))";
		$this->assertSqlEqual($ans, $sql, "$sql does not match $ans");
	}

	public function addOrCallback($hook, $type, $clauses, $params) {
		$clauses['ors'][] = '57 > 32';

		return $clauses;
	}

	public function testAccessPluginHookAddAnd() {
		elgg_register_plugin_hook_handler('get_sql', 'access', [
			$this,
			'addAndCallback'
		]);
		$sql = _elgg_get_access_where_sql([
			'ignore_access' => true,
		]);
		$ans = "((1 = 1) AND (e.enabled = 'yes' AND 57 > 32))";
		$this->assertSqlEqual($ans, $sql, "$sql does not match $ans");
	}

	public function testHasAccessToEntity() {

		$session = elgg_get_session();

		$viewer = $session->getLoggedInUser();

		$ia = elgg_set_ignore_access(true);

		$owner = $this->createOne('user');

		$object = $this->createOne('object', [
			'owner_guid' => $owner->guid,
			'access_id' => ACCESS_PRIVATE,
		]);

		elgg_set_ignore_access($ia);

		$session->removeLoggedInUser();

		$this->assertFalse(has_access_to_entity($object));
		$this->assertFalse(has_access_to_entity($object, $viewer));
		$this->assertTrue(has_access_to_entity($object, $owner));

		$ia = elgg_set_ignore_access(true);
		$object->access_id = ACCESS_PUBLIC;
		$object->save();
		elgg_set_ignore_access($ia);

		$this->assertTrue(has_access_to_entity($object));
		$this->assertTrue(has_access_to_entity($object, $viewer));
		$this->assertTrue(has_access_to_entity($object, $owner));

		$ia = elgg_set_ignore_access(true);
		$object->access_id = ACCESS_LOGGED_IN;
		$object->save();
		elgg_set_ignore_access($ia);

		$this->assertFalse(has_access_to_entity($object));
		// even though user is logged out, existing users are presumed to have access to an entity
		$this->assertTrue(has_access_to_entity($object, $viewer));
		$this->assertTrue(has_access_to_entity($object, $owner));

		$session->setLoggedInUser($viewer);
		$this->assertTrue(has_access_to_entity($object));
		$this->assertTrue(has_access_to_entity($object, $viewer));
		$this->assertTrue(has_access_to_entity($object, $owner));
		$session->removeLoggedInUser();

		$ia = elgg_set_ignore_access(true);
		$owner->addFriend($viewer->guid);
		$object->access_id = ACCESS_FRIENDS;
		$object->save();
		elgg_set_ignore_access($ia);

		$this->assertFalse(has_access_to_entity($object));
		$this->assertTrue(has_access_to_entity($object, $viewer));
		$this->assertTrue(has_access_to_entity($object, $owner));

		$session->setLoggedInUser($viewer);
		$this->assertTrue(has_access_to_entity($object));
		$this->assertTrue(has_access_to_entity($object, $viewer));
		$this->assertTrue(has_access_to_entity($object, $owner));

		$ia = elgg_set_ignore_access(true);
		$owner->delete();
		$object->delete();
		elgg_set_ignore_access($ia);

		$session->setLoggedInUser($viewer);
	}

	public function addAndCallback($hook, $type, $clauses, $params) {
		$clauses['ands'][] = '57 > 32';

		return $clauses;
	}

	protected function assertSqlEqual($sql1, $sql2, $message = '') {
		$sql1 = $this->normalizeSql($sql1);
		$sql2 = $this->normalizeSql($sql2);

		return $this->assertEquals($sql1, $sql2, $message);
	}

	protected function getFriendsClause($user_guid, $table_alias, $owner_guid = 'owner_guid') {
		$CONFIG = _elgg_config();
		$table_alias = $table_alias ? $table_alias . '.' : '';

		return "{$table_alias}access_id = " . ACCESS_FRIENDS . "
			AND {$table_alias}{$owner_guid} IN (
				SELECT guid_one FROM {$CONFIG->dbprefix}entity_relationships
				WHERE relationship = 'friend' AND guid_two = $user_guid
			)";
	}

	protected function getOwnerClause($user_guid, $table_alias, $owner_guid = 'owner_guid') {
		$table_alias = $table_alias ? $table_alias . '.' : '';

		return "{$table_alias}{$owner_guid} = $user_guid";
	}

	protected function getLoggedInAccessListClause($table_alias) {
		$table_alias = $table_alias ? $table_alias . '.' : '';

		return "{$table_alias}access_id IN (2,1)";
	}

	protected function getLoggedOutAccessListClause($table_alias) {
		$table_alias = $table_alias ? $table_alias . '.' : '';

		return "{$table_alias}access_id IN (2)";
	}

	/**
	 * Attempt to normalize whitespace in a query
	 *
	 * @param string $query Query
	 * @return string
	 */
	private function normalizeSql($query) {
		$query = trim($query);
		$query = str_replace('  ', ' ', $query);
		$query = strtolower($query);
		$query = preg_replace('~\\s+~', ' ', $query);
		return $query;
	}
}
