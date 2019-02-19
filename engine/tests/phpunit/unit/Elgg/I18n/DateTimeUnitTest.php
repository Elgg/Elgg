<?php

namespace Elgg\I18n;

use Elgg\UnitTestCase;

/**
 * @group UnitTests
 */
class DateTimeUnitTest extends UnitTestCase {
	
	/**
	 * {@inheritDoc}
	 * @see \Elgg\BaseTestCase::up()
	 */
	public function up() {
		
	}
	
	/**
	 * {@inheritDoc}
	 * @see \Elgg\BaseTestCase::down()
	 */
	public function down() {
		
	}
	
	/**
	 * @dataProvider formatLocaleProvider
	 */
	public function testFormatLocale($time, $date_format, $strftime_format, $language) {
		
		$date = new DateTime($time);
		
		// make expected output
		$current_locale = setlocale(LC_TIME, 0);
		setlocale(LC_TIME, $language);
		
		$expected = strftime($strftime_format, $date->getTimestamp());
		
		setlocale(LC_TIME, $current_locale);
		
		$this->assertEquals($expected, $date->formatLocale($date_format, $language));
	}
	
	public function formatLocaleProvider() {
		return [
			['midnight', 'l d, F Y H:i:s', '%A %d, %B %Y %H:%M:%S', 'en'],
			['+2 days', 'l d, F Y H:i:s', '%A %d, %B %Y %H:%M:%S', 'nl'],
			['tomorrow', 'l', '%A', 'nl'],
			['January 9, 2018 12:00', 'l d, F Y H:i:s', '%A %d, %B %Y %H:%M:%S', 'en'],
			['January 9, 2018 12:00', 'l d, F Y H:i:s', '%A %d, %B %Y %H:%M:%S', 'nl'],
			['January 9, 2018 12:00', 'F', '%B', 'nl'],
			[1515496794, 'l d, F Y H:i:s', '%A %d, %B %Y %H:%M:%S', 'en'],
			[1515496794, 'l d, F Y H:i:s', '%A %d, %B %Y %H:%M:%S', 'nl'],
			[1515496794, 'l, F', '%A, %B', 'nl'],
		];
	}
}