<?php

namespace Elgg\Mocks;

/**
 * @group Mocks
 * @group UnitTests
 */
class DatabaseUnitTest extends \Elgg\UnitTestCase {

	public function up() {

	}

	public function down() {

	}

	/**
	 * @expectedException \DatabaseException
	 */
	public function testThrowsWithUnknownInsertSpec() {
		insert_data('INSERT INTO B');
	}

	/**
	 * @expectedException \DatabaseException
	 */
	public function testThrowsWithUnknownUpdateSpec() {
		update_data('UPDATE B');
	}

	/**
	 * @expectedException \DatabaseException
	 */
	public function testThrowsWithUnknownDeleteSpec() {
		delete_data('DELETE FROM B');
	}

	public function testCanInsertData() {

		_elgg_services()->db->addQuerySpec([
			'sql' => 'INSERT INTO A WHERE b = :b',
			'params' => [
				':b' => 'b',
			],
			'insert_id' => 123,
		]);

		_elgg_services()->db->addQuerySpec([
			'sql' => 'INSERT INTO A WHERE c = :c',
			'params' => [
				':c' => 'c',
			],
		]);

		$this->assertEquals(123, insert_data('INSERT INTO A WHERE b = :b', [':b' => 'b']));
		$this->assertEquals(0, insert_data('INSERT INTO A WHERE c = :c', [':c' => 'c']));
	}

	public function testCanUpdateData() {

		_elgg_services()->db->addQuerySpec([
			'sql' => 'UPDATE A SET b = :b WHERE c = :c',
			'params' => [
				':b' => 'b',
				':c' => 'c'
			],
			'row_count' => 20,
		]);

		_elgg_services()->db->addQuerySpec([
			'sql' => 'UPDATE A SET b = :b WHERE d = :d',
			'params' => [
				':b' => 'b',
				':d' => 'd'
			],
			'row_count' => 0,
		]);

		$this->assertTrue(update_data('UPDATE A SET b = :b WHERE c = :c', [
			':b' => 'b',
			':c' => 'c'
		]));

		$this->assertEquals(20, update_data('UPDATE A SET b = :b WHERE c = :c', [
			':b' => 'b',
			':c' => 'c'
		], true));

		$this->assertTrue(update_data('UPDATE A SET b = :b WHERE d = :d', [
			':b' => 'b',
			':d' => 'd'
		]));

		$this->assertEquals(0, update_data('UPDATE A SET b = :b WHERE d = :d', [
			':b' => 'b',
			':d' => 'd'
		], true));
	}

	public function testCanDeleteData() {

		_elgg_services()->db->addQuerySpec([
			'sql' => 'DELETE FROM A WHERE b = :b',
			'params' => [
				':b' => 'b',
			],
			'row_count' => 20,
		]);

		_elgg_services()->db->addQuerySpec([
			'sql' => 'DELETE FROM A WHERE c = :c',
			'params' => [
				':c' => 'c',
			],
		]);

		$this->assertEquals(20, delete_data('DELETE FROM A WHERE b = :b', [':b' => 'b']));
		$this->assertEquals(0, delete_data('DELETE FROM A WHERE c = :c', [':c' => 'c']));
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
				':foo' => 'bar1',
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


		$this->assertEquals([$data[0], $data[2]], get_data('SELECT FROM A WHERE foo = :foo', [$this, 'rowToArray'], [':foo' => 'bar1']));
		$this->assertEquals($data[0], get_data_row('SELECT FROM A WHERE foo = :foo', [$this, 'rowToArray'], [':foo' => 'bar1']));
		
	}

	function rowToArray($row) {
		return (array) $row;
	}
}
