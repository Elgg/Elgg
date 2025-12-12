<?php

namespace Elgg\Traits;

use Elgg\UnitTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class SeedingUnitTest extends UnitTestCase {
	
	use Seeding {
		createObject as createSeededObject;
	}

	#[DataProvider('propertyProvider')]
	public function testCreateObjectTitle($input) {
		$object = $this->createSeededObject([
			'title' => $input,
		], ['save' => false]);
		$this->assertInstanceOf(\ElggObject::class, $object);
		
		if ($input === false) {
			$this->assertNull($object->title);
		} else {
			$this->assertIsString($object->title);
		}
		
		if (is_string($input)) {
			$this->assertEquals($input, $object->title);
		}
	}

	#[DataProvider('propertyProvider')]
	public function testCreateObjectDescription($input) {
		$object = $this->createSeededObject([
			'description' => $input,
		], ['save' => false]);
		$this->assertInstanceOf(\ElggObject::class, $object);
		
		if ($input === false) {
			$this->assertNull($object->description);
		} else {
			$this->assertIsString($object->description);
		}
		
		if (is_string($input)) {
			$this->assertEquals($input, $object->description);
		}
	}

	#[DataProvider('propertyProvider')]
	public function testCreateObjectTags($input) {
		$object = $this->createSeededObject([
			'tags' => $input,
		], ['save' => false]);
		$this->assertInstanceOf(\ElggObject::class, $object);
		
		if ($input === false) {
			$this->assertNull($object->tags);
		} elseif (is_string($input)) {
			$this->assertEquals($input, $object->tags);
		} else {
			$this->assertIsArray($object->tags);
		}
	}
	
	public static function propertyProvider() {
		return [
			[true],
			[false],
			['hello world'],
		];
	}
}
