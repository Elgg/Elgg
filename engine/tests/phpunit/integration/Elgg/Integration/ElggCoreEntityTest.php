<?php

namespace Elgg\Integration;

use Elgg\Hook;
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
	
	/**
	 * @var \ElggUser
	 */
	protected $owner;

	public function up() {
		$this->owner = $this->createUser();
		elgg()->session->setLoggedInUser($this->owner);
		
		// use \ElggObject since \ElggEntity is an abstract class
		$this->entity = new ElggObject();
		$this->entity->setSubtype('elgg_entity_test_subtype');

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
		
		if ($this->owner) {
			$this->owner->delete();
		}
		
		elgg()->session->removeLoggedInUser();
	}

	public function testSubtypePropertyReads() {
		$this->assertTrue($this->entity->save());
		$guid = $this->entity->guid;

		$subtype_prop = $this->entity->subtype;
		$this->assertEquals($subtype_prop, 'elgg_entity_test_subtype');

		$this->entity->invalidateCache();

		$this->entity = null;
		$this->entity = get_entity($guid);

		$subtype_prop = $this->entity->subtype;
		$this->assertEquals($subtype_prop, 'elgg_entity_test_subtype');
	}

	public function testUnsavedEntitiesDontRecordAttributeSets() {
		$entity = new ElggObject();
		$entity->setSubtype('elgg_entity_test_subtype');
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
		$handler = function (\Elgg\Event $event) use (&$calls) {
			$calls++;
			$this->assertEquals([
				'container_guid' => elgg_get_logged_in_user_guid(),
			], $event->getObject()->getOriginalAttributes());
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

		$this->assertEquals([], $this->entity->getAnnotations([
			'annotation_name' => 'non_existent',
		]));

		// save entity and check for annotation
		$this->entity->annotate('non_existent', 'foo');
		$annotations = $this->entity->getAnnotations(['annotation_name' => 'non_existent']);
		$this->assertInstanceOf(\ElggAnnotation::class, $annotations[0]);
		$this->assertEquals('non_existent', $annotations[0]->name);
		$this->assertEquals(1, $this->entity->countAnnotations('non_existent'));

		// @todo belongs in Annotations API test class
		$this->assertEquals($annotations, elgg_get_annotations([
			'guid' => $this->entity->guid,
			'annotation_name' => 'non_existent',
		]));
		$this->assertEquals($annotations, elgg_get_annotations([
			'guid' => $this->entity->guid,
			'annotation_name' => 'non_existent',
			'type' => 'object',
		]));
		$this->assertEquals([], elgg_get_annotations([
			'guid' => $this->entity->guid,
			'type' => 'object',
			'subtype' => 'fail',
		]));

		//  clear annotation
		$this->assertTrue($this->entity->deleteAnnotations());
		$this->assertEquals(0, $this->entity->countAnnotations('non_existent'));

		// @todo belongs in Annotations API test class
		$this->assertEquals([], elgg_get_annotations(['guid' => $this->entity->getGUID()]));
		$this->assertEquals([], elgg_get_annotations([
			'guid' => $this->entity->guid,
			'type' => 'object',
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
		$dbprefix = elgg()->db->prefix;

		// add annotations and metadata to check if they're disabled.
		$annotation_id = $this->entity->annotate('test_annotation_' . rand(), 'test_value_' . rand());
		
		$this->assertTrue($this->entity->disable());

		// ensure disabled by comparing directly with database
		$entity = elgg()->db->getDataRow("SELECT *
			FROM {$dbprefix}entities
			WHERE guid = '{$this->entity->guid}'
		");
		$this->assertEquals('no', $entity->enabled);

		$annotation = elgg()->db->getDataRow("SELECT *
			FROM {$dbprefix}annotations
			WHERE id = '$annotation_id'
		");
		$this->assertEquals('no', $annotation->enabled);

		// re-enable for deletion to work
		$this->assertTrue($this->entity->enable());

		// check enabled
		// check annotations and metadata enabled.
		$entity = elgg()->db->getDataRow("SELECT *
			FROM {$dbprefix}entities
			WHERE guid = '{$this->entity->guid}'
		");
		$this->assertEquals('yes', $entity->enabled);

		$annotation = elgg()->db->getDataRow("SELECT *
			FROM {$dbprefix}annotations
			WHERE id = '$annotation_id'
		");
		$this->assertEquals('yes', $annotation->enabled);

		$this->assertTrue($this->entity->delete());
		$this->entity = null;
	}

	public function testElggEntityRecursiveDisableAndEnable() {
		$CONFIG = _elgg_services()->config;

		$obj1 = new ElggObject();
		$obj1->setSubtype($this->getRandomSubtype());
		$obj1->container_guid = $this->entity->getGUID();
		$obj1->save();
		$obj2 = new ElggObject();
		$obj2->setSubtype($this->getRandomSubtype());
		$obj2->container_guid = $this->entity->getGUID();
		$obj2->save();

		// disable $obj2 before disabling the container
		$this->assertTrue($obj2->disable());

		// disable entities container by $this->entity
		$this->assertTrue($this->entity->disable());
		$entity = elgg()->db->getDataRow("SELECT *
			FROM {$CONFIG->dbprefix}entities
			WHERE guid = '{$obj1->guid}'
		");
		$this->assertEquals('no', $entity->enabled);

		// enable entities that were disabled with the container (but not $obj2)
		$this->assertTrue($this->entity->enable());
		$entity = elgg()->db->getDataRow("SELECT *
			FROM {$CONFIG->dbprefix}entities
			WHERE guid = '{$obj1->guid}'
		");
		$this->assertEquals('yes', $entity->enabled);
		$entity = elgg()->db->getDataRow("SELECT *
			FROM {$CONFIG->dbprefix}entities
			WHERE guid = '{$obj2->guid}'
		");
		$this->assertEquals('no', $entity->enabled);

		// cleanup
		$this->assertTrue($obj2->enable());
		$this->assertTrue($obj2->delete());
		$this->assertTrue($obj1->delete());
	}

	public function testElggEntityGetIconURL() {

		$handler = function(\Elgg\Hook $hook) {
			$size = (string) $hook->getParam('size');

			return "$size.jpg";
		};

		elgg_register_plugin_hook_handler('entity:icon:url', 'object', $handler, 99999);

		$obj = new ElggObject();
		$obj->setSubtype($this->getRandomSubtype());
		$obj->save();

		// Test default size
		$this->assertEquals(elgg_normalize_url('medium.jpg'), $obj->getIconURL());
		// Test size
		$this->assertEquals(elgg_normalize_url('small.jpg'), $obj->getIconURL('small'));
		// Test mixed params
		$this->assertEquals($obj->getIconURL('small'), $obj->getIconURL(['size' => 'small']));
		// Test bad param
		$this->assertEquals(elgg_normalize_url('medium.jpg'), $obj->getIconURL(new \stdClass));

		elgg_unregister_plugin_hook_handler('entity:icon:url', 'object', $handler, 99999);
	}

	public function testCreateWithContainerGuidEqualsZero() {
		$user = $this->owner;

		$object = new ElggObject();
		$object->setSubtype($this->getRandomSubtype());
		$object->owner_guid = $user->guid;
		$object->container_guid = 0;

		// If container_guid attribute is not updated with owner_guid attribute
		// ElggEntity::getContainerEntity() would return false
		// thus terminating save()
		$this->assertTrue($object->save());

		$this->assertEquals($user->guid, $object->getContainerGUID());
	}

	public function testUpdateAbilityDependsOnCanEdit() {
		$this->entity->access_id = ACCESS_PRIVATE;

		$this->assertTrue($this->entity->save());

		$user = $this->createOne('user');

		$old_user = elgg()->session->getLoggedInUser();
		elgg()->session->setLoggedInUser($user);
		
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
		elgg_call(ELGG_IGNORE_ACCESS, function() {
			$this->assertTrue($this->entity->save());
		});
		
		elgg()->session->setLoggedInUser($old_user);
		$user->delete();
	}

	/**
	 * Make sure entity is loaded from cache during save operations
	 * See #10612
	 */
	public function testNewObjectLoadedFromCacheDuringSaveOperations() {

		$object = new ElggObject();
		$object->setSubtype('elgg_entity_test_subtype');

		// Add temporary metadata, annotation and private settings
		// to extend the scope of tests and catch issues with save operations
		$object->test_metadata = 'bar';
		$object->annotate('test_annotation', 'baz');
		$object->setPrivateSetting('test_setting', 'foo');

		$metadata_called = false;
		$metadata_event_handler = function (\Elgg\Event $event) use (&$metadata_called) {
			/* @var $metadata \ElggMetadata */
			$metadata = $event->getObject();
			$entity = get_entity($metadata->entity_guid);
			$this->assertEquals($metadata->entity_guid, $entity->guid);
			$metadata_called = true;
		};

		$annotation_called = false;
		$annotation_event_handler = function (\Elgg\Event $event) use (&$annotation_called) {
			/* @var $annotation \ElggAnnotation */
			$annotation = $event->getObject();
			$entity = get_entity($annotation->entity_guid);
			$this->assertEquals($annotation->entity_guid, $entity->guid);
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

		$user = new ElggUser();
		$user->username = $this->getRandomUsername();

		// Add temporary metadata, annotation and private settings
		// to extend the scope of tests and catch issues with save operations
		$user->test_metadata = 'bar';
		$user->annotate('test_annotation', 'baz');
		$user->setPrivateSetting('test_setting', 'foo');

		$metadata_called = false;
		$metadata_event_handler = function (\Elgg\Event $event) use (&$metadata_called) {
			/* @var $metadata \ElggMetadata */
			$metadata = $event->getObject();
			$entity = get_entity($metadata->entity_guid);
			$this->assertEquals($metadata->entity_guid, $entity->guid);
			$metadata_called = true;
		};

		$annotation_called = false;
		$annotation_event_handler = function (\Elgg\Event $event) use (&$annotation_called) {
			/* @var $metadata \ElggAnnotation */
			$annotation = $event->getObject();
			$entity = get_entity($annotation->entity_guid);
			$this->assertEquals($annotation->entity_guid, $entity->guid);
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
		$group->setSubtype('test_group_subtype');

		// Add temporary metadata, annotation and private settings
		// to extend the scope of tests and catch issues with save operations
		$group->test_metadata = 'bar';
		$group->annotate('test_annotation', 'baz');
		$group->setPrivateSetting('test_setting', 'foo');

		$metadata_called = false;
		$metadata_event_handler = function (\Elgg\Event $event) use (&$metadata_called) {
			/* @var $metadata \ElggMetadata */
			$metadata = $event->getObject();
			$entity = get_entity($metadata->entity_guid);
			$this->assertEquals($metadata->entity_guid, $entity->guid);
			$metadata_called = true;
		};

		$annotation_called = false;
		$annotation_event_handler = function (\Elgg\Event $event) use (&$annotation_called) {
			/* @var $metadata \ElggAnnotation */
			$annotation = $event->getObject();
			$entity = get_entity($annotation->entity_guid);
			$this->assertEquals($annotation->entity_guid, $entity->guid);
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

	/**
	 * @see https://github.com/Elgg/Elgg/pull/11998
	 *
	 * @group AccessSQL
	 * @group Access
	 */
	public function testDatabaseRowContainsCorrectParametersWhenJoined() {

		_elgg_services()->hooks->backup();

		$handler = function(Hook $hook) {
			if ($hook->getParam('ignore_access')) {
				return;
			}
			
			$value = $hook->getValue();

			$alias = $hook->getParam('table_alias');
			$guid_column = $hook->getParam('guid_column');

			$qb = $hook->getParam('query_builder');
			/* @var $qb \Elgg\Database\QueryBuilder */

			$qb->joinMetadataTable($alias, $guid_column, 'foo', 'inner', 'md');

			$value['ands'][] = $qb->where($qb->compare('md.value', '=', 'bar', ELGG_VALUE_STRING));

			return $value;
		};

		$entity = $this->createObject();

		$hook = $this->registerTestingHook('get_sql', 'access', $handler);

		elgg_call(ELGG_ENFORCE_ACCESS, function() use ($entity) {
			$row = _elgg_services()->entityTable->getRow($entity->guid);
			$this->assertEmpty($row);

			elgg_call(ELGG_IGNORE_ACCESS,function() use ($entity) {
				$entity->foo = 'bar';
				$entity->save();
			});

			$row = _elgg_services()->entityTable->getRow($entity->guid);
			$this->assertInstanceOf(\stdClass::class, $row);

			$this->assertEquals($entity->guid, $row->guid);
			$this->assertEquals($entity->owner_guid, $row->owner_guid);
			$this->assertEquals($entity->container_guid, $row->container_guid);
			$this->assertEquals($entity->access_id, $row->access_id);
			$this->assertEquals($entity->enabled, $row->enabled);
			$this->assertEquals($entity->time_created, $row->time_created);
		});

		$hook->unregister();

		_elgg_services()->hooks->restore();
	}

	public function testContainerTimeUpdatedChangesOnEntityCreate() {
		$old_ts = time() - 10;
		
		$container = $this->createGroup();
		$container->updateLastAction($old_ts);
		
		$this->assertEquals($old_ts, $container->last_action);
		
		// triggering entity create should update container last action
		$object = $this->createObject([
			'container_guid' => $container->guid,
		]);
		
		$current_last_action = $container->last_action;
		
		$this->assertNotEquals($old_ts, $current_last_action);
		
		// object update should not update container last action
		$container->updateLastAction($old_ts);
		$object->foo = 'bar';
		$this->assertEquals($old_ts, $container->last_action);
		
		// object delete should not update container last action
		$container->updateLastAction($old_ts);
		$object->delete();
		$this->assertEquals($old_ts, $container->last_action);
	}
	
	/**
	 * @dataProvider entitiesFromCacheProvider
	 */
	public function testEntityGetReturnedFromCache($type, $subtype, $check_type, $check_subtype) {
		$entity = $this->createOne($type, [
			'subtype' => $subtype,
		]);
		
		$guid = $entity->guid;
		$this->assertNotEmpty($guid);
		
		$entity->setVolatileData('temp_cache_data', true);
		$this->assertNotEmpty($entity->getVolatileData('temp_cache_data'));
		
		// store in entity cache
		$entity->cache();
		
		// entity should be returned from cache and contain the volatile data
		$from_cache_entity = _elgg_services()->entityTable->get($guid, $check_type, $check_subtype);
		$this->assertTrue($from_cache_entity->getVolatileData('temp_cache_data'));
		
		// flush cache and check if entity does not contain volatile data (thus came from db)
		$entity->invalidateCache();
		
		$not_cached_entity = _elgg_services()->entityTable->get($guid, $check_type, $check_subtype);
		$this->assertEmpty($not_cached_entity->getVolatileData('temp_cache_data'));
		
		if (!empty($check_subtype)) {
			$not_cached_entity->setSubtype("{$subtype}_alt");
			$this->assertEquals("{$subtype}_alt", $not_cached_entity->subtype);
			
			$not_cached_entity->setVolatileData('alt_types', true);
			$not_cached_entity->cache();
			$this->assertTrue($not_cached_entity->getVolatileData('alt_types'));
			
			// if cache type does not match requested type, return from database if type matches
			$from_db_entity = _elgg_services()->entityTable->get($guid, $check_type, $check_subtype);
			$this->assertInstanceOf(\ElggEntity::class, $from_db_entity);
			$this->assertEmpty($from_db_entity->getVolatileData('alt_types'));
		}
				
		// cleanup
		$entity->delete();
	}
	
	public function entitiesFromCacheProvider() {
		return [
			['object', 'foo', null, null],
			['object', 'foo', 'object', null],
			['object', 'foo', 'object', 'foo'],
			['object', 'foo', null, 'foo'],
			['user', null, null, null],
			['user', null, 'user', null],
			['user', 'foo', 'user', 'foo'],
		];
	}
	
	/**
	 * @dataProvider entitiesNotTypesMatch
	 */
	public function testEntityGetNotReturnedIfTypesMismatch($type, $subtype, $check_type, $check_subtype) {
		$entity = $this->createOne($type, ['subtype' => $subtype]);
		
		$guid = $entity->guid;
		$this->assertNotEmpty($guid);
		
		// entity should not be returned from db or cache
		$this->assertFalse(_elgg_services()->entityTable->get($guid, $check_type, $check_subtype));
				
		// cleanup
		$entity->delete();
	}
	
	public function entitiesNotTypesMatch() {
		return [
			['object', 'foo', 0, null],
			['object', 'foo', 0, 0],
			['object', 'foo', null, 0],
			['object', 'foo', '', ''],
			['object', 'foo', 'not_object', null],
			['object', 'foo', 'not_object', 'foo'],
			['object', 'foo', 'object', 'false'],
			['object', 'foo', null, 'false'],
		];
	}
}
