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
	
	/**
	 * @dataProvider shortNumberProvider
	 */
	public function testCanShortenNumber($number, $precision, $expected) {
		$this->assertEquals($expected, Values::shortFormatOutput($number, $precision));
	}
	
	public function shortNumberProvider() {
		return [
			['a', 1, 'a'],
			[1, 1, 1],
			[1000, 0, '1K'],
			[1000, 1, '1K'],
			[1000, 3, '1K'],
			[1201, 0, '1K'],
			[1201, 2, '1.2K'],
			[1201, 3, '1.201K'],
			[1230, 2, '1.23K'],
			[1100000, 2, '1.1M'],
			[1100000000, 2, '1.1B'],
			[1100000000000, 2, '1.1T'],
			[1123039000000000, 2, '1,123.04T'],
			[1120000000000000, 0, '1,120T'],
			[1120000000000000, 2, '1,120T']
		];
	}
}
