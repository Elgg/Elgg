<?php

namespace Elgg\Traits\Entity;

use Elgg\Exceptions\InvalidArgumentException;
use Elgg\Exceptions\RangeException;
use Elgg\IntegrationTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

abstract class IconsIntegrationTestCase extends IntegrationTestCase {
	
	protected \ElggEntity $entity;
	
	public function up() {
		parent::up();
		
		$this->entity = $this->getEntity();
	}
	
	abstract protected function getEntity(): \ElggEntity;

	#[DataProvider('tooFewCoordinatesProvider')]
	public function testSaveIconCoordinatesWithTooFewCoordinates($x1, $x2, $y1, $y2, $icon_type) {
		$coords = [
			'x1' => $x1,
			'x2' => $x2,
			'y1' => $y1,
			'y2' => $y2,
		];
		
		$this->expectException(InvalidArgumentException::class);
		$this->entity->saveIconCoordinates($coords, $icon_type);
	}
	
	public static function tooFewCoordinatesProvider(): array {
		return [
			[100, 100, 200, null, 'icon'],
			[100, 100, null, 200, 'icon'],
			[100, null, 200, 200, 'icon'],
			[null, 100, 200, 200, 'icon'],
			[100, 100, 200, null, 'avatar'],
			[100, 100, null, 200, 'avatar'],
			[100, null, 200, 200, 'avatar'],
			[null, 100, 200, 200, 'avatar'],
		];
	}

	#[DataProvider('invalidCoordinatesProvider')]
	public function testSaveIconCoordinatesWithTooInvalidCoordinates($x1, $x2, $y1, $y2, $icon_type) {
		$coords = [
			'x1' => $x1,
			'x2' => $x2,
			'y1' => $y1,
			'y2' => $y2,
		];
		
		$this->expectException(RangeException::class);
		$this->entity->saveIconCoordinates($coords, $icon_type);
	}
	
	public static function invalidCoordinatesProvider(): array {
		return [
			[-100, 100, 200, 200, 'icon'],
			[100, -100, 200, 200, 'icon'],
			[100, 100, -200, 200, 'icon'],
			[100, 100, 200, -200, 'icon'],
			[-100, 100, 200, 200, 'avatar'],
			[100, -100, 200, 200, 'avatar'],
			[100, 100, -200, 200, 'avatar'],
			[100, 100, 200, -200, 'avatar'],
		];
	}
	
	public function testCRUDIconCoordinates() {
		$entity = $this->entity;
		
		$icon_type1 = 'icon';
		$icon_type2 = 'avatar';
		
		// start empty
		$current1 = $entity->getIconCoordinates($icon_type1);
		$this->assertIsArray($current1);
		$this->assertEmpty($current1);
		
		$current2 = $entity->getIconCoordinates($icon_type2);
		$this->assertIsArray($current2);
		$this->assertEmpty($current2);
		
		// save coordinates
		$coords1 = [
			'x1' => $this->faker()->numberBetween(1, 1000),
			'x2' => $this->faker()->numberBetween(1, 1000),
			'y1' => $this->faker()->numberBetween(1, 1000),
			'y2' => $this->faker()->numberBetween(1, 1000),
		];
		$coords2 = [
			'x1' => $this->faker()->numberBetween(1, 1000),
			'x2' => $this->faker()->numberBetween(1, 1000),
			'y1' => $this->faker()->numberBetween(1, 1000),
			'y2' => $this->faker()->numberBetween(1, 1000),
		];
		
		$entity->saveIconCoordinates($coords1, $icon_type1);
		
		$entity->invalidateCache();
		$current1 = $entity->getIconCoordinates($icon_type1);
		$this->assertNotEmpty($current1);
		$this->assertEquals($coords1, $current1);
		
		// shouldn't be saved to a different icon
		$this->assertEmpty($entity->getIconCoordinates($icon_type2));
		
		// update coordinates
		$entity->saveIconCoordinates($coords2, $icon_type1);
		
		$entity->invalidateCache();
		$current1 = $entity->getIconCoordinates($icon_type1);
		$this->assertNotEmpty($current1);
		$this->assertEquals($coords2, $current1);
		
		// save to different icon
		$entity->saveIconCoordinates($coords1, $icon_type2);
		
		$entity->invalidateCache();
		$current2 = $entity->getIconCoordinates($icon_type2);
		$this->assertNotEmpty($current2);
		$this->assertEquals($coords1, $current2);
		
		$this->assertNotEquals($current1, $current2);
		
		// remove coordinates
		$entity->removeIconCoordinates($icon_type1);
		
		$entity->invalidateCache();
		$this->assertEmpty($entity->getIconCoordinates($icon_type1));
		$this->assertNotEmpty($entity->getIconCoordinates($icon_type2));
		
		$entity->removeIconCoordinates($icon_type2);
		$this->assertEmpty($entity->getIconCoordinates($icon_type2));
	}
	
	public function testIconGenerationLocking() {
		$entity = $this->entity;
		
		$icon_type1 = 'icon';
		$icon_type2 = 'avatar';
		
		$this->assertFalse($entity->isIconThumbnailGenerationLocked($icon_type1));
		$this->assertFalse($entity->isIconThumbnailGenerationLocked($icon_type2));
		
		// lock generation
		$entity->lockIconThumbnailGeneration($icon_type1);
		
		$this->assertTrue($entity->isIconThumbnailGenerationLocked($icon_type1));
		$this->assertFalse($entity->isIconThumbnailGenerationLocked($icon_type2));
		
		// check ttl
		$this->assertFalse($entity->isIconThumbnailGenerationLocked($icon_type1, -1));
		
		// lock other icon generation
		$entity->lockIconThumbnailGeneration($icon_type2);
		
		$this->assertTrue($entity->isIconThumbnailGenerationLocked($icon_type1));
		$this->assertTrue($entity->isIconThumbnailGenerationLocked($icon_type2));
		
		// unlock
		$entity->unlockIconThumbnailGeneration($icon_type1);
		
		$this->assertFalse($entity->isIconThumbnailGenerationLocked($icon_type1));
		$this->assertTrue($entity->isIconThumbnailGenerationLocked($icon_type2));
		
		// unlock other icon
		$entity->unlockIconThumbnailGeneration($icon_type2);
		
		$this->assertFalse($entity->isIconThumbnailGenerationLocked($icon_type1));
		$this->assertFalse($entity->isIconThumbnailGenerationLocked($icon_type2));
	}
}
