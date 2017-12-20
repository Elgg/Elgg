<?php

/**
 * Test \ElggEntity
 *
 */
class ElggCoreEntityTest extends \ElggCoreUnitTest {

	/**
	 * @var \ElggObject
	 */
	protected $entity;

	public function up() {
		// use \ElggObject since \ElggEntity is an abstract class
		$this->entity = new \ElggObject();
		$this->entity->subtype = 'elgg_entity_test_subtype';

		// Add temporary metadata, annotation and private settings
		// to extend the scope of tests and catch issues with save operations
		$this->entity->test_metadata = 'bar';
		$this->entity->annotate('test_annotation', 'baz');
		$this->entity->setPrivateSetting('test_setting', 'foo');

		$this->entity->save();
	}

	public function down() {
		if ($this->entity) {
			$this->entity->delete();
		}
	}

	public function testSubtypePropertyReads() {
		$this->assertTrue($this->entity->save());
		$guid = $this->entity->guid;

		$subtype_prop = $this->entity->subtype;
		$this->assertEqual($subtype_prop, 'elgg_entity_test_subtype');

		$this->entity->invalidateCache();

		$this->entity = null;
		$this->entity = get_entity($guid);

		$subtype_prop = $this->entity->subtype;
		$this->assertIsA($subtype_prop, 'int');
		$this->assertEqual($subtype_prop, 'elgg_entity_test_subtype');
	}

	public function testUnsavedEntitiesDontRecordAttributeSets() {
		$entity = new \ElggObject();
		$entity->subtype = 'elgg_entity_test_subtype';
		$entity->title = 'Foo';
		$entity->description = 'Bar';
		$entity->container_guid = elgg_get_logged_in_user_guid();

		$this->assertEqual($entity->getOriginalAttributes(), []);
	}

	public function testAlreadyPersistedAttributeSetsAreRecorded() {
		$this->entity->container_guid = elgg_get_site_entity()->guid;

		$this->assertEqual($this->entity->getOriginalAttributes(), [
			'container_guid' => elgg_get_logged_in_user_guid(),
		]);
	}

	public function testModifiedAttributesAreAvailableDuringUpdateNotAfter() {
		$this->entity->container_guid = elgg_get_site_entity()->guid;

		$calls = 0;
		$handler = function ($event, $type, \ElggObject $object) use (&$calls) {
			$calls++;
			$this->assertEqual($object->getOriginalAttributes(), [
				'container_guid' => elgg_get_logged_in_user_guid(),
			]);
		};

		elgg_register_event_handler('update', 'object', $handler);
		elgg_register_event_handler('update:after', 'object', $handler);
		$this->entity->save();

		$this->assertEqual($calls, 2);

		elgg_unregister_event_handler('update', 'object', $handler);
		elgg_unregister_event_handler('update:after', 'object', $handler);

		$this->assertEqual($this->entity->getOriginalAttributes(), []);
	}

	public function testModifedAttributesSettingIntsAsStrings() {
		$this->entity->container_guid = elgg_get_logged_in_user_guid();
		$this->entity->save();

		$this->entity->container_guid = (string) elgg_get_logged_in_user_guid();
		$this->assertEqual($this->entity->getOriginalAttributes(), []);
	}

	public function testMultipleAttributeSetsDontOverwriteOriginals() {
		$this->entity->container_guid = 1;
		$this->entity->container_guid = 2;

		$this->assertEqual($this->entity->getOriginalAttributes(), [
			'container_guid' => elgg_get_logged_in_user_guid(),
		]);
	}

	public function testGetSubtype() {
		$guid = $this->entity->guid;

		$this->assertEqual($this->entity->getSubtype(), 'elgg_entity_test_subtype');

		$this->entity->invalidateCache();

		$this->entity = null;
		$this->entity = get_entity($guid);

		$this->assertEqual($this->entity->getSubtype(), 'elgg_entity_test_subtype');
	}

	public function testElggEntityGetAndSetAnnotations() {
		$this->assertIdentical($this->entity->getAnnotations(['annotation_name' => 'non_existent']), []);

		// save entity and check for annotation
		$this->entity->annotate('non_existent', 'foo');
		$annotations = $this->entity->getAnnotations(['annotation_name' => 'non_existent']);
		$this->assertIsA($annotations[0], '\ElggAnnotation');
		$this->assertIdentical($annotations[0]->name, 'non_existent');
		$this->assertEqual($this->entity->countAnnotations('non_existent'), 1);

		// @todo belongs in Annotations API test class
		$this->assertIdentical($annotations, elgg_get_annotations([
			'guid' => $this->entity->getGUID(),
			'annotation_name' => 'non_existent'
		]));
		$this->assertIdentical($annotations, elgg_get_annotations([
			'guid' => $this->entity->getGUID(),
			'annotation_name' => 'non_existent',
			'type' => 'object'
		]));
		$this->assertIdentical(false, elgg_get_annotations([
			'guid' => $this->entity->getGUID(),
			'type' => 'object',
			'subtype' => 'fail'
		]));

		//  clear annotation
		$this->assertTrue($this->entity->deleteAnnotations());
		$this->assertEqual($this->entity->countAnnotations('non_existent'), 0);

		// @todo belongs in Annotations API test class
		$this->assertIdentical([], elgg_get_annotations(['guid' => $this->entity->getGUID()]));
		$this->assertIdentical([], elgg_get_annotations([
			'guid' => $this->entity->getGUID(),
			'type' => 'object'
		]));
	}

	public function testElggEntitySaveAndDelete() {
		// check attributes populated during create()
		$time_minimum = time() - 5;
		$this->assertTrue($this->entity->time_created > $time_minimum);
		$this->assertTrue($this->entity->time_updated > $time_minimum);
		$this->assertEqual($this->entity->container_guid, elgg_get_logged_in_user_guid());
	}

	public function testElggEntityDisableAndEnable() {
		$CONFIG = _elgg_config();

		// add annotations and metadata to check if they're disabled.
		$annotation_id = create_annotation($this->entity->guid, 'test_annotation_' . rand(), 'test_value_' . rand());

		$metadata = new ElggMetadata();
		$metadata->entity_guid = $this->entity->guid;
		$metadata->name = 'test_metadata_' . rand();
		$metadata->value = 'test_value_' . rand();
		$metadata_id = $metadata->save();

		$this->assertTrue($this->entity->disable());

		// ensure disabled by comparing directly with database
		$entity = get_data_row("SELECT * FROM {$CONFIG->dbprefix}entities WHERE guid = '{$this->entity->guid}'");
		$this->assertIdentical($entity->enabled, 'no');

		$annotation = get_data_row("SELECT * FROM {$CONFIG->dbprefix}annotations WHERE id = '$annotation_id'");
		$this->assertIdentical($annotation->enabled, 'no');

		$metadata = get_data_row("SELECT * FROM {$CONFIG->dbprefix}metadata WHERE id = '$metadata_id'");
		$this->assertIdentical($metadata->enabled, 'no');

		// re-enable for deletion to work
		$this->assertTrue($this->entity->enable());

		// check enabled
		// check annotations and metadata enabled.
		$entity = get_data_row("SELECT * FROM {$CONFIG->dbprefix}entities WHERE guid = '{$this->entity->guid}'");
		$this->assertIdentical($entity->enabled, 'yes');

		$annotation = get_data_row("SELECT * FROM {$CONFIG->dbprefix}annotations WHERE id = '$annotation_id'");
		$this->assertIdentical($annotation->enabled, 'yes');

		$metadata = get_data_row("SELECT * FROM {$CONFIG->dbprefix}metadata WHERE id = '$metadata_id'");
		$this->assertIdentical($metadata->enabled, 'yes');

		$this->assertTrue($this->entity->delete());
		$this->entity = null;
	}

	public function testElggEntityRecursiveDisableAndEnable() {
		$CONFIG = _elgg_config();

		$obj1 = new \ElggObject();
		$obj1->container_guid = $this->entity->getGUID();
		$obj1->save();
		$obj2 = new \ElggObject();
		$obj2->container_guid = $this->entity->getGUID();
		$obj2->save();

		// disable $obj2 before disabling the container
		$this->assertTrue($obj2->disable());

		// disable entities container by $this->entity
		$this->assertTrue($this->entity->disable());
		$entity = get_data_row("SELECT * FROM {$CONFIG->dbprefix}entities WHERE guid = '{$obj1->guid}'");
		$this->assertIdentical($entity->enabled, 'no');

		// enable entities that were disabled with the container (but not $obj2)
		$this->assertTrue($this->entity->enable());
		$entity = get_data_row("SELECT * FROM {$CONFIG->dbprefix}entities WHERE guid = '{$obj1->guid}'");
		$this->assertIdentical($entity->enabled, 'yes');
		$entity = get_data_row("SELECT * FROM {$CONFIG->dbprefix}entities WHERE guid = '{$obj2->guid}'");
		$this->assertIdentical($entity->enabled, 'no');

		// cleanup
		$this->assertTrue($obj2->enable());
		$this->assertTrue($obj2->delete());
		$this->assertTrue($obj1->delete());
	}

	public function testElggEntityGetIconURL() {

		elgg_register_plugin_hook_handler('entity:icon:url', 'object', function ($hook, $type, $url, $params) {
			$size = (string) elgg_extract('size', $params);

			return "$size.jpg";
		}, 99999);

		$obj = new \ElggObject();
		$obj->save();

		// Test default size
		$this->assertEqual($obj->getIconURL(), elgg_normalize_url('medium.jpg'));
		// Test size
		$this->assertEqual($obj->getIconURL('small'), elgg_normalize_url('small.jpg'));
		// Test mixed params
		$this->assertEqual($obj->getIconURL('small'), $obj->getIconURL(['size' => 'small']));
		// Test bad param
		$this->assertEqual($obj->getIconURL(new \stdClass), elgg_normalize_url('medium.jpg'));
	}

	public function testCreateWithContainerGuidEqualsZero() {
		$user = $this->createUser();

		$object = new \ElggObject();
		$object->owner_guid = $user->guid;
		$object->container_guid = 0;

		// If container_guid attribute is not updated with owner_guid attribute
		// ElggEntity::getContainerEntity() would return false
		// thus terminating save()
		$this->assertTrue($object->save());

		$this->assertEqual($user->guid, $object->getContainerGUID());

		$user->delete();
	}

	public function testUpdateAbilityDependsOnCanEdit() {
		$this->entity->access_id = ACCESS_PRIVATE;

		$this->assertTrue($this->entity->save());

		$user = $this->createUser();
		$old_user = $this->replaceSession($user);

		// even owner can't bypass permissions
		elgg_register_plugin_hook_handler('permissions_check', 'object', [
			Elgg\Values::class,
			'getFalse'
		], 999);
		$this->assertFalse($this->entity->save());
		elgg_unregister_plugin_hook_handler('permissions_check', 'object', [
			Elgg\Values::class,
			'getFalse'
		]);

		$this->assertFalse($this->entity->save());

		elgg_register_plugin_hook_handler('permissions_check', 'object', [
			Elgg\Values::class,
			'getTrue'
		]);

		// even though this user can't look up the entity via the DB, permission allows update.
		$this->assertFalse(has_access_to_entity($this->entity, $user));
		$this->assertTrue($this->entity->save());

		elgg_unregister_plugin_hook_handler('permissions_check', 'object', [
			Elgg\Values::class,
			'getTrue'
		]);

		// can save with access ignore
		$ia = elgg_set_ignore_access();
		$this->assertTrue($this->entity->save());
		elgg_set_ignore_access($ia);

		$this->replaceSession($old_user);
		$user->delete();
	}

	/**
	 * Make sure entity is loaded from cache during save operations
	 * See #10612
	 */
	public function testNewObjectLoadedFromCacheDuringSaveOperations() {

		$object = new \ElggObject();
		$object->subtype = 'elgg_entity_test_subtype';

		// Add temporary metadata, annotation and private settings
		// to extend the scope of tests and catch issues with save operations
		$object->test_metadata = 'bar';
		$object->annotate('test_annotation', 'baz');
		$object->setPrivateSetting('test_setting', 'foo');

		$metadata_called = false;
		$metadata_event_handler = function ($event, $type, $metadata) use (&$metadata_called) {
			/* @var $metadata \ElggMetadata */
			$entity = get_entity($metadata->entity_guid);
			$this->assertEqual($metadata->entity_guid, $entity->guid);
			$metadata_called = true;
		};

		$annotation_called = false;
		$annotation_event_handler = function ($event, $type, $annotation) use (&$annotation_called) {
			/* @var $metadata \ElggAnnotation */
			$entity = get_entity($annotation->entity_guid);
			$this->assertEqual($annotation->entity_guid, $entity->guid);
			$annotation_called = true;
		};

		elgg_register_event_handler('create', 'metadata', $metadata_event_handler);
		elgg_register_event_handler('create', 'annotation', $annotation_event_handler);

		$object->save();

		elgg_unregister_event_handler('create', 'metadata', $metadata_event_handler);
		elgg_unregister_event_handler('create', 'annotation', $annotation_event_handler);

		$object->delete();

		$this->assertTrue($metadata_called);
		$this->assertTrue($annotation_called);
	}

	/**
	 * Make sure entity is loaded from cache during save operations
	 * See #10612
	 */
	public function testNewUserLoadedFromCacheDuringSaveOperations() {

		$user = new \ElggUser();
		$user->username = $this->getRandomUsername();

		// Add temporary metadata, annotation and private settings
		// to extend the scope of tests and catch issues with save operations
		$user->test_metadata = 'bar';
		$user->annotate('test_annotation', 'baz');
		$user->setPrivateSetting('test_setting', 'foo');

		$metadata_called = false;
		$metadata_event_handler = function ($event, $type, $metadata) use (&$metadata_called) {
			/* @var $metadata \ElggMetadata */
			$entity = get_entity($metadata->entity_guid);
			$this->assertEqual($metadata->entity_guid, $entity->guid);
			$metadata_called = true;
		};

		$annotation_called = false;
		$annotation_event_handler = function ($event, $type, $annotation) use (&$annotation_called) {
			/* @var $metadata \ElggAnnotation */
			$entity = get_entity($annotation->entity_guid);
			$this->assertEqual($annotation->entity_guid, $entity->guid);
			$annotation_called = true;
		};

		elgg_register_event_handler('create', 'metadata', $metadata_event_handler);
		elgg_register_event_handler('create', 'annotation', $annotation_event_handler);

		$user->save();

		elgg_unregister_event_handler('create', 'metadata', $metadata_event_handler);
		elgg_unregister_event_handler('create', 'annotation', $annotation_event_handler);

		$user->delete();

		$this->assertTrue($metadata_called);
		$this->assertTrue($annotation_called);
	}

	/**
	 * Make sure entity is loaded from cache during save operations
	 * See #10612
	 */
	public function testNewGroupLoadedFromCacheDuringSaveOperations() {

		$group = new \ElggGroup();
		$group->subtype = 'test_group_subtype';

		// Add temporary metadata, annotation and private settings
		// to extend the scope of tests and catch issues with save operations
		$group->test_metadata = 'bar';
		$group->annotate('test_annotation', 'baz');
		$group->setPrivateSetting('test_setting', 'foo');

		$metadata_called = false;
		$metadata_event_handler = function ($event, $type, $metadata) use (&$metadata_called) {
			/* @var $metadata \ElggMetadata */
			$entity = get_entity($metadata->entity_guid);
			$this->assertEqual($metadata->entity_guid, $entity->guid);
			$metadata_called = true;
		};

		$annotation_called = false;
		$annotation_event_handler = function ($event, $type, $annotation) use (&$annotation_called) {
			/* @var $metadata \ElggAnnotation */
			$entity = get_entity($annotation->entity_guid);
			$this->assertEqual($annotation->entity_guid, $entity->guid);
			$annotation_called = true;
		};

		elgg_register_event_handler('create', 'metadata', $metadata_event_handler);
		elgg_register_event_handler('create', 'annotation', $annotation_event_handler);

		$group->save();

		elgg_unregister_event_handler('create', 'metadata', $metadata_event_handler);
		elgg_unregister_event_handler('create', 'annotation', $annotation_event_handler);

		$group->delete();

		$this->assertTrue($metadata_called);
		$this->assertTrue($annotation_called);
	}


}
