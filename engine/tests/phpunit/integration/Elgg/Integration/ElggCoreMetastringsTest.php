<?php

namespace Elgg\Integration;

use Elgg\Database\AnnotationsTable;
use Elgg\Database\MetadataTable;
use Elgg\Database\Select;
use Elgg\IntegrationTestCase;

class ElggCoreMetastringsTest extends IntegrationTestCase {

	protected array $metastringTypes = [
		'metadata',
		'annotation',
	];
	protected array $metastringTables = [
		'metadata' => MetadataTable::TABLE_NAME,
		'annotation' => AnnotationsTable::TABLE_NAME,
	];

	protected \ElggObject $object;

	public function up() {
		_elgg_services()->session_manager->setLoggedInUser($this->getAdmin());
		$this->object = $this->createObject();
	}

	protected function createAnnotations($max = 1) {
		$annotations = [];
		for ($i = 0; $i < $max; $i++) {
			$name = 'test_annotation_name' . rand();
			$value = 'test_annotation_value' . rand();
			
			$annotations[] = $this->object->annotate($name, $value);
		}

		return $annotations;
	}

	protected function createMetadata($max = 1) {
		$metadata = [];
		for ($i = 0; $i < $max; $i++) {
			$name = 'test_metadata_name' . $i . rand();
			$value = 'test_metadata_value' . $i . rand();
			$md = new \ElggMetadata();
			$md->entity_guid = $this->object->guid;
			$md->name = $name;
			$md->value = $value;
			
			if ($md->save()) {
				$metadata[] = $md->id;
			}
		}

		return $metadata;
	}
	
	protected function getDatabaseRow(int $id, string $type) {
		$select = Select::fromTable($this->metastringTables[$type]);
		$select->select('*')
			->where($select->compare('id', '=', $id, ELGG_VALUE_ID));
		
		return _elgg_services()->db->getDataRow($select);
	}
	
	public function testMetadataValueTypes() {
		$this->object->string_md = 'string_value';
		$this->object->integer_md = 1234;
		$this->object->bool_true_md = true;
		$this->object->bool_false_md = false;
		
		$this->object->invalidateCache();
		
		$md = elgg_get_metadata([
			'entity_guid' => $this->object->guid,
			'metadata_name' => 'string_md',
		])[0];
		
		$this->assertIsString($md->value);
		$this->assertEquals('text', $md->value_type);
		
		$md = elgg_get_metadata([
			'entity_guid' => $this->object->guid,
			'metadata_name' => 'integer_md',
		])[0];
		
		$this->assertIsInt($md->value);
		$this->assertEquals('integer', $md->value_type);
		
		$md = elgg_get_metadata([
			'entity_guid' => $this->object->guid,
			'metadata_name' => 'bool_true_md',
		])[0];
		
		$this->assertIsBool($md->value);
		$this->assertEquals('bool', $md->value_type);

		$md = elgg_get_metadata([
			'entity_guid' => $this->object->guid,
			'metadata_name' => 'bool_false_md',
		])[0];
		
		$this->assertIsBool($md->value);
		$this->assertEquals('bool', $md->value_type);
		
		$this->object->invalidateCache();
		
		$this->assertIsString($this->object->string_md);
		$this->assertIsInt($this->object->integer_md);
		$this->assertTrue($this->object->bool_true_md);
		$this->assertFalse($this->object->bool_false_md);
	}
	
	public function testMetadataBoolDatabaseValue() {
		// begin true
		$this->object->bool_true_md = true;
		
		$md = elgg_get_metadata([
			'entity_guid' => $this->object->guid,
			'metadata_name' => 'bool_true_md',
		])[0];
		$this->assertInstanceOf(\ElggMetadata::class, $md);
		$this->assertTrue($md->value);
		
		$row = $this->getDatabaseRow($md->id, 'metadata');
		$this->assertEquals('bool', $row->value_type);
		$this->assertEquals('1', $row->value);
		
		// now set to false
		$this->object->bool_true_md = false;
		
		$md = elgg_get_metadata([
			'entity_guid' => $this->object->guid,
			'metadata_name' => 'bool_true_md',
		])[0];
		$this->assertInstanceOf(\ElggMetadata::class, $md);
		$this->assertFalse($md->value);
		
		$row = $this->getDatabaseRow($md->id, 'metadata');
		$this->assertEquals('bool', $row->value_type);
		$this->assertEquals('0', $row->value);
		
		// set to true by metadata object
		$md->value = true;
		$md->save();
		
		$md = elgg_get_metadata_from_id($md->id);
		$this->assertInstanceOf(\ElggMetadata::class, $md);
		$this->assertTrue($md->value);
		
		$row = $this->getDatabaseRow($md->id, 'metadata');
		$this->assertEquals('bool', $row->value_type);
		$this->assertEquals('1', $row->value);
		
		// begin false
		$this->object->bool_false_md = false;
		
		$md = elgg_get_metadata([
			'entity_guid' => $this->object->guid,
			'metadata_name' => 'bool_false_md',
		])[0];
		$this->assertInstanceOf(\ElggMetadata::class, $md);
		$this->assertFalse($md->value);
		
		$row = $this->getDatabaseRow($md->id, 'metadata');
		$this->assertEquals('bool', $row->value_type);
		$this->assertEquals('0', $row->value);
		
		// now set to true
		$this->object->bool_false_md = true;
		
		$md = elgg_get_metadata([
			'entity_guid' => $this->object->guid,
			'metadata_name' => 'bool_false_md',
		])[0];
		$this->assertInstanceOf(\ElggMetadata::class, $md);
		$this->assertTrue($md->value);
		
		$row = $this->getDatabaseRow($md->id, 'metadata');
		$this->assertEquals('bool', $row->value_type);
		$this->assertEquals('1', $row->value);
		
		// set to false by metadata object
		$md->value = false;
		$md->save();
		
		$md = elgg_get_metadata_from_id($md->id);
		$this->assertInstanceOf(\ElggMetadata::class, $md);
		$this->assertFalse($md->value);
		
		$row = $this->getDatabaseRow($md->id, 'metadata');
		$this->assertEquals('bool', $row->value_type);
		$this->assertEquals('0', $row->value);
	}
	
	public function testAnnotationValueTypes() {
		$this->object->annotate('string_name', 'string_value');
		$this->object->annotate('integer_name', 1234);
		$this->object->annotate('bool_false_name', false);
		$this->object->annotate('bool_true_name', true);
		
		$this->object->invalidateCache();
		
		$annotation = elgg_get_annotations([
			'guid' => $this->object->guid,
			'annotation_name' => 'string_name',
		])[0];
		
		$this->assertIsString($annotation->value);
		$this->assertEquals('text', $annotation->value_type);
		
		$annotation = elgg_get_annotations([
			'guid' => $this->object->guid,
			'annotation_name' => 'integer_name',
		])[0];
		
		$this->assertIsInt($annotation->value);
		$this->assertEquals('integer', $annotation->value_type);
		
		$annotation = elgg_get_annotations([
			'guid' => $this->object->guid,
			'annotation_name' => 'bool_false_name',
		])[0];
		
		$this->assertFalse($annotation->value);
		$this->assertEquals('bool', $annotation->value_type);

		$annotation = elgg_get_annotations([
			'guid' => $this->object->guid,
			'annotation_name' => 'bool_true_name',
		])[0];
		
		$this->assertTrue($annotation->value);
		$this->assertEquals('bool', $annotation->value_type);
	}
	
	public function testAnnotationBoolDatabaseValue() {
		// begin true
		$this->object->annotate('bool_true_name', true);
		
		$annotation = elgg_get_annotations([
			'guid' => $this->object->guid,
			'annotation_name' => 'bool_true_name',
		])[0];
		$this->assertInstanceOf(\ElggAnnotation::class, $annotation);
		$this->assertTrue($annotation->value);
		
		$row = $this->getDatabaseRow($annotation->id, 'annotation');
		$this->assertEquals('bool', $row->value_type);
		$this->assertEquals('1', $row->value);
		
		// now set to false
		$annotation->value = false;
		$annotation->save();
		
		$annotation = elgg_get_annotations([
			'guid' => $this->object->guid,
			'annotation_name' => 'bool_true_name',
		])[0];
		$this->assertInstanceOf(\ElggAnnotation::class, $annotation);
		$this->assertFalse($annotation->value);
		
		$row = $this->getDatabaseRow($annotation->id, 'annotation');
		$this->assertEquals('bool', $row->value_type);
		$this->assertEquals('0', $row->value);
		
		// begin false
		$this->object->annotate('bool_false_name', false);
		
		$annotation = elgg_get_annotations([
			'guid' => $this->object->guid,
			'annotation_name' => 'bool_false_name',
		])[0];
		$this->assertInstanceOf(\ElggAnnotation::class, $annotation);
		$this->assertFalse($annotation->value);
		
		$row = $this->getDatabaseRow($annotation->id, 'annotation');
		$this->assertEquals('bool', $row->value_type);
		$this->assertEquals('0', $row->value);
		
		// now set to true
		$annotation->value = true;
		$annotation->save();
		
		$annotation = elgg_get_annotations([
			'guid' => $this->object->guid,
			'annotation_name' => 'bool_false_name',
		])[0];
		$this->assertInstanceOf(\ElggAnnotation::class, $annotation);
		$this->assertTrue($annotation->value);
		
		$row = $this->getDatabaseRow($annotation->id, 'annotation');
		$this->assertEquals('bool', $row->value_type);
		$this->assertEquals('1', $row->value);
	}
	
	public function testDeleteByID() {
		
		// the following variables are used dynamically
		$annotation = $this->createAnnotations(1);
		$metadata = $this->createMetadata(1);

		foreach ($this->metastringTypes as $type) {
			$id = ${$type}[0];
			$table = $this->metastringTables[$type];
			
			$select = Select::fromTable($table)->select('*');
			$select->where($select->compare('id', '=', $id, ELGG_VALUE_ID));
			
			$test = elgg()->db->getData($select);

			$this->assertEquals($id, $test[0]->id);
			
			if ($type === 'annotation') {
				$item = elgg_get_annotation_from_id($id);
			} elseif ($type === 'metadata') {
				$item = elgg_get_metadata_from_id($id);
			}
			
			$this->assertTrue($item->delete());
			$this->assertEquals([], elgg()->db->getData($select));
		}
	}

	public function testGetAnnotationObjectFromID() {
		$annotations = $this->createAnnotations(1);
		$id = array_shift($annotations);

		$test = elgg_get_annotation_from_id($id);

		$this->assertEquals($id, $test->id);
		$this->assertTrue($test->delete());
	}

	public function testGetMetadataObjectFromID() {
		$metadata = $this->createMetadata(1);
		$id = array_shift($metadata);

		$test = elgg_get_metadata_from_id($id);

		$this->assertEquals($id, $test->id);
		$this->assertTrue($test->delete());
	}

	public function testKeepMeFromDeletingAllMetadata() {
		$options = [
			'limit' => 10,
			'guid' => ELGG_ENTITIES_ANY_VALUE,
			'guids' => false,
			'metadata_name' => ELGG_ENTITIES_ANY_VALUE,
			'metadata_names' => false,
			'metadata_value' => ELGG_ENTITIES_ANY_VALUE,
			'metadata_values' => false,
		];

		$this->expectException(\Elgg\Exceptions\InvalidArgumentException::class);
		elgg_delete_metadata($options);

		$options['guid'] = -1;
		$this->assertNull(elgg_delete_metadata($options));
	}
	
	public function testDeleteAllMetadataWithInvalidGUID() {
		$options = [
			'limit' => 10,
			'guids' => false,
			'metadata_name' => ELGG_ENTITIES_ANY_VALUE,
			'metadata_names' => false,
			'metadata_value' => ELGG_ENTITIES_ANY_VALUE,
			'metadata_values' => false,
			'guid' => -1,
		];

		$this->assertTrue(elgg_delete_metadata($options));
	}
	
	public function testKeepMeFromDeletingAllAnnotations() {
		// annotations
		$options = [
			'limit' => 10,
			'guid' => ELGG_ENTITIES_ANY_VALUE,
			'guids' => false,
			'annotation_name' => ELGG_ENTITIES_ANY_VALUE,
			'annotation_names' => false,
			'annotation_value' => ELGG_ENTITIES_ANY_VALUE,
			'annotation_values' => false,
			'annotation_owner_guid' => ELGG_ENTITIES_ANY_VALUE,
			'annotation_owner_guids' => false,
		];

		$this->assertFalse(elgg_delete_annotations($options));
		$options['guid'] = -1;
		$this->assertTrue(elgg_delete_annotations($options));
	}
}
