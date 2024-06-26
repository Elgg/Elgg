<?php

namespace Elgg;

use Elgg\Exceptions\RangeException;

class EntityDirLocatorUnitTest extends \Elgg\UnitTestCase {

	public $guids = [
		1,
		4999,
		5000,
		5001,
		7500,
		10000,
		13532,
		17234
	];

	public function testConstructorGUIDs() {
		// good guids
		foreach ($this->guids as $guid) {
			$dir = new \Elgg\EntityDirLocator($guid);
			$this->assertInstanceOf('\Elgg\EntityDirLocator', $dir);
		}
	}

	/**
	 * @dataProvider badGuidsProvider
	 */
	public function testConstructorThrowsWithBadGuid($guid) {
		$this->expectException(RangeException::class);
		$this->expectExceptionMessage('"guid" must be greater than 0');
		new \Elgg\EntityDirLocator($guid);
	}

	public static function badGuidsProvider() {
		return [
			[0],
			[-123]
		];
	}

	public function testGetPath() {
		foreach ($this->guids as $guid) {
			$test = new \Elgg\EntityDirLocator($guid);

			// we start at 1 since there are no guids of 0
			if ($guid < 5000) {
				$path = "1/$guid/";
			} else if ($guid < 10000) {
				$path = "5000/$guid/";
			} else if ($guid < 15000) {
				$path = "10000/$guid/";
			} else if ($guid < 20000) {
				$path = "15000/$guid/";
			}

			$this->assertSame($path, $test->getPath());
		}
	}

	public function testToString() {
		$guid = 431;
		$path = new \Elgg\EntityDirLocator($guid);

		$root = "/tmp/elgg/";
		$this->assertSame($root . '1/431/', $root . $path);
	}
}
