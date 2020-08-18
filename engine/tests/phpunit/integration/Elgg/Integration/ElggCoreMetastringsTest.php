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
		$this->object = $this->createOne('object');
	}

	public function down() {
		$this->object->delete();

		$guid = $this->object->guid;
		elgg_call(ELGG_SHOW_DISABLED_ENTITIES, function() use ($guid) {
			elgg_delete_annotations([
				'guid' => $guid,
			]);
		});
		
		_elgg_services()->session->removeLoggedInUser();
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
		foreach ($this->metastringTypes as $type) {
			$required = [
				'guid',
				'guids'
			];

			switch ($type) {
				case 'metadata':
					$metadata_required = [
						'metadata_name',
						'metadata_names',
						'metadata_value',
						'metadata_values'
					];

					$required = array_merge($required, $metadata_required);
					break;

				case 'annotation':
					$annotations_required = [
						'annotation_owner_guid',
						'annotation_owner_guids',
						'annotation_name',
						'annotation_names',
						'annotation_value',
						'annotation_values'
					];

					$required = array_merge($required, $annotations_required);
					break;
			}

			$options = [];
			$this->assertFalse(_elgg_is_valid_options_for_batch_operation($options, $type));

			// limit alone isn't valid:
			$options = ['limit' => 10];
			$this->assertFalse(_elgg_is_valid_options_for_batch_operation($options, $type));

			foreach ($required as $key) {
				$options = [];

				$options[$key] = ELGG_ENTITIES_ANY_VALUE;
				$this->assertFalse(_elgg_is_valid_options_for_batch_operation($options, $type), "Sent $key = ELGG_ENTITIES_ANY_VALUE");

				$options[$key] = ELGG_ENTITIES_NO_VALUE;
				$this->assertFalse(_elgg_is_valid_options_for_batch_operation($options, $type), "Sent $key = ELGG_ENTITIES_NO_VALUE");

				$options[$key] = false;
				$this->assertFalse(_elgg_is_valid_options_for_batch_operation($options, $type), "Sent $key = bool false");

				$options[$key] = true;
				$this->assertTrue(_elgg_is_valid_options_for_batch_operation($options, $type), "Sent $key = bool true");

				$options[$key] = 'test';
				$this->assertTrue(_elgg_is_valid_options_for_batch_operation($options, $type), "Sent $key = 'test'");

				$options[$key] = ['test'];
				$this->assertTrue(_elgg_is_valid_options_for_batch_operation($options, $type), "Sent $key = array('test')");
			}
		}
	}
}
