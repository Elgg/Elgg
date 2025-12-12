<?php

namespace Elgg;

use DateTime as PHPDateTime;
use Elgg\I18n\DateTime as ElggDateTime;
use PHPUnit\Framework\Attributes\DataProvider;

class ValuesUnitTest extends UnitTestCase {

	
	public function down() {
		_elgg_services()->translator->setCurrentLanguage();
		
		parent::down();
	}

	#[DataProvider('timeProvider')]
	public function testCanNormalizeTime($time) {

		$dt = Values::normalizeTime($time);

		$this->assertInstanceOf(ElggDateTime::class, $dt);
		$this->assertEquals($dt->getTimestamp(), Values::normalizeTimestamp($time));
	}

	public static function timeProvider() {
		return [
			['January 9, 2018 12:00'],
			[1515496794],
			[new PHPDateTime('+2 days')],
			[new \DateTimeImmutable('+10 days')],
			[new ElggDateTime('-2 days')],
			[null],
			[''],
			[0],
		];
	}

	#[DataProvider('emptyProvider')]
	public function testIsEmpty($value, $expected_result) {
		$this->assertEquals($expected_result, Values::isEmpty($value));
	}
	
	public static function emptyProvider() {
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

	#[DataProvider('timezoneProvider')]
	public function testSetTimeAfterNormalize($timezone) {
		
		$tz = date_default_timezone_get();
		date_default_timezone_set($timezone);
		
		$time = mktime(10, 15, 30, 1, 15, 2019); // 2019-01-15 10:15:30
		$dt = Values::normalizeTime($time);
		$dt->setTime(10, 15, 30);
		
		date_default_timezone_set($tz);
		
		$this->assertEquals($time, $dt->getTimestamp());
	}
	
	public static function timezoneProvider() {
		return [
			['UTC'],
			['Australia/Adelaide'],
			['Europe/Amsterdam'],
			['America/New_York'],
		];
	}

	#[DataProvider('shortNumberProvider')]
	public function testCanShortenNumber($number, $precision, $expected) {
		$this->assertEquals($expected, Values::shortFormatOutput($number, $precision));
	}
	
	public static function shortNumberProvider() {
		return [
			['a', 1, 'a'],
			[1, 1, 1],
			[0, 0, 0],
			[987, 0, 987],
			[-987, 0, -987],
			[1000, 0, '1K'],
			[-1000, 0, '-1K'],
			[1000, 1, '1K'],
			[1000, 3, '1K'],
			[1201, 0, '1K'],
			[1201.00, 0, '1K'],
			[1201, 2, '1.2K'],
			[-1201, 2, '-1.2K'],
			[1201, 3, '1.201K'],
			[1230, 2, '1.23K'],
			[1100000, 2, '1.1M'],
			[1100000000, 2, '1.1B'],
			[1100000000000, 2, '1.1T'],
			[1123039000000000, 2, '1,123.04T'],
			[1120000000000000, 0, '1,120T'],
			[-1120000000000000, 0, '-1,120T'],
			[1120000000000000, 2, '1,120T']
		];
	}

	#[DataProvider('numberFormatProvider')]
	public function testNumberFormat($number, $decimals, $expected_en, $expected_nl) {
		_elgg_services()->translator->setCurrentLanguage('en');
		
		$this->assertEquals($expected_en, Values::numberFormat($number, $decimals));
		
		_elgg_services()->translator->setCurrentLanguage('nl');
		
		$this->assertEquals($expected_nl, Values::numberFormat($number, $decimals));
	}
	
	public static function numberFormatProvider() {
		return [
			[0, 0, '0', '0'],
			[0, 2, '0.00', '0,00'],
			[1, 0, '1', '1'],
			[1.2, 0, '1', '1'],
			[1, 1, '1.0', '1,0'],
			['1.4', 2, '1.40', '1,40'],
			[1000, 0, '1,000', '1.000'],
			[-1000, 0, '-1,000', '-1.000'],
			[1000, 2, '1,000.00', '1.000,00'],
			['1000', 0, '1,000', '1.000'],
			['1e6', 0, '1,000,000', '1.000.000'],
			['1.2e6', 2, '1,200,000.00', '1.200.000,00'],
		];
	}
}
