<?php

namespace Elgg\Database;

use Elgg\IntegrationTestCase;

class AttributeLoaderTest extends IntegrationTestCase {
	
	/**
	 * @var \ElggObject
	 */
	protected $object;
	
	/**
	 * {@inheritDoc}
	 */
	public function up() {
		_elgg_services()->session->setIgnoreAccess(true);
		
		$this->object = $this->createObject();
	}

	/**
	 * {@inheritDoc}
	 */
	public function down() {
		if ($this->object instanceof \ElggObject) {
			$this->object->delete();
		}
		
		_elgg_services()->session->setIgnoreAccess(false);
	}

	public function testEntityAttributes() {
		foreach ([
			'guid',
			'owner_guid',
			'container_guid',
			'access_id',
			'time_created',
			'time_updated',
			'last_action',
		] as $attr) {
			$this->assertIsInt($this->object->$attr, "Testing attribute {$attr}");
		}
	}

	public function testAnnotationsAttributes() {
		$annotation_id = $this->object->annotate('test_annotation_name', 'test_annotation_value');
		$this->assertIsInt($annotation_id);
		
		$annotation = elgg_get_annotation_from_id($annotation_id);
		$this->assertInstanceOf(\ElggAnnotation::class, $annotation);
		foreach ([
			'id',
			'entity_guid',
			'owner_guid',
			'access_id',
			'time_created',
		] as $attr) {
			$this->assertIsInt($annotation->$attr, "Testing attribute {$attr}");
		}
	}

	public function testMetadataAttributes() {
		
		$metadata = new \ElggMetadata();
		$metadata->entity_guid = $this->object->guid;
		$metadata->name = 'test_metadata_name';
		$metadata->value_type = 'text';
		$metadata->value = 'test_metadata_value';
		$md_id = _elgg_services()->metadataTable->create($metadata, false);
		
		$this->assertIsInt($md_id);
		
		$md = elgg_get_metadata_from_id($md_id);
		$this->assertInstanceOf(\ElggMetadata::class, $md);
		foreach ([
			'id',
			'entity_guid',
			'time_created',
		] as $attr) {
			$this->assertIsInt($md->$attr, "Testing attribute {$attr}");
		}
	}
	
	public function testRiverItemAttributes() {
		
		$river_item_id = elgg_create_river_item([
			'view' => 'river/object/blog/create',
			'action_type' => 'create',
			'subject_guid' => $this->object->owner_guid,
			'object_guid' => $this->object->guid,
		]);
		
		$this->assertIsInt($river_item_id);
		
		$river_item = elgg_get_river_item_from_id($river_item_id);
		$this->assertInstanceOf(\ElggRiverItem::class, $river_item);
		foreach ([
			'id',
			'subject_guid',
			'object_guid',
			'target_guid',
			'annotation_id',
			'posted',
		] as $attr) {
			$this->assertIsInt($river_item->$attr, "Testing attribute {$attr}");
		}
	}
}
