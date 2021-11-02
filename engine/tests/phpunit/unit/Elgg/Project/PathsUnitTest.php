<?php

namespace Elgg\Project;

use Elgg\UnitTestCase;

class PathsUnitTest extends UnitTestCase {

	/**
	 * @dataProvider sanitizePathProvider
	 */
	public function testSanitize($prefix) {
		$path = Paths::sanitize(Paths::elgg() . $prefix . 'engine/tests/phpunit/unit/Elgg/Project/') . 'PathsUnitTest.php'; // this class file
		$expected = Paths::sanitize(__FILE__, false);
		
		$this->assertEquals($expected, $path);
	}
	
	public function sanitizePathProvider() {
		return [
			[''],
			['/'],
			['\\'],
			['//'],
			['./'],
			['.//'],
			['../'],
			['..\\'],
			['..//'],
			['..../'],
			['....//'],
			['....\//'],
		];
	}
}
