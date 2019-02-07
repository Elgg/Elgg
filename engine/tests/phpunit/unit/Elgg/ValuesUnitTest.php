<?php

namespace Elgg;

use DateTime as PHPDateTime;
use Elgg\I18n\DateTime as ElggDateTime;

/**
 * @group Values
 */
class ValuesUnitTest extends UnitTestCase {

	public function up() {

	}

	public function down() {

	}

	/**
	 * @dataProvider timeProvider
	 */
	public function testCanNormalizeTime($time) {

		$dt = Values::normalizeTime($time);

		$this->assertInstanceOf(ElggDateTime::class, $dt);
		$this->assertEquals($dt->getTimestamp(), Values::normalizeTimestamp($time));

	}

	public function timeProvider() {
		return [
			['January 9, 2018 12:00'],
			[1515496794],
			[new PHPDateTime('+2 days')],
			[new ElggDateTime('-2 days')],
		];
	}
	
	/**
	 * @dataProvider emptyProvider
	 */
	public function testIsEmpty($value, $expected_result) {
		$this->assertEquals($expected_result, Values::isEmpty($value));
	}
	
	public function emptyProvider() {
		return [
			[0, false],
			[0.0, false],
			['0', false],
			[null, true],
			[false, true],
			[[], true],
			['', true],
			[new \stdClass(), false],
		];
	}
}
