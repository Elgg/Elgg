<?php

namespace Elgg\Mocks;

use Elgg\Exceptions\DatabaseException;

/**
 * @group Mocks
 * @group UnitTests
 */
class DatabaseUnitTest extends \Elgg\UnitTestCase {

	public function up() {

	}

	public function down() {

	}

	public function testThrowsWithUnknownInsertSpec() {
		$this->expectException(DatabaseException::class);
		elgg()->db->insertData('INSERT INTO B');
	}

	public function testThrowsWithUnknownUpdateSpec() {
		$this->expectException(DatabaseException::class);
		elgg()->db->updateData('UPDATE B');
	}

	public function testThrowsWithUnknownDeleteSpec() {
		$this->expectException(DatabaseException::class);
		elgg()->db->deleteData('DELETE FROM B');
	}

	public function testCanInsertData() {

		_elgg_services()->db->addQuerySpec([
			'sql' => 'INSERT INTO A WHERE b = :b',
			'params' => [
				'b' => 'b',
			],
			'insert_id' => 123,
		]);

		_elgg_services()->db->addQuerySpec([
			'sql' => 'INSERT INTO A WHERE c = :c',
			'params' => [
				'c' => 'c',
			],
		]);

		$this->assertEquals(123, elgg()->db->insertData('INSERT INTO A WHERE b = :b', ['b' => 'b']));
		$this->assertEquals(0, elgg()->db->insertData('INSERT INTO A WHERE c = :c', ['c' => 'c']));
	}

	public function testCanUpdateData() {

		_elgg_services()->db->addQuerySpec([
			'sql' => 'UPDATE A SET b = :b WHERE c = :c',
			'params' => [
				'b' => 'b',
				'c' => 'c'
			],
			'row_count' => 20,
		]);

		_elgg_services()->db->addQuerySpec([
			'sql' => 'UPDATE A SET b = :b WHERE d = :d',
			'params' => [
				'b' => 'b',
				'd' => 'd'
			],
			'row_count' => 0,
		]);

		$this->assertTrue(elgg()->db->updateData('UPDATE A SET b = :b WHERE c = :c', false, [
			'b' => 'b',
			'c' => 'c'
		]));

		$this->assertEquals(20, elgg()->db->updateData('UPDATE A SET b = :b WHERE c = :c', true, [
			'b' => 'b',
			'c' => 'c'
		]));

		$this->assertTrue(elgg()->db->updateData('UPDATE A SET b = :b WHERE d = :d', false, [
			'b' => 'b',
			'd' => 'd'
		]));

		$this->assertEquals(0, elgg()->db->updateData('UPDATE A SET b = :b WHERE d = :d', true, [
			'b' => 'b',
			'd' => 'd'
		]));
	}

	public function testCanDeleteData() {

		_elgg_services()->db->addQuerySpec([
			'sql' => 'DELETE FROM A WHERE b = :b',
			'params' => [
				'b' => 'b',
			],
			'row_count' => 20,
		]);

		_elgg_services()->db->addQuerySpec([
			'sql' => 'DELETE FROM A WHERE c = :c',
			'params' => [
				'c' => 'c',
			],
		]);

		$this->assertEquals(20, elgg()->db->deleteData('DELETE FROM A WHERE b = :b', [':b' => 'b']));
		$this->assertEquals(0, elgg()->db->deleteData('DELETE FROM A WHERE c = :c', [':c' => 'c']));
	}

	public function testCanGetData() {

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
			'sql' => 'SELECT FROM A WHERE foo = :foo',
			'params' => [
				'foo' => 'bar1',
			],
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


		$this->assertEquals([$data[0], $data[2]], elgg()->db->getData('SELECT FROM A WHERE foo = :foo', [$this, 'rowToArray'], ['foo' => 'bar1']));
		$this->assertEquals($data[0], elgg()->db->getDataRow('SELECT FROM A WHERE foo = :foo', [$this, 'rowToArray'], ['foo' => 'bar1']));
		
	}

	function rowToArray($row) {
		return (array) $row;
	}
}
