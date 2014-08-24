<?php
namespace Elgg;


class EntityDirLocatorTest extends \PHPUnit_Framework_TestCase {
	public $guids = array(
		1,
		4999,
		5000,
		5001,
		7500,
		10000,
		13532,
		17234
	);

	public function testConstructorGUIDs() {
		// good guids
		foreach ($this->guids as $guid) {
			$dir = new \Elgg\EntityDirLocator($guid);
			$this->assertInstanceOf('\Elgg\EntityDirLocator', $dir);
		}

		// bad guids
		$bad_guids = array(
			"abc",
			null,
			false,
			0,
			-123
		);

		foreach ($bad_guids as $guid) {
			$this->setExpectedException('InvalidArgumentException', "GUIDs must be integers > 0.");
			$dir = new \Elgg\EntityDirLocator($guid);
		}
	}

	public function testGetPath() {
		$size = \Elgg\EntityDirLocator::BUCKET_SIZE;

		foreach ($this->guids as $guid) {
			$test = new \Elgg\EntityDirLocator($guid);

			// we start at 1 since there are no guids of 0
			if ($guid < 5000) {
				$path = "1/$guid/";
			} elseif ($guid < 10000) {
				$path = "5000/$guid/";
			} elseif ($guid < 15000) {
				$path = "10000/$guid/";
			} elseif ($guid < 20000) {
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
