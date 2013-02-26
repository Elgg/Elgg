<?php

class ElggEntityDirLocatorTest extends PHPUnit_Framework_TestCase {
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
			$dir = new Elgg_EntityDirLocator($guid);
			$this->assertInstanceOf('Elgg_EntityDirLocator', $dir);
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
			$this->setExpectedException('Exception', "GUIDs must be integers > 0.");
			$dir = new Elgg_EntityDirLocator($guid);
		}
	}

	public function testBucketBounds() {
		$size = Elgg_EntityDirLocator::BUCKET_SIZE;
		
		foreach ($this->guids as $guid) {
			$locator = new Elgg_EntityDirLocator($guid);
			$bound = $locator->getLowerBucketBound($guid);

			// we start at 1 since there are no guids of 0
			if ($guid < 5000) {
				$correct_bound = 1;
			} elseif ($guid < 10000) {
				$correct_bound = 5000;
			} elseif ($guid < 15000) {
				$correct_bound = 10000;
			} elseif ($guid < 20000) {
				$correct_bound = 15000;
			}

			$this->assertSame($correct_bound, $bound, "Bucket bound failed guid = $guid and size = $size");
		}
	}

	public function testGetPath() {
		$guid = 5392;
		$test = new Elgg_EntityDirLocator($guid);

		$this->assertSame("5000/5392/", $test->getPath());
	}

	public function testToString() {
		$guid = 431;
		$path = new Elgg_EntityDirLocator($guid);

		$root = "/tmp/elgg/";
		$this->assertSame($root . '1/431/', $root . $path);
	}

}