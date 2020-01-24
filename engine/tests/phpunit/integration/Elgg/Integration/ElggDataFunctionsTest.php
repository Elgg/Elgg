<?php

namespace Elgg\Integration;

use ElggUser;

/**
 * @group IntegrationTests
 */
class ElggDataFunctionsTest extends \Elgg\IntegrationTestCase {

	/**
	 * @var string
	 */
	private $prefix;

	/**
	 * @var ElggUser
	 */
	private $user;

	public function up() {
		$this->prefix = _elgg_services()->db->prefix;

		$users = elgg_get_entities([
			'type' => 'user',
			'limit' => 1,
			'order_by' => 'e.guid ASC',
		]);
		$this->user = $users[0];
	}

	public function down() {

	}

	public function testCanGetData() {
		$row1 = get_data("
			SELECT *
			FROM {$this->prefix}entities
			WHERE guid = {$this->user->guid}
		");
		$row1 = $row1[0];

		$row2 = get_data("
			SELECT *
			FROM {$this->prefix}entities
			WHERE guid = ?
		", null, [$this->user->guid]);
		$row2 = $row2[0];

		$row3 = get_data("
			SELECT *
			FROM {$this->prefix}entities
			WHERE guid = :guid
		", null, [
			':guid' => $this->user->guid,
		]);
		$row3 = $row3[0];

		$row4 = get_data("
			SELECT *
			FROM {$this->prefix}entities
			WHERE guid = :guid
		", function ($row) {
			return (array)$row;
		}, [
			':guid' => $this->user->guid,
		]);
		$row4 = $row4[0];

		$this->assertInstanceOf(\stdClass::class, $row1);
		$this->assertSame($this->user->type, $row1->type);
		$this->assertEquals($row1, $row2);
		$this->assertEquals($row1, $row3);
		$this->assertIsArray($row4);
		$this->assertEquals((array)$row1, $row4);
	}

	public function testCanGetDataRow() {
		$row1 = get_data_row("
			SELECT *
			FROM {$this->prefix}entities
			WHERE guid = {$this->user->guid}
		");

		$row2 = get_data_row("
			SELECT *
			FROM {$this->prefix}entities
			WHERE guid = ?
		", null, [$this->user->guid]);

		$row3 = get_data_row("
			SELECT *
			FROM {$this->prefix}entities
			WHERE guid = :guid
		", null, [
			':guid' => $this->user->guid,
		]);

		$this->assertInstanceOf(\stdClass::class, $row1);
		$this->assertEquals($this->user->guid, $row1->guid);
		$this->assertEquals($row1, $row2);
		$this->assertEquals($row1, $row3);
	}

	public function testCanInsert() {
		$time = time();

		$id1 = insert_data("
			INSERT INTO {$this->prefix}entity_relationships
			       (guid_one, relationship, guid_two, time_created)
			VALUES ({$this->user->guid}, 'test_self1', {$this->user->guid}, $time)
			ON DUPLICATE KEY UPDATE time_created = $time
		");
		$id2 = insert_data("
			INSERT INTO {$this->prefix}entity_relationships
			       (guid_one, relationship, guid_two, time_created)
			VALUES (:guid1,   :rel,         :guid2,   :time)
			ON DUPLICATE KEY UPDATE time_created = :time
		", [
			':guid1' => $this->user->guid,
			':guid2' => $this->user->guid,
			':rel' => 'test_self2',
			':time' => $time,
		]);

		$rows = get_data("
			SELECT *
			FROM {$this->prefix}entity_relationships
			WHERE guid_one = ?
			  AND guid_two = ?
			  AND time_created = ?
			ORDER BY id ASC
		", null, [$this->user->guid, $this->user->guid, $time]);

		$this->assertIsInt($id1);
		$this->assertIsInt($id2);
		$this->assertEquals($id1, $rows[0]->id);
		$this->assertEquals($id2, $rows[1]->id);

		remove_entity_relationship($this->user->guid, 'test_self1', $this->user->guid);
		remove_entity_relationship($this->user->guid, 'test_self2', $this->user->guid);
	}

	public function testCanUpdate() {
		add_entity_relationship($this->user->guid, 'test_self1', $this->user->guid);
		$rel = check_entity_relationship($this->user->guid, 'test_self1', $this->user->guid);

		$res = update_data("
			UPDATE {$this->prefix}entity_relationships
			SET relationship = 'test_self2'
			WHERE id = {$rel->id}
		");
		$rel = get_relationship($rel->id);

		$this->assertTrue($res);
		$this->assertInstanceOf(\ElggRelationship::class, $rel);
		$this->assertEquals('test_self2', $rel->relationship);

		$num_rows = update_data("
			UPDATE {$this->prefix}entity_relationships
			SET relationship = 'test_self3'
			WHERE id = {$rel->id}
		", [], true);
		$rel = get_relationship($rel->id);

		$this->assertEquals(1, $num_rows);
		$this->assertInstanceOf(\ElggRelationship::class, $rel);
		$this->assertEquals('test_self3', $rel->relationship);

		$num_rows = update_data("
			UPDATE {$this->prefix}entity_relationships
			SET relationship = :rel
			WHERE id = :id
		", [
			':rel' => 'test_self4',
			':id' => $rel->id,
		], true);
		$rel = get_relationship($rel->id);

		$this->assertEquals(1, $num_rows);
		$this->assertEquals('test_self4', $rel->relationship);

		$rel->delete();
	}

	public function testCanDelete() {
		$new_rel = function () {
			add_entity_relationship($this->user->guid, 'test_self1', $this->user->guid);
			return check_entity_relationship($this->user->guid, 'test_self1', $this->user->guid);
		};

		$rel = $new_rel();
		$res = delete_data("
			DELETE FROM {$this->prefix}entity_relationships
			WHERE id = {$rel->id}
		");
		$this->assertEquals(1, $res);
		$this->assertFalse(check_entity_relationship($this->user->guid, 'test_self1', $this->user->guid));

		$rel = $new_rel();
		$res = delete_data("
			DELETE FROM {$this->prefix}entity_relationships
			WHERE id = :id
		", [
			':id' => $rel->id,
		]);
		$this->assertEquals(1, $res);
		$this->assertFalse(check_entity_relationship($this->user->guid, 'test_self1', $this->user->guid));
	}

	/**
	 * @dataProvider canSanitizeProvider
	 */
	public function testCanSanitize($input, $expected) {
		$this->assertEquals($expected, sanitize_string($input));
	}
	
	public function canSanitizeProvider() {
		return [
			["'" , "\\'"],
			["\"", "\\\""],
			["\\", "\\\\"],
			["\n", "\\n"],
			["\r", "\\r"],
		];
	}

	public function testSanitizeRejectsArrays() {
		$this->expectException(\DatabaseException::class);
		$this->expectExceptionMessage('Elgg\Database::sanitizeString() and serialize_string() cannot accept arrays.');
		sanitise_string(['foo']);
	}

	public function testCanDelayQuery() {
		$sql = "
			SELECT *
			FROM {$this->prefix}entities
			WHERE guid = :guid
		";
		$params = [
			':guid' => $this->user->guid,
		];

		// capture what's passed to callback
		$captured = null;
		$callback = function ($stmt) use (&$captured) {
			$captured = $stmt;
			return $stmt;
		};
		execute_delayed_read_query($sql, $callback, $params);

		_elgg_services()->db->executeDelayedQueries();

		/* @var \Doctrine\DBAL\Driver\Statement $captured */

		$this->assertInstanceOf(\Doctrine\DBAL\Driver\Statement::class, $captured);

		$rows = $captured->fetchAll(\PDO::FETCH_OBJ);
		$this->assertEquals($this->user->guid, $rows[0]->guid);
	}
}
