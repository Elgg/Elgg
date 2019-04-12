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
	
	/**
	 * @dataProvider timezoneProvider
	 */
	public function testSetTimeAfterNormalize($timezone) {
		
		$tz = date_default_timezone_get();
		date_default_timezone_set($timezone);
		
		$time = mktime(10, 15, 30, 1, 15, 2019); // 2019-01-15 10:15:30
		$dt = Values::normalizeTime($time);
		$dt->setTime(10, 15, 30);
		
		date_default_timezone_set($tz);
		
		$this->assertEquals($time, $dt->getTimestamp());
	}
	
	public function timezoneProvider() {
		return [
			['UTC'],
			['Australia/Adelaide'],
			['Europe/Amsterdam'],
			['America/New_York'],
		];
	}
}
