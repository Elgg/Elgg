<?php

namespace Elgg\Integration;

use ElggObject;
use ElggUser;

/**
 * @group IntegrationTests
 * @group Entities
 * @group EntityPrivateSettings
 */
class ElggCoreEntityTest extends \Elgg\IntegrationTestCase {

	/**
	 * @var ElggObject
	 */
	protected $entity;

	public function up() {
		_elgg_services()->session->setLoggedInUser($this->getAdmin());

		// use \ElggObject since \ElggEntity is an abstract class
		$this->entity = new ElggObject();
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
		_elgg_services()->session->removeLoggedInUser();
	}

	public function testSubtypePropertyReads() {
		$this->assertTrue($this->entity->save());
		$guid = $this->entity->guid;

		$subtype_prop = $this->entity->subtype;
		$this->assertEquals('elgg_entity_test_subtype', $subtype_prop);

		$this->entity->invalidateCache();

		$this->entity = null;
		$this->entity = get_entity($guid);

		$subtype_prop = $this->entity->subtype;
		$this->assertEquals('elgg_entity_test_subtype', $subtype_prop);
	}

	public function testUnsavedEntitiesDontRecordAttributeSets() {
		$entity = new ElggObject();
		$entity->subtype = 'elgg_entity_test_subtype';
		$entity->title = 'Foo';
		$entity->description = 'Bar';
		$entity->container_guid = elgg_get_logged_in_user_guid();

		$this->assertEquals([], $entity->getOriginalAttributes());
	}

	public function testAlreadyPersistedAttributeSetsAreRecorded() {
		$this->entity->container_guid = elgg_get_site_entity()->guid;

		$this->assertEquals([
			'container_guid' => elgg_get_logged_in_user_guid(),
		], $this->entity->getOriginalAttributes());
	}

	public function testModifiedAttributesAreAvailableDuringUpdateNotAfter() {
		$this->entity->container_guid = elgg_get_site_entity()->guid;

		$calls = 0;
		$handler = function ($event, $type, ElggObject $object) use (&$calls) {
			$calls++;
			$this->assertEquals([
				'container_guid' => elgg_get_logged_in_user_guid(),
			], $object->getOriginalAttributes());
		};

		elgg_register_event_handler('update', 'object', $handler);
		elgg_register_event_handler('update:after', 'object', $handler);
		$this->entity->save();

		$this->assertEquals(2, $calls);

		elgg_unregister_event_handler('update', 'object', $handler);
		elgg_unregister_event_handler('update:after', 'object', $handler);

		$this->assertEquals([], $this->entity->getOriginalAttributes());
	}

	public function testModifedAttributesSettingIntsAsStrings() {
		$this->entity->container_guid = elgg_get_logged_in_user_guid();
		$this->entity->save();

		$this->entity->container_guid = (string) elgg_get_logged_in_user_guid();
		$this->assertEquals([], $this->entity->getOriginalAttributes());
	}

	public function testMultipleAttributeSetsDontOverwriteOriginals() {
		$this->entity->container_guid = 1;
		$this->entity->container_guid = 2;

		$this->assertEquals([
			'container_guid' => elgg_get_logged_in_user_guid(),
		], $this->entity->getOriginalAttributes());
	}

	public function testGetSubtype() {
		$guid = $this->entity->guid;

		$this->assertEquals('elgg_entity_test_subtype', $this->entity->getSubtype());

		$this->entity->invalidateCache();

		$this->entity = null;
		$this->entity = get_entity($guid);

		$this->assertEquals('elgg_entity_test_subtype', $this->entity->getSubtype());
	}

	public function testElggEntityGetAndSetAnnotations() {

		$this->assertEquals([], $this->entity->getAnnotations(['annotation_name' => 'non_existent']));

		// save entity and check for annotation
		$this->entity->annotate('non_existent', 'foo');
		$annotations = $this->entity->getAnnotations(['annotation_name' => 'non_existent']);
		$this->assertInstanceOf(\ElggAnnotation::class, $annotations[0]);
		$this->assertEquals('non_existent', $annotations[0]->name);
		$this->assertEquals(1, $this->entity->countAnnotations('non_existent'));

		// @todo belongs in Annotations API test class
		$this->assertEquals($annotations, elgg_get_annotations([
			'guid' => $this->entity->getGUID(),
			'annotation_name' => 'non_existent'
		]));
		
		$this->assertEquals($annotations, elgg_get_annotations([
			'guid' => $this->entity->getGUID(),
			'annotation_name' => 'non_existent',
			'type' => 'object'
		]));
		$this->assertEquals([], elgg_get_annotations([
			'guid' => $this->entity->getGUID(),
			'type' => 'object',
			'subtype' => 'fail'
		]));

		//  clear annotation
		$this->assertTrue($this->entity->deleteAnnotations());
		$this->assertEquals(0, $this->entity->countAnnotations('non_existent'));

		// @todo belongs in Annotations API test class
		$this->assertEquals([], elgg_get_annotations(['guid' => $this->entity->getGUID()]));
		$this->assertEquals([], elgg_get_annotations([
			'guid' => $this->entity->getGUID(),
			'type' => 'object'
		]));
	}

	public function testElggEntitySaveAndDelete() {
		// check attributes populated during create()
		$time_minimum = time() - 5;
		$this->assertTrue($this->entity->time_created > $time_minimum);
		$this->assertTrue($this->entity->time_updated > $time_minimum);
		$this->assertEquals(elgg_get_logged_in_user_guid(), $this->entity->container_guid);
	}

	public function testElggEntityDisableAndEnable() {
		$CONFIG = _elgg_config();

		// add annotations and metadata to check if they're disabled.
		$annotation_id = create_annotation($this->entity->guid, 'test_annotation_' . rand(), 'test_value_' . rand());
		
		$this->assertTrue($this->entity->disable());

		// ensure disabled by comparing directly with database
		$entity = get_data_row("SELECT * FROM {$CONFIG->dbprefix}entities WHERE guid = '{$this->entity->guid}'");
		$this->assertEquals('no', $entity->enabled);

		$annotation = get_data_row("SELECT * FROM {$CONFIG->dbprefix}annotations WHERE id = '$annotation_id'");
		$this->assertEquals('no', $annotation->enabled);

		// re-enable for deletion to work
		$this->assertTrue($this->entity->enable());

		// check enabled
		// check annotations and metadata enabled.
		$entity = get_data_row("SELECT * FROM {$CONFIG->dbprefix}entities WHERE guid = '{$this->entity->guid}'");
		$this->assertEquals('yes', $entity->enabled);

		$annotation = get_data_row("SELECT * FROM {$CONFIG->dbprefix}annotations WHERE id = '$annotation_id'");
		$this->assertEquals('yes', $annotation->enabled);

		$this->assertTrue($this->entity->delete());
		$this->entity = null;
	}

	public function testElggEntityRecursiveDisableAndEnable() {
		$CONFIG = _elgg_config();

		$obj1 = new ElggObject();
		$obj1->subtype = $this->getRandomSubtype();
		$obj1->container_guid = $this->entity->getGUID();
		$obj1->save();
		$obj2 = new ElggObject();
		$obj2->subtype = $this->getRandomSubtype();
		$obj2->container_guid = $this->entity->getGUID();
		$obj2->save();

		// disable $obj2 before disabling the container
		$this->assertTrue($obj2->disable());

		// disable entities container by $this->entity
		$this->assertTrue($this->entity->disable());
		$entity = get_data_row("SELECT * FROM {$CONFIG->dbprefix}entities WHERE guid = '{$obj1->guid}'");
		$this->assertEquals('no', $entity->enabled);

		// enable entities that were disabled with the container (but not $obj2)
		$this->assertTrue($this->entity->enable());
		$entity = get_data_row("SELECT * FROM {$CONFIG->dbprefix}entities WHERE guid = '{$obj1->guid}'");
		$this->assertEquals('yes', $entity->enabled);
		$entity = get_data_row("SELECT * FROM {$CONFIG->dbprefix}entities WHERE guid = '{$obj2->guid}'");
		$this->assertEquals('no', $entity->enabled);

		// cleanup
		$this->assertTrue($obj2->enable());
		$this->assertTrue($obj2->delete());
		$this->assertTrue($obj1->delete());
	}

	public function testElggEntityGetIconURL() {

		$handler = function ($hook, $type, $url, $params) {
			$size = (string) elgg_extract('size', $params);

			return "$size.jpg";
		};

		elgg_register_plugin_hook_handler('entity:icon:url', 'object', $handler, 99999);

		$obj = new ElggObject();
		$obj->subtype = $this->getRandomSubtype();
		$obj->save();

		// Test default size
		$this->assertEquals(elgg_normalize_url('medium.jpg'), $obj->getIconURL());
		// Test size
		$this->assertEquals(elgg_normalize_url('small.jpg'), $obj->getIconURL('small'));
		// Test mixed params
		$this->assertEquals($obj->getIconURL(['size' => 'small']), $obj->getIconURL('small'));
		// Test bad param
		$this->assertEquals(elgg_normalize_url('medium.jpg'), $obj->getIconURL(new \stdClass));

		elgg_unregister_plugin_hook_handler('entity:icon:url', 'object', $handler);
	}

	public function testCreateWithContainerGuidEqualsZero() {
		$user = $this->createUser();

		$object = new ElggObject();
		$object->subtype = $this->getRandomSubtype();
		$object->owner_guid = $user->guid;
		$object->container_guid = 0;

		// If container_guid attribute is not updated with owner_guid attribute
		// ElggEntity::getContainerEntity() would return false
		// thus terminating save()
		$this->assertGreaterThan(0, $object->save());

		$this->assertEquals($object->getContainerGUID(), $user->guid);

		$user->delete();
	}

	public function testUpdateAbilityDependsOnCanEdit() {
		$this->entity->access_id = ACCESS_PRIVATE;

		$this->assertTrue($this->entity->save());

		$user = $this->createUser();

		$old_user = _elgg_services()->session->getLoggedInUser();
		_elgg_services()->session->setLoggedInUser($user);
		
		// even owner can't bypass permissions
		elgg_register_plugin_hook_handler('permissions_check', 'object', [
			\Elgg\Values::class,
			'getFalse'
		], 999);
		$this->assertFalse($this->entity->save());
		elgg_unregister_plugin_hook_handler('permissions_check', 'object', [
			\Elgg\Values::class,
			'getFalse'
		]);

		$this->assertFalse($this->entity->save());

		elgg_register_plugin_hook_handler('permissions_check', 'object', [
			\Elgg\Values::class,
			'getTrue'
		]);

		// even though this user can't look up the entity via the DB, permission allows update.
		$this->assertFalse(has_access_to_entity($this->entity, $user));
		$this->assertTrue($this->entity->save());

		elgg_unregister_plugin_hook_handler('permissions_check', 'object', [
			\Elgg\Values::class,
			'getTrue'
		]);

		// can save with access ignore
		$ia = elgg_set_ignore_access();
		$this->assertTrue($this->entity->save());
		elgg_set_ignore_access($ia);

		$user->delete();

		_elgg_services()->session->setLoggedInUser($old_user);
	}

	/**
	 * Make sure entity is loaded from cache during save operations
	 * See #10612
	 */
	public function testNewObjectLoadedFromCacheDuringSaveOperations() {

		$object = new ElggObject();
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
			$this->assertEquals($entity->guid, $metadata->entity_guid);
			$metadata_called = true;
		};

		$annotation_called = false;
		$annotation_event_handler = function ($event, $type, $annotation) use (&$annotation_called) {
			/* @var $metadata \ElggAnnotation */
			$entity = get_entity($annotation->entity_guid);
			$this->assertEquals($entity->guid, $annotation->entity_guid);
			$annotation_called = true;
		};

		elgg_register_event_handler('create', 'metadata', $metadata_event_handler);
		elgg_register_event_handler('create', 'annotation', $annotation_event_handler);

		$object->save();

		elgg_unregister_event_handler('create', 'metadata', $metadata_event_handler);
		elgg_unregister_event_handler('create', 'annotation', $annotation_event_handler);

		$object->delete();

		$this->assertTrue((bool) $metadata_called);
		$this->assertTrue((bool) $annotation_called);
	}

	/**
	 * Make sure entity is loaded from cache during save operations
	 * See #10612
	 */
	public function testNewUserLoadedFromCacheDuringSaveOperations() {

		$user = new ElggUser();
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
			$this->assertEquals($entity->guid, $metadata->entity_guid);
			$metadata_called = true;
		};

		$annotation_called = false;
		$annotation_event_handler = function ($event, $type, $annotation) use (&$annotation_called) {
			/* @var $metadata \ElggAnnotation */
			$entity = get_entity($annotation->entity_guid);
			$this->assertEquals($entity->guid, $annotation->entity_guid);
			$annotation_called = true;
		};

		elgg_register_event_handler('create', 'metadata', $metadata_event_handler);
		elgg_register_event_handler('create', 'annotation', $annotation_event_handler);

		$user->save();

		elgg_unregister_event_handler('create', 'metadata', $metadata_event_handler);
		elgg_unregister_event_handler('create', 'annotation', $annotation_event_handler);

		$user->delete();

		$this->assertTrue((bool) $metadata_called);
		$this->assertTrue((bool) $annotation_called);
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
			$this->assertEquals($entity->guid, $metadata->entity_guid);
			$metadata_called = true;
		};

		$annotation_called = false;
		$annotation_event_handler = function ($event, $type, $annotation) use (&$annotation_called) {
			/* @var $metadata \ElggAnnotation */
			$entity = get_entity($annotation->entity_guid);
			$this->assertEquals($entity->guid, $annotation->entity_guid);
			$annotation_called = true;
		};

		elgg_register_event_handler('create', 'metadata', $metadata_event_handler);
		elgg_register_event_handler('create', 'annotation', $annotation_event_handler);

		$group->save();

		elgg_unregister_event_handler('create', 'metadata', $metadata_event_handler);
		elgg_unregister_event_handler('create', 'annotation', $annotation_event_handler);

		$group->delete();

		$this->assertTrue((bool) $metadata_called);
		$this->assertTrue((bool) $annotation_called);
	}


}
