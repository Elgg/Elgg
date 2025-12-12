<?php

namespace Elgg\Traits\Entity;

use Elgg\IntegrationTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

abstract class MetadataIntegrationTestCase extends IntegrationTestCase {

	/**
	 * @var \ElggEntity
	 */
	protected $entity;

	/**
	 * @var \ElggEntity
	 */
	protected $unsaved_entity;
	
	/**
	 * @var \ElggEntity[]
	 */
	protected array $entities;

	public function up() {
		parent::up();
		
		$this->entity = $this->getEntity();
		
		$this->unsaved_entity = $this->getUnsavedEntity();
		
		$this->entities = [
			$this->entity,
			$this->unsaved_entity,
		];
	}

	public function down() {
		unset($this->unsaved_entity);
		unset($this->entities);
	}
	
	abstract protected function getEntity(): \ElggEntity;
	
	abstract protected function getUnsavedEntity(): \ElggEntity;
	
	public function testEntitySetSingleValue() {
		foreach ($this->entities as $entity) {
			$entity->foo = 'bar';
			$this->assertEquals('bar', $entity->foo);
		}
	}
	
	public function testEntitySetSingleIntValue() {
		foreach ($this->entities as $entity) {
			$entity->foo = 123;
			$this->assertEquals(123, $entity->foo);
		}
	}
	
	public function testEntitySetTrueValue() {
		foreach ($this->entities as $entity) {
			$entity->foo = true;
			$this->assertTrue($entity->foo);
		}
	}
	
	public function testEntitySetFalseValue() {
		foreach ($this->entities as $entity) {
			$entity->foo = false;
			$this->assertFalse($entity->foo);
		}
	}
	
	public function testEntitySetEmptyStringValue() {
		foreach ($this->entities as $entity) {
			$entity->foo = '';
			$this->assertNull($entity->foo);
		}
	}
	
	public function testEntitySetNullValue() {
		foreach ($this->entities as $entity) {
			$entity->foo = null;
			$this->assertNull($entity->foo);
		}
	}
	
	public function testEntitySetOverwriteWithNullValue() {
		foreach ($this->entities as $entity) {
			$entity->foo = 'bar';
			$this->assertEquals('bar', $entity->foo);
			$entity->foo = null;
			$this->assertNull($entity->foo);
		}
	}

	public function testEntitySetSingleValueOverwrite() {
		foreach ($this->entities as $entity) {
			$entity->foo = 'bar';
			$this->assertEquals('bar', $entity->foo);
			$entity->foo = 'bar2';
			$this->assertEquals('bar2', $entity->foo);
		}
	}

	public function testEntitySetSingleValueOverwriteNoChanges() {
		foreach ($this->entities as $entity) {
			$entity->foo = 'bar';
			$this->assertEquals('bar', $entity->foo);
			$entity->foo = 'bar';
			$this->assertEquals('bar', $entity->foo);
		}
	}

	public function testEntitySetSingleElementArrayValue() {
		foreach ($this->entities as $entity) {
			$entity->foo = ['bar'];
			$this->assertEquals('bar', $entity->foo);
		}
	}

	public function testEntitySetMultipleValue() {
		foreach ($this->entities as $entity) {
			$entity->foo = ['a', 'b'];
			$this->assertEquals(['a', 'b'], $entity->foo);
		}
	}

	public function testEntitySetMultipleValueOverwrite() {
		foreach ($this->entities as $entity) {
			$entity->foo = ['a', 'b'];
			$this->assertEquals(['a', 'b'], $entity->foo);
			$entity->foo = ['b', 'a'];
			$this->assertEquals(['b', 'a'], $entity->foo);
		}
	}

	public function testEntitySetMultipleValueOverwriteNoChanges() {
		foreach ($this->entities as $entity) {
			$entity->foo = ['a', 'b'];
			$this->assertEquals(['a', 'b'], $entity->foo);
			$entity->foo = ['a', 'b'];
			$this->assertEquals(['a', 'b'], $entity->foo);
		}
	}

	public function testEntitySetEmptyArray() {
		foreach ($this->entities as $entity) {
			$entity->foo = 'bar';
			$this->assertEquals('bar', $entity->foo);
			$entity->foo = [];
			$this->assertNull($entity->foo);
		}
	}
	
	public function testEntityUnsetSingleValue() {
		foreach ($this->entities as $entity) {
			$entity->foo = 'bar';
			$this->assertEquals('bar', $entity->foo);
			unset($entity->foo);
			$this->assertNull($entity->foo);
		}
	}
	
	public function testEntityUnsetMultipleValue() {
		foreach ($this->entities as $entity) {
			$entity->foo = ['a', 'b'];
			$this->assertEquals(['a', 'b'], $entity->foo);
			unset($entity->foo);
			$this->assertNull($entity->foo);
		}
	}
		
	public function testSetMetadataUpdateCreatesNoNewMetadataRows() {
		$this->entity->foo = 'bar';
		$md = elgg_get_metadata([
			'guid' => $this->entity->guid,
			'metadata_name' => 'foo',
		]);
		$this->assertCount(1, $md);
		$new_id = $md[0]->id;
		
		$this->entity->foo = 'bar2';
		$md = elgg_get_metadata([
			'guid' => $this->entity->guid,
			'metadata_name' => 'foo',
		]);
		$this->assertCount(1, $md);
		$update_id = $md[0]->id;
		
		$this->assertEquals($new_id, $update_id);
	}
		
	public function testSetMetadataToArrayCreatesNewMetadataRows() {
		$this->entity->foo = 'bar';
		$md = elgg_get_metadata([
			'guid' => $this->entity->guid,
			'metadata_name' => 'foo',
		]);
		$this->assertCount(1, $md);
		$original_id = $md[0]->id;
		
		$this->entity->foo = ['bar1', 'bar2'];
		$md = elgg_get_metadata([
			'guid' => $this->entity->guid,
			'metadata_name' => 'foo',
		]);
		$this->assertCount(2, $md);
		
		foreach ($md as $row) {
			// check if all metadata is a new row
			$this->assertNotEquals($original_id, $row->id);
		}
	}
		
	public function testSetMetadataFromArrayCreatesNewMetadataRows() {
		$this->entity->foo = ['bar1', 'bar2'];
		$md = elgg_get_metadata([
			'guid' => $this->entity->guid,
			'metadata_name' => 'foo',
		]);
		$this->assertCount(2, $md);
		
		$original_ids = [];
		foreach ($md as $row) {
			$original_ids[] = $row->id;
		}
		
		$this->entity->foo = 'bar';
		$md = elgg_get_metadata([
			'guid' => $this->entity->guid,
			'metadata_name' => 'foo',
		]);
		$this->assertCount(1, $md);
		
		foreach ($original_ids as $original_id) {
			// check if all metadata is a new row
			$this->assertNotEquals($original_id, $md[0]->id);
		}
	}
		
	public function testDeleteMetadataNonExistingMetadata() {
		// let's delete a non-existent metadata
		$this->assertTrue($this->entity->deleteMetadata('non-existing'));
	}
		
	public function testDeleteMetadataNonExistingMetadataNotDeletingOtherMetadata() {
		// @link https://github.com/elgg/elgg/issues/2273
		$this->assertTrue($this->entity->setMetadata('foo', 'bar'));
		$this->assertTrue($this->entity->deleteMetadata('notfoo'));
		$this->assertEquals('bar', $this->entity->getMetadata('foo'));
	}

	public function testDeleteMetadataSingleName() {
		$this->assertTrue($this->entity->setMetadata('foo', 'bar'));
		$this->assertEquals('bar', $this->entity->getMetadata('foo'));
		$this->assertTrue($this->entity->setMetadata('important', 'indeed!'));
		$this->assertEquals('indeed!', $this->entity->getMetadata('important'));
		$this->assertTrue($this->entity->deleteMetadata('important'));
		$this->assertNull($this->entity->getMetadata('important'));
		$this->assertEquals('bar', $this->entity->getMetadata('foo'));
	}

	public function testDeleteMetadataDeletesAll() {
		$initial_count = (int) elgg_get_metadata([
			'guid' => $this->entity->guid,
			'count' => true,
		]);
		
		$this->entity->foo = 'bar';
		$this->entity->bar = 'foo';
		
		// count if all metadata is deleted
		$metadata_count = (int) elgg_get_metadata([
			'guid' => $this->entity->guid,
			'count' => true,
		]);
		$this->assertEquals($initial_count + 2, $metadata_count);
		
		$this->entity->deleteMetadata();
		
		$this->assertNull($this->entity->foo);
		$this->assertNull($this->entity->bar);
		
		// count if all metadata is deleted
		$metadata_count = (int) elgg_get_metadata([
			'guid' => $this->entity->guid,
			'count' => true,
		]);
		$this->assertEquals(0, $metadata_count);
	}
	
	public function testSetMetadataAppendSingleValueReturnsArray() {
		foreach ($this->entities as $entity) {
			$entity->foo = 'test1';
			$entity->setMetadata('foo', 'test2', '', true);

			$this->assertEquals([
				'test1',
				'test2'
			], $entity->foo);
		}
	}

	public function testSetMetadataAppendSingleElementArrayValueReturnsArray() {
		foreach ($this->entities as $entity) {
			$entity->foo = 'test1';
			$entity->setMetadata('foo', ['test2'], '', true);

			$this->assertEquals([
				'test1',
				'test2'
			], $entity->foo);
		}
	}

	public function testSetMetadataAppendArrayValueReturnsArray() {
		foreach ($this->entities as $entity) {
			$entity->foo = [
				'brett',
				'bryan',
				'brad',
			];
			$entity->setMetadata('foo', ['test1', 'test2', 'test3'], '', true);

			$this->assertEquals([
				'brett',
				'bryan',
				'brad',
				'test1',
				'test2',
				'test3',
			], $entity->foo);
		}
	}
	
	public function testCanGetAllEntityMetadata() {
		foreach ($this->entities as $entity) {

			$entity->foo1 = 'bar1';
			$entity->foo2 = ['bar2', 'bar3', 'bar4', 5];
			$entity->foo3 = false;

			$metadata = $entity->getAllMetadata();

			$this->assertEquals($metadata['foo1'], $entity->foo1);
			$this->assertEquals($metadata['foo2'], $entity->foo2);
			$this->assertEquals($metadata['foo3'], $entity->foo3);
		}
	}

	#[DataProvider('emptyValues')]
	public function testSetMetadataEmpty($empty_value) {
		foreach ($this->entities as $entity) {
			$entity->setMetadata('foo', 'bar');
			$this->assertEquals('bar', $entity->getMetadata('foo'));
			$this->assertEquals('bar', $entity->foo);
			
			// remove metadata by setting to empty value
			$this->assertTrue($entity->setMetadata('foo', $empty_value));
			$this->assertNull($entity->foo);
			$this->assertNull($entity->getMetadata('foo'));
			
			// removing non-existing data should also return true
			$this->assertTrue($entity->setMetadata('foo', $empty_value));
		}
	}
	
	public static function emptyValues() {
		return [
			[''],
			[null],
		];
	}
}
