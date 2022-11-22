<?php

namespace Elgg\Database;

use Elgg\IntegrationTestCase;

class AnnotationsTableIntegrationTest extends IntegrationTestCase {

	/**
	 * @var AnnotationsTable
	 */
	protected $service;

	/**
	 * @var \ElggUser
	 */
	protected $owner;
	
	/**
	 * {@inheritDoc}
	 */
	public function up() {
		$this->service = _elgg_services()->annotationsTable;
		$this->owner = $this->createUser();
		_elgg_services()->session_manager->setLoggedInUser($this->owner);
	}

	public function testCreateAnnotation() {
		$entity = $this->createObject();

		$annotation = new \ElggAnnotation();
		$annotation->owner_guid = $this->owner->guid;
		$annotation->name = 'foo';
		$annotation->value = 'bar';
		
		$annotation_id = $this->service->create($annotation, $entity);
		$this->assertIsInt($annotation_id);
		
		$annotation = elgg_get_annotation_from_id($annotation_id);
		$this->assertInstanceOf(\ElggAnnotation::class, $annotation);
		$this->assertEquals($this->owner->guid, $annotation->owner_guid);
		$this->assertEquals('foo', $annotation->name);
		$this->assertEquals('bar', $annotation->value);
	}

	public function testCreateAnnotationWithEmptyContent() {
		$entity = $this->createObject();
		
		$annotation = new \ElggAnnotation();
		
		$this->assertFalse($this->service->create($annotation, $entity));
	}

	public function testUpdateAnnotationUsingUpdate() {
		$entity = $this->createObject();

		$annotation = new \ElggAnnotation();
		$annotation->owner_guid = $this->owner->guid;
		$annotation->name = 'foo';
		$annotation->value = 'bar';
		
		$annotation_id = $this->service->create($annotation, $entity);
		$this->assertIsInt($annotation_id);
		
		$annotation = elgg_get_annotation_from_id($annotation_id);
		$this->assertInstanceOf(\ElggAnnotation::class, $annotation);
		$annotation->value = 'bar2';
		$annotation_save_id = $this->service->update($annotation);
		$this->assertEquals($annotation_id, $annotation_save_id);
		
		$annotation = elgg_get_annotation_from_id($annotation_id);
		$this->assertEquals('bar2', $annotation->value);
	}

	public function testUpdateAnnotationUsingCreate() {
		$entity = $this->createObject();

		$annotation = new \ElggAnnotation();
		$annotation->owner_guid = $this->owner->guid;
		$annotation->name = 'foo';
		$annotation->value = 'bar';
		
		$annotation_id = $this->service->create($annotation, $entity);
		$this->assertIsInt($annotation_id);
		
		$annotation = elgg_get_annotation_from_id($annotation_id);
		$this->assertInstanceOf(\ElggAnnotation::class, $annotation);
		$annotation->value = 'bar2';
		$annotation_save_id = $this->service->create($annotation, $entity);
		$this->assertEquals($annotation_id, $annotation_save_id);
		
		$annotation = elgg_get_annotation_from_id($annotation_id);
		$this->assertEquals('bar2', $annotation->value);
	}

	public function testUpdateAnnotationWithEmptyContent() {
		$entity = $this->createObject();

		$annotation = new \ElggAnnotation();
		$annotation->owner_guid = $this->owner->guid;
		$annotation->name = 'foo';
		$annotation->value = 'bar';
		
		$annotation_id = $this->service->create($annotation, $entity);
		$this->assertIsInt($annotation_id);
		
		$annotation = elgg_get_annotation_from_id($annotation_id);
		$this->assertInstanceOf(\ElggAnnotation::class, $annotation);
		unset($annotation->value);
		$this->assertFalse($this->service->update($annotation));
	}
}
