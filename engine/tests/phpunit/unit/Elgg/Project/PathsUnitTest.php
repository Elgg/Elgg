<?php

namespace Elgg\Project;

use Elgg\UnitTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class PathsUnitTest extends UnitTestCase {
	
	#[DataProvider('sanitizePathProvider')]
	public function testSanitize($prefix) {
		$path = Paths::sanitize(Paths::elgg() . $prefix . 'engine/tests/phpunit/unit/Elgg/Project/') . 'PathsUnitTest.php'; // this class file
		$expected = Paths::sanitize(__FILE__, false);
		
		$this->assertEquals($expected, $path);
	}
	
	public static function sanitizePathProvider() {
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
