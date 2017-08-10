<?php
/**
 * Access SQL tests
 *
 * @package Elgg
 * @subpackage Test
 */
class ElggCoreAccessSQLTest extends \Elgg\LegacyIntegrationTestCase {

	/** @var \ElggUser */
	protected $user;
	
	/**
	 * Called before each test object.
	 */
	public function setUp() {

		parent::setUp();

		$this->user = $this->createUser();

		_elgg_services()->hooks->backup();
	}

	/**
	 * Called after each test method.
	 */
	public function tearDown() {
		_elgg_services()->hooks->restore();

		$this->user->delete();

		parent::tearDown();
	}


	public function testAdminAccess() {
		// we know an admin is logged in when running the tests
		$sql = _elgg_get_access_where_sql();
		$ans = "((1 = 1) AND (e.enabled = 'yes'))";
		$this->assertTrue($this->assertSqlEqual($ans, $sql), "$sql does not match $ans");
	}

	public function testTurningEnabledOff() {
		$sql = _elgg_get_access_where_sql(['use_enabled_clause' => false]);
		$ans = "((1 = 1))";
		$this->assertTrue($this->assertSqlEqual($ans, $sql), "$sql does not match $ans");
	}

	public function testNonAdminUser() {
		$sql = _elgg_get_access_where_sql(['user_guid' => $this->user->guid]);

		$friends_clause = $this->getFriendsClause($this->user->guid, 'e');
		$owner_clause = $this->getOwnerClause($this->user->guid, 'e');
		$access_clause = $this->getLoggedInAccessListClause('e');
		$ans = "(($friends_clause OR $owner_clause OR $access_clause) AND (e.enabled = 'yes'))";

		$this->assertTrue($this->assertSqlEqual($ans, $sql), "$sql does not match $ans");
	}

	public function testCustomTableAlias() {
		$sql = _elgg_get_access_where_sql([
			'user_guid' => $this->user->guid,
			'table_alias' => 'foo',
		]);

		$friends_clause = $this->getFriendsClause($this->user->guid, 'foo');
		$owner_clause = $this->getOwnerClause($this->user->guid, 'foo');
		$access_clause = $this->getLoggedInAccessListClause('foo');
		$ans = "(($friends_clause OR $owner_clause OR $access_clause) AND (foo.enabled = 'yes'))";

		$this->assertTrue($this->assertSqlEqual($ans, $sql), "$sql does not match $ans");

		// test with no alias
		$sql = _elgg_get_access_where_sql([
			'user_guid' => $this->user->guid,
			'table_alias' => '',
		]);

		$friends_clause = $this->getFriendsClause($this->user->guid, '');
		$owner_clause = $this->getOwnerClause($this->user->guid, '');
		$access_clause = $this->getLoggedInAccessListClause('');
		$ans = "(($friends_clause OR $owner_clause OR $access_clause) AND (enabled = 'yes'))";

		$this->assertTrue($this->assertSqlEqual($ans, $sql), "$sql does not match $ans");
	}

	public function testCustomOwnerGuidColumn() {
		$sql = _elgg_get_access_where_sql([
			'user_guid' => $this->user->guid,
			'owner_guid_column' => 'unit_test',
		]);

		$friends_clause = $this->getFriendsClause($this->user->guid, 'e', 'unit_test');
		$owner_clause = $this->getOwnerClause($this->user->guid, 'e', 'unit_test');
		$access_clause = $this->getLoggedInAccessListClause('e');
		$ans = "(($friends_clause OR $owner_clause OR $access_clause) AND (e.enabled = 'yes'))";

		$this->assertTrue($this->assertSqlEqual($ans, $sql), "$sql does not match $ans");
	}

	public function testLoggedOutUser() {
		$sp = _elgg_services();
		$original_session = $sp->session;
		$original_access = $sp->accessCollections;
		$sp->setValue('session', \ElggSession::getMock());
		$sp->setValue('accessCollections', new \Elgg\Database\AccessCollections(
			$sp->config, $sp->db, $sp->entityTable, $sp->accessCache, $sp->hooks, $sp->session, $sp->translator
		));

		$sql = _elgg_get_access_where_sql();
		$access_clause = $this->getLoggedOutAccessListClause('e');
		$ans = "(($access_clause) AND (e.enabled = 'yes'))";

		$this->assertTrue($this->assertSqlEqual($ans, $sql), "$sql does not match $ans");

		$sp->setValue('session', $original_session);
		$sp->setValue('accessCollections', $original_access);
	}

	public function testAccessPluginHookRemoveEnabled() {
		elgg_register_plugin_hook_handler('get_sql', 'access', [$this, 'removeEnabledCallback']);
		$sql = _elgg_get_access_where_sql();
		$ans = "((1 = 1))";
		$this->assertTrue($this->assertSqlEqual($ans, $sql), "$sql does not match $ans");
	}

	public function removeEnabledCallback($hook, $type, $clauses, $params) {
		$clauses['ands'] = [];
		return $clauses;
	}

	public function testAccessPluginHookRemoveOrs() {
		elgg_register_plugin_hook_handler('get_sql', 'access', [$this, 'removeOrsCallback']);
		$sql = _elgg_get_access_where_sql();
		$ans = "((e.enabled = 'yes'))";
		$this->assertTrue($this->assertSqlEqual($ans, $sql), "$sql does not match $ans");
	}

	public function removeOrsCallback($hook, $type, $clauses, $params) {
		$clauses['ors'] = [];
		return $clauses;
	}
	
	public function testAccessPluginHookAddOr() {
		elgg_register_plugin_hook_handler('get_sql', 'access', [$this, 'addOrCallback']);
		$sql = _elgg_get_access_where_sql();
		$ans = "((1 = 1 OR 57 > 32) AND (e.enabled = 'yes'))";
		$this->assertTrue($this->assertSqlEqual($ans, $sql), "$sql does not match $ans");
	}

	public function addOrCallback($hook, $type, $clauses, $params) {
		$clauses['ors'][] = '57 > 32';
		return $clauses;
	}

	public function testAccessPluginHookAddAnd() {
		elgg_register_plugin_hook_handler('get_sql', 'access', [$this, 'addAndCallback']);
		$sql = _elgg_get_access_where_sql();
		$ans = "((1 = 1) AND (e.enabled = 'yes' AND 57 > 32))";
		$this->assertTrue($this->assertSqlEqual($ans, $sql), "$sql does not match $ans");
	}

	public function testHasAccessToEntity() {
		$session = elgg_get_session();
		$test_user = $session->getLoggedInUser();

		$object = new ElggObject();
		$object->access_id = ACCESS_PRIVATE;
		$object->save();

		$session->removeLoggedInUser();
		$this->assertFalse(has_access_to_entity($object));
		$this->assertFalse(has_access_to_entity($object, $this->user));
		$session->setLoggedInUser($test_user);

		$object->access_id = ACCESS_PUBLIC;
		$object->save();

		$session->removeLoggedInUser();
		$this->assertTrue(has_access_to_entity($object));
		$this->assertTrue(has_access_to_entity($object, $this->user));
		$session->setLoggedInUser($test_user);

		$object->access_id = ACCESS_LOGGED_IN;
		$object->save();

		$session->removeLoggedInUser();
		$this->assertFalse(has_access_to_entity($object));
		$this->assertTrue(has_access_to_entity($object, $this->user));
		$session->setLoggedInUser($test_user);

		$test_user->addFriend($this->user->guid);

		$object->access_id = ACCESS_FRIENDS;
		$object->save();

		$session->removeLoggedInUser();
		$this->assertFalse(has_access_to_entity($object));
		$this->assertTrue(has_access_to_entity($object, $this->user));
		$session->setLoggedInUser($test_user);

		$test_user->removeFriend($this->user->guid);
		$object->delete();
	}

	public function addAndCallback($hook, $type, $clauses, $params) {
		$clauses['ands'][] = '57 > 32';
		return $clauses;
	}

	protected function assertSqlEqual($sql1, $sql2) {
		$sql1 = preg_replace('/\s+/', '', $sql1);
		$sql2 = preg_replace('/\s+/', '', $sql2);
		return $sql1 === $sql2;
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
}
