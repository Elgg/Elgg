<?php

namespace Elgg\Mocks;

use Elgg\Database\Delete;
use Elgg\Database\Insert;
use Elgg\Database\Select;
use Elgg\Database\Update;
use Elgg\Exceptions\DatabaseException;

/**
 * @group Mocks
 * @group UnitTests
 */
class DatabaseUnitTest extends \Elgg\UnitTestCase {

	public function testThrowsWithUnknownInsertSpec() {
		$this->expectException(DatabaseException::class);
		elgg()->db->insertData(Insert::intoTable('B'));
	}

	public function testThrowsWithUnknownUpdateSpec() {
		$this->expectException(DatabaseException::class);
		elgg()->db->updateData(Update::table('B'));
	}

	public function testThrowsWithUnknownDeleteSpec() {
		$this->expectException(DatabaseException::class);
		elgg()->db->deleteData(Delete::fromTable('B'));
	}

	public function testCanInsertData() {
		$insert123 = Insert::intoTable('A');
		$insert123->values([
			'name' => $insert123->param('foo', ELGG_VALUE_STRING),
		]);

		$insert0 = Insert::intoTable('A');
		$insert0->values([
			'name' => $insert0->param('bar', ELGG_VALUE_STRING),
		]);
		
		_elgg_services()->db->addQuerySpec([
			'sql' => $insert123->getSQL(),
			'params' => $insert123->getParameters(),
			'insert_id' => 123,
		]);

		_elgg_services()->db->addQuerySpec([
			'sql' => $insert0->getSQL(),
			'params' => $insert0->getParameters(),
		]);

		$this->assertEquals(123, elgg()->db->insertData($insert123));
		$this->assertEquals(0, elgg()->db->insertData($insert0));
	}

	public function testCanUpdateData() {

		$update20 = Update::table('A');
		$update20->set('b', 'b');
		$update20->where($update20->compare('c', '=', 'c'));
		
		_elgg_services()->db->addQuerySpec([
			'sql' => $update20->getSQL(),
			'params' => $update20->getParameters(),
			'row_count' => 20,
		]);

		$update0 = Update::table('A');
		$update0->set('b', 'b');
		$update0->where($update0->compare('d', '=', 'd'));
		
		_elgg_services()->db->addQuerySpec([
			'sql' => $update0->getSQL(),
			'params' => $update0->getParameters(),
			'row_count' => 0,
		]);

		$this->assertTrue(elgg()->db->updateData($update20, false));

		$this->assertEquals(20, elgg()->db->updateData($update20, true));

		$this->assertTrue(elgg()->db->updateData($update0, false));

		$this->assertEquals(0, elgg()->db->updateData($update0, true));
	}

	public function testCanDeleteData() {
		
		$delete20 = Delete::fromTable('A');
		$delete20->where($delete20->compare('b', '=', 'b'));
		
		_elgg_services()->db->addQuerySpec([
			'sql' => $delete20->getSQL(),
			'params' => $delete20->getParameters(),
			'row_count' => 20,
		]);

		$delete0 = Delete::fromTable('A');
		$delete0->where($delete0->compare('c', '=', 'c'));
		
		_elgg_services()->db->addQuerySpec([
			'sql' => $delete0->getSQL(),
			'params' => $delete0->getParameters(),
		]);

		$this->assertEquals(20, elgg()->db->deleteData($delete20));
		$this->assertEquals(0, elgg()->db->deleteData($delete0));
	}

	public function testCanGetData() {

		$select = Select::fromTable('A');
		$select->where($select->compare('foo', '=', 'bar1'));
		
		$data = [
			[
				'id' => 1,
				'foo' => 'bar1',
			],
			[
				'id' => 2,
				'foo' => 'bar2',
			],
			[
				'id' => 3,
				'foo' => 'bar1',
			]
		];
		
		_elgg_services()->db->addQuerySpec([
			'sql' => $select->getSQL(),
			'params' => $select->getParameters(),
			'results' => function() use ($data) {
			$results = [];
				foreach ($data as $elem) {
					if ($elem['foo'] == 'bar1') {
						$results[] = (object) $elem;
					}
				}
				return $results;
			}
		]);


		$this->assertEquals([$data[0], $data[2]], elgg()->db->getData($select, [$this, 'rowToArray']));
		$this->assertEquals($data[0], elgg()->db->getDataRow($select, [$this, 'rowToArray']));
		
	}

	function rowToArray($row) {
		return (array) $row;
	}
}
