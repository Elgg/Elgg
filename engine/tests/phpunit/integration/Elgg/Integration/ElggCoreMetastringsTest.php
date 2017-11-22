<?php

namespace Elgg\Integration;

use ElggAnnotation;

/**
 * Elgg Metastrings test
 *
 * @group IntegrationTests
 * @group Metadata
 * @group Annotations
 */
class ElggCoreMetastringsTest extends \Elgg\LegacyIntegrationTestCase {

	public $metastringTypes = [
		'metadata',
		'annotation'
	];
	public $metastringTables = [
		'metadata' => 'metadata',
		'annotation' => 'annotations',
	];

	public function up() {
		$this->object = $this->createOne('object');
	}

	public function down() {
		$this->object->delete();

		access_show_hidden_entities(true);
		elgg_delete_annotations([
			'guid' => $this->object->guid,
		]);
		access_show_hidden_entities(false);
	}

	public function createAnnotations($max = 1) {
		$annotations = [];
		for ($i = 0; $i < $max; $i++) {
			$name = 'test_annotation_name' . rand();
			$value = 'test_annotation_value' . rand();
			$id = create_annotation($this->object->guid, $name, $value);
			$annotations[] = $id;
		}

		return $annotations;
	}

	public function createMetadata($max = 1) {
		$metadata = [];
		for ($i = 0; $i < $max; $i++) {
			$name = 'test_metadata_name' . rand();
			$value = 'test_metadata_value' . rand();
			$id = _elgg_services()->metadataTable->create($this->object->guid, $name, $value);
			$metadata[] = $id;
		}

		return $metadata;
	}

	public function testDeleteByID() {
		$db_prefix = _elgg_config()->dbprefix;
		$annotation = $this->createAnnotations(1);
		$metadata = $this->createMetadata(1);

		foreach ($this->metastringTypes as $type) {
			$id = ${$type}[0];
			$table = $db_prefix . $this->metastringTables[$type];
			$q = "SELECT * FROM $table WHERE id = $id";
			$test = get_data($q);

			$this->assertEqual($test[0]->id, $id);
			$this->assertTrue(_elgg_delete_metastring_based_object_by_id($id, $type));
			$this->assertIdentical([], get_data($q));
		}
	}

	public function testGetMetastringObjectFromID() {
		$db_prefix = _elgg_config()->dbprefix;
		$annotation = $this->createAnnotations(1);
		$metadata = $this->createMetadata(1);

		foreach ($this->metastringTypes as $type) {
			$id = ${$type}[0];
			$test = _elgg_get_metastring_based_object_from_id($id, $type);

			$this->assertEqual($id, $test->id);
			$this->assertTrue(_elgg_delete_metastring_based_object_by_id($id, $type));
		}
	}

	public function testGetMetastringObjectFromIDWithDisabledAnnotation() {

		$name = 'test_annotation_name' . rand();
		$value = 'test_annotation_value' . rand();

		$id = create_annotation($this->object->guid, $name, $value);

		$this->assertTrue((bool) $id);

		$ia = elgg_set_ignore_access(true);

		$annotation = elgg_get_annotation_from_id($id);

		$this->assertIsA($annotation, ElggAnnotation::class);

		$this->assertTrue($annotation->disable());

		$test = _elgg_get_metastring_based_object_from_id($id, 'annotation');
		$this->assertEqual(false, $test);

		$prev = access_get_show_hidden_status();
		access_show_hidden_entities(true);
		$this->assertTrue(_elgg_delete_metastring_based_object_by_id($id, 'annotation'));
		access_show_hidden_entities($prev);

		elgg_set_ignore_access($ia);
	}

	public function testGetMetastringBasedObjectWithDisabledAnnotation() {
		$name = 'test_annotation_name' . rand();
		$value = 'test_annotation_value' . rand();
		$id = create_annotation($this->object->guid, $name, $value);

		$annotation = elgg_get_annotation_from_id($id);
		$this->assertTrue($annotation->disable());

		$test = elgg_get_annotations([
			'guid' => $this->object->guid,
		]);
		$this->assertEqual([], $test);

		$prev = access_get_show_hidden_status();
		access_show_hidden_entities(true);
		$this->assertTrue(_elgg_delete_metastring_based_object_by_id($id, 'annotation'));
		access_show_hidden_entities($prev);
	}

	public function testEnableDisableByID() {
		$db_prefix = _elgg_config()->dbprefix;
		$annotation = $this->createAnnotations(1);

		$type = 'annotation';

		$id = ${$type}[0];
		$table = $db_prefix . $this->metastringTables[$type];
		$q = "SELECT * FROM $table WHERE id = $id";
		$test = get_data($q);

		// disable
		$this->assertEqual($test[0]->enabled, 'yes');
		$this->assertTrue(_elgg_set_metastring_based_object_enabled_by_id($id, 'no', $type));

		$test = get_data($q);
		$this->assertEqual($test[0]->enabled, 'no');

		// enable
		$ashe = access_get_show_hidden_status();
		access_show_hidden_entities(true);
		$this->assertTrue(_elgg_set_metastring_based_object_enabled_by_id($id, 'yes', $type));

		$test = get_data($q);
		$this->assertEqual($test[0]->enabled, 'yes');

		access_show_hidden_entities($ashe);
		$this->assertTrue(_elgg_delete_metastring_based_object_by_id($id, $type));
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
