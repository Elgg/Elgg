<?php

namespace Elgg\Integration;

use Elgg\IntegrationTestCase;
use ElggAnnotation;

/**
 * Elgg Metastrings test
 *
 * @group IntegrationTests
 * @group Metadata
 * @group Annotations
 */
class ElggCoreMetastringsTest extends IntegrationTestCase {

	public $metastringTypes = [
		'metadata',
		'annotation'
	];
	public $metastringTables = [
		'metadata' => 'metadata',
		'annotation' => 'annotations',
	];

	public function up() {
		_elgg_services()->session->setLoggedInUser($this->getAdmin());
		$this->object = $this->createObject();
	}

	public function createAnnotations($max = 1) {
		$annotations = [];
		for ($i = 0; $i < $max; $i++) {
			$name = 'test_annotation_name' . rand();
			$value = 'test_annotation_value' . rand();
			
			$annotations[] = $this->object->annotate($name, $value);
		}

		return $annotations;
	}

	public function createMetadata($max = 1) {
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
	
	public function testDeleteByID() {
		$db_prefix = _elgg_services()->config->dbprefix;
		
		// the following variables are used dynamically
		$annotation = $this->createAnnotations(1);
		$metadata = $this->createMetadata(1);

		foreach ($this->metastringTypes as $type) {
			$id = ${$type}[0];
			$table = $db_prefix . $this->metastringTables[$type];
			$q = "SELECT * FROM $table WHERE id = $id";
			$test = elgg()->db->getData($q);

			$this->assertEquals($id, $test[0]->id);
			
			if ($type === 'annotation') {
				$item = elgg_get_annotation_from_id($id);
			} elseif ($type === 'metadata') {
				$item = elgg_get_metadata_from_id($id);
			}
			
			$this->assertTrue($item->delete());
			$this->assertEquals([], elgg()->db->getData($q));
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

	public function testGetMetastringObjectFromIDWithDisabledAnnotation() {

		$name = 'test_annotation_name' . rand();
		$value = 'test_annotation_value' . rand();

		$id = $this->object->annotate($name, $value);

		$this->assertTrue((bool) $id);

		elgg_call(ELGG_IGNORE_ACCESS, function() use ($id) {
			$annotation = elgg_get_annotation_from_id($id);
	
			$this->assertInstanceOf(ElggAnnotation::class, $annotation);
	
			$this->assertTrue($annotation->disable());

			$this->assertFalse(elgg_get_annotation_from_id($id));
	
			$result = elgg_call(ELGG_SHOW_DISABLED_ENTITIES, function() use ($id) {
				$annotation = elgg_get_annotation_from_id($id);
				return $annotation->delete();
			});
			
			$this->assertTrue($result);
		});
	}

	public function testGetMetastringBasedObjectWithDisabledAnnotation() {
		$name = 'test_annotation_name' . rand();
		$value = 'test_annotation_value' . rand();
		$id = $this->object->annotate($name, $value);

		$annotation = elgg_get_annotation_from_id($id);
		$this->assertTrue($annotation->disable());

		$test = elgg_get_annotations([
			'guid' => $this->object->guid,
		]);
		$this->assertEquals([], $test);

		$result = elgg_call(ELGG_SHOW_DISABLED_ENTITIES, function() use ($id) {
			$annotation = elgg_get_annotation_from_id($id);
			return $annotation->delete();
		});
		$this->assertTrue($result);
	}

	public function testEnableDisableByID() {
		$annotations = $this->createAnnotations(1);
		$annotation_id = $annotations[0];
		$annotation = elgg_get_annotation_from_id($annotation_id);

		$table = _elgg_services()->config->dbprefix . $this->metastringTables['annotation'];
		
		$q = "SELECT * FROM {$table} WHERE id = {$annotation_id}";
		$test = elgg()->db->getData($q);

		// disable
		$this->assertEquals('yes', $test[0]->enabled);
		$this->assertTrue($annotation->disable());

		$test = elgg()->db->getData($q);
		$this->assertEquals('no', $test[0]->enabled);

		// enable
		$result = elgg_call(ELGG_SHOW_DISABLED_ENTITIES, function() use ($annotation) {
			return $annotation->enable();
		});
		$this->assertTrue($result);

		$test = elgg()->db->getData($q);
		$this->assertEquals('yes', $test[0]->enabled);

		$this->assertTrue($annotation->delete());
	}

	public function testKeepMeFromDeletingEverything() {
		$options = [
			'limit' => 10,
			'guid' => ELGG_ENTITIES_ANY_VALUE,
			'guids' => false,
			'metadata_name' => ELGG_ENTITIES_ANY_VALUE,
			'metadata_names' => false,
			'metadata_value' => ELGG_ENTITIES_ANY_VALUE,
			'metadata_values' => false,
		];

		$this->assertFalse(elgg_delete_metadata($options));

		$options['guid'] = -1;
		$this->assertNull(elgg_delete_metadata($options));

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
		$this->assertNull(elgg_delete_annotations($options));
	}
}
