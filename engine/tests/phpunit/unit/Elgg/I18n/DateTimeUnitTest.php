<?php

namespace Elgg\I18n;

use Elgg\UnitTestCase;

/**
 * @group UnitTests
 */
class DateTimeUnitTest extends UnitTestCase {

	/**
	 * @var string previous default timezone
	 */
	protected $timezone;
	
	public function up() {
		$this->timezone = date_default_timezone_get();
		date_default_timezone_set('Europe/Amsterdam');
	}
	
	public function down() {
		date_default_timezone_set($this->timezone);
	}
	
	/**
	 * @dataProvider formatLocaleProvider
	 */
	public function testFormatLocale($time, $date_format, $language, $expected) {
		$date = new DateTime($time);
		
		$this->assertEquals($expected, $date->formatLocale($date_format, $language));
	}
	
	public function formatLocaleProvider() {
		return [
			['January 9, 2018 12:00', 'l d, F Y H:i:s', 'en', 'Tuesday 09, January 2018 12:00:00'],
			['January 9, 2018 12:00', 'l d, F Y H:i:s', 'nl', 'dinsdag 09, januari 2018 12:00:00'],
			['January 9, 2018 12:00', 'F', 'nl', 'januari'],
			['@1515496794', 'l d, F Y H:i:s', 'en', 'Tuesday 09, January 2018 12:19:54'],
			['@1515496794', 'l d, F Y H:i:s', 'nl', 'dinsdag 09, januari 2018 12:19:54'],
			['@1515496794', 'l, F', 'nl', 'dinsdag, januari'],
		];
	}
}
