<?php

namespace Elgg\Integration;

use Elgg\Database\Delete;
use Elgg\Database\Insert;
use Elgg\Database\Select;
use Elgg\Database\Update;
use ElggUser;

/**
 * @group IntegrationTests
 */
class ElggDataFunctionsTest extends \Elgg\IntegrationTestCase {

	/**
	 * @var ElggUser
	 */
	protected $user;

	public function up() {
		$this->user = $this->createUser();
	}

	public function testCanGetData() {
		
		$select = Select::fromTable('entities');
		$select->select('*');
		$select->where($select->compare('guid', '=', $this->user->guid, ELGG_VALUE_GUID));
		
		$row_as_object = elgg()->db->getData($select);
		$row_as_object = $row_as_object[0];

		$row_as_array = elgg()->db->getData($select, function ($row) {
			return (array) $row;
		});
		$row_as_array = $row_as_array[0];

		$this->assertInstanceOf(\stdClass::class, $row_as_object);
		$this->assertSame($this->user->type, $row_as_object->type);
		$this->assertIsArray($row_as_array);
		$this->assertEquals((array) $row_as_object, $row_as_array);
	}

	public function testCanGetDataRow() {
		$select = Select::fromTable('entities');
		$select->select('*');
		$select->where($select->compare('guid', '=', $this->user->guid, ELGG_VALUE_GUID));
		
		$row = elgg()->db->getDataRow($select);

		$this->assertInstanceOf(\stdClass::class, $row);
		$this->assertEquals($this->user->guid, $row->guid);
	}

	public function testCanInsert() {
		$time = time();
		
		$row1 = Insert::intoTable('entity_relationships');
		$row1->values([
			'guid_one' => $row1->param($this->user->guid, ELGG_VALUE_GUID),
			'relationship' => $row1->param('test_self1', ELGG_VALUE_STRING),
			'guid_two' => $row1->param($this->user->guid, ELGG_VALUE_GUID),
			'time_created' => $row1->param($time, ELGG_VALUE_TIMESTAMP),
		]);
		
		$row2 = Insert::intoTable('entity_relationships');
		$row2->values([
			'guid_one' => $row2->param($this->user->guid, ELGG_VALUE_GUID),
			'relationship' => $row2->param('test_self2', ELGG_VALUE_STRING),
			'guid_two' => $row2->param($this->user->guid, ELGG_VALUE_GUID),
			'time_created' => $row2->param($time, ELGG_VALUE_TIMESTAMP),
		]);
		
		$id1 = elgg()->db->insertData($row1);
		$id2 = elgg()->db->insertData($row2);
		
		$select = Select::fromTable('entity_relationships');
		$select->select('*');
		$select->where($select->compare('guid_one', '=', $this->user->guid, ELGG_VALUE_GUID));
		$select->andWhere($select->compare('guid_two', '=', $this->user->guid, ELGG_VALUE_GUID));
		$select->andWhere($select->compare('time_created', '=', $time, ELGG_VALUE_INTEGER));
		$select->orderBy('id', 'ASC');
		
		$rows = elgg()->db->getData($select);

		$this->assertIsInt($id1);
		$this->assertIsInt($id2);
		$this->assertEquals($id1, $rows[0]->id);
		$this->assertEquals($id2, $rows[1]->id);
	}

	public function testCanUpdate() {
		$rel_id = _elgg_services()->relationshipsTable->add($this->user->guid, 'test_self1', $this->user->guid, true);
		$this->assertIsInt($rel_id);
		
		$rel = elgg_get_relationship($rel_id);
		$this->assertInstanceOf(\ElggRelationship::class, $rel);

		$update1 = Update::table('entity_relationships');
		$update1->set('relationship', $update1->param('test_self2', ELGG_VALUE_STRING));
		$update1->where($update1->compare('id', '=', $rel->id, ELGG_VALUE_INTEGER));
		
		$this->assertTrue(elgg()->db->updateData($update1));
		
		$rel = elgg_get_relationship($rel->id);
		$this->assertInstanceOf(\ElggRelationship::class, $rel);
		$this->assertEquals('test_self2', $rel->relationship);

		$update2 = Update::table('entity_relationships');
		$update2->set('relationship', $update2->param('test_self3', ELGG_VALUE_STRING));
		$update2->where($update2->compare('id', '=', $rel->id, ELGG_VALUE_INTEGER));
		
		$num_rows = elgg()->db->updateData($update2, true);
		$this->assertEquals(1, $num_rows);
		
		$rel = elgg_get_relationship($rel->id);
		$this->assertInstanceOf(\ElggRelationship::class, $rel);
		$this->assertEquals('test_self3', $rel->relationship);
	}

	public function testCanDelete() {
		$rel_id = _elgg_services()->relationshipsTable->add($this->user->guid, 'test_self1', $this->user->guid, true);
		$this->assertIsInt($rel_id);
		
		$rel = elgg_get_relationship($rel_id);
		$this->assertInstanceOf(\ElggRelationship::class, $rel);

		$delete = Delete::fromTable('entity_relationships');
		$delete->where($delete->compare('id', '=', $rel->id, ELGG_VALUE_INTEGER));
		
		$res = elgg()->db->deleteData($delete);
		$this->assertEquals(1, $res);
		$this->assertFalse($this->user->hasRelationship($this->user->guid, 'test_self1'));
	}

	public function testCanDelayQuery() {
		$qb = Select::fromTable('entities');
		$qb->select('*');
		$qb->where($qb->compare('guid', '=', $this->user->guid, ELGG_VALUE_INTEGER));
		
		// capture what's passed to callback
		$captured = null;
		$callback = function ($stmt) use (&$captured) {
			$captured = $stmt;
			return $stmt;
		};
		
		// get a reflector to check the contents of the delayed queries property
		$database = _elgg_services()->db;
		$reflector = new \ReflectionClass($database);
		$delayed_queries = $reflector->getProperty('delayed_queries');
		$delayed_queries->setAccessible(true);
		
		$delayed_value = $delayed_queries->getValue($database); // backup
		$delayed_queries->setValue($database, []);
		
		$this->assertIsArray($delayed_queries->getValue($database));
		$this->assertEmpty($delayed_queries->getValue($database));
		
		// add query
		$database->registerDelayedQuery($qb, $callback);
		
		$this->assertIsArray($delayed_queries->getValue($database));
		$this->assertCount(1, $delayed_queries->getValue($database));

		// execute query
		$database->executeDelayedQueries();
		
		$this->assertIsArray($delayed_queries->getValue($database));
		$this->assertEmpty($delayed_queries->getValue($database));
		
		// restore old value
		$delayed_queries->setValue($database, $delayed_value);
		
		/* @var \Doctrine\DBAL\Result $captured */
		$this->assertInstanceOf(\Doctrine\DBAL\Result::class, $captured);

		$rows = $captured->fetchAllAssociative();
		$this->assertEquals($this->user->guid, $rows[0]['guid']);
	}
}
