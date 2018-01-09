<?php

namespace Elgg;

use DateTime;

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

		$this->assertInstanceOf(\DateTime::class, $dt);
		$this->assertEquals($dt->getTimestamp(), Values::normalizeTimestamp($time));

	}

	public function timeProvider() {
		return [
			['January 9, 2018 12:00'],
			[1515496794],
			[new DateTime('+2 days')],
		];
	}
}