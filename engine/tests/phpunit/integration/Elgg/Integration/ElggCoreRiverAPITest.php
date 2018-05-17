<?php

namespace Elgg\Integration;

use Elgg\Event;
use Elgg\Values;
use ElggRiveritem;
use DateTime;

/**
 * Elgg Test river api
 *
 * @group IntegrationTests
 * @group River
 */
class ElggCoreRiverAPITest extends \Elgg\IntegrationTestCase {

	/**
	 * @var \ElggObject
	 */
	protected $entity;

	/**
	 * @var \ElggUser
	 */
	protected $user;

	/**
	 * @var DateTime
	 */
	protected $dt;

	public function up() {
		$user = $this->createOne('user');
		
		$entity = $this->createOne('object', [
			'owner_guid' => $user->guid,
		]);
		
		$this->user = $user;
		$this->entity = $entity;

		_elgg_services()->session->setLoggedInUser($user);
		
		// By default, only admins are allowed to delete river items
		// For the sake of this test case, we will allow the user to delete items
		elgg_register_plugin_hook_handler('permissions_check:delete', 'river', [
			$this,
			'allowDelete'
		]);

		$dt = new DateTime();
		_elgg_services()->river->setCurrentTime($dt);
		$this->dt = $dt;
	}

	public function down() {
		
		_elgg_services()->session->setLoggedInUser($this->getAdmin());
		
		if (isset($this->user)) {
			$this->user->delete();
		}
		
		if (isset($this->entity)) {
			$this->entity->delete();
		}

		elgg_unregister_plugin_hook_handler('permissions_check:delete', 'river', [
			$this,
			'allowDelete'
		]);
		
		_elgg_services()->session->removeLoggedInUser();
	}

	public function allowDelete($hook, $type, $return, $params) {
		
		$hook_user = elgg_extract('user', $params);
		if (!$hook_user) {
			return;
		}
		
		if ($this->user->guid === $hook_user->guid) {
			return true;
		}
	}

	public function testCanCreateRiverItem() {

		$params = [
			'action_type' => 'create',
			'object_guid' => $this->entity->guid,
		];

		$id = elgg_create_river_item($params);
		$this->assertTrue(is_int($id));
		$this->assertTrue(elgg_delete_river(['id' => $id]));
		
		$params['return_item'] = true;
		$item = elgg_create_river_item($params);

		$this->assertEquals('river', $item->getType());
		$this->assertEquals('item', $item->getSubtype());

		$this->assertInstanceOf(\stdClass::class, $item->toObject());

		$this->assertInstanceOf(ElggRiverItem::class, $item);
		$this->assertTrue(elgg_delete_river(['id' => $item->id]));
	}

	public function testRiverCreationEmitsHookAndEvent() {
		$params = [
			'action_type' => 'create',
			'subject_guid' => $this->user->guid,
			'object_guid' => $this->entity->guid,
			'posted' => time(),
			'return_item' => true,
		];

		$captured = [];
		$hook_handler = function ($hook, $type, $value, $params) use (&$captured) {
			$captured['hook_value'] = $value;
		};
		$event_handler = function ($event, $type, $object) use (&$captured) {
			$captured['event_object'] = $object;
		};

		elgg_register_plugin_hook_handler('creating', 'river', $hook_handler);
		elgg_register_event_handler('created', 'river', $event_handler);
		$item = elgg_create_river_item($params);
		$this->assertInstanceOf(ElggRiverItem::class, $item);
		elgg_unregister_plugin_hook_handler('creating', 'river', $hook_handler);
		elgg_unregister_event_handler('created', 'river', $event_handler);

		$expected_values = [
			'action_type' => 'create',
			'subject_guid' => $this->user->guid,
			'object_guid' => $this->entity->guid,
			'target_guid' => $this->entity->container_guid,
			'annotation_id' => 0,
			'posted' => $this->dt->getTimestamp(),
		];
		foreach ($expected_values as $key => $value) {
			$this->assertEquals($captured['hook_value'][$key], $value);
		}
		$this->assertSame($item, $captured['event_object']);

		$this->assertTrue($item->delete());
	}

	public function testCanCancelRiverItemViaHook() {
		$params = [
			'action_type' => 'create',
			'subject_guid' => $this->user->guid,
			'object_guid' => $this->entity->guid,
		];

		elgg_register_plugin_hook_handler('creating', 'river', [
			Values::class,
			'getFalse'
		]);
		$id = elgg_create_river_item($params);
		elgg_unregister_plugin_hook_handler('creating', 'river', [
			Values::class,
			'getFalse'
		]);

		$this->assertFalse($id); // prevented
	}

	public function testCanCancelRiverDeleteByEvent() {
		$params = [
			'action_type' => 'create',
			'subject_guid' => $this->user->guid,
			'object_guid' => $this->entity->guid,
			'return_item' => true,
		];
		$item = elgg_create_river_item($params);

		elgg_register_event_handler('delete:before', 'river', [
			Values::class,
			'getFalse'
		]);
		$this->assertFalse($item->delete());
		elgg_unregister_event_handler('delete:before', 'river', [
			Values::class,
			'getFalse'
		]);

		$items = elgg_get_river(['id' => $item->id]);
		$this->assertEquals(count($items), 1);

		$this->assertTrue($item->delete());
	}

	public function testRiverDeleteFiresAfterEvent() {
		// [delete:after, river]

		$params = [
			'action_type' => 'create',
			'subject_guid' => $this->user->guid,
			'object_guid' => $this->entity->guid,
			'return_item' => true,
		];
		$item = elgg_create_river_item($params);

		$captured = null;
		$event_handler = function ($event, $type, $object) use (&$captured) {
			$captured = $object;
		};

		elgg_register_event_handler('delete:after', 'river', $event_handler);
		$item->delete();
		elgg_unregister_event_handler('delete:after', 'river', $event_handler);

		$this->assertSame($item, $captured);
	}

	public function testRiverDeleteUsesPermissionHook() {
		$params = [
			'action_type' => 'create',
			'subject_guid' => $this->user->guid,
			'object_guid' => $this->entity->guid,
			'return_item' => true,
		];
		$item = elgg_create_river_item($params);

		//permissions_check:delete, river
		$this->assertTrue($item->canDelete());

		$captured = null;
		$handler = function () use (&$captured) {
			$captured = func_get_args();

			return false;
		};

		elgg_register_plugin_hook_handler('permissions_check:delete', 'river', $handler);

		$this->assertFalse($item->canDelete());
		$this->assertTrue($captured[2]);
		$this->assertSame($captured[3]['item'], $item);
		$this->assertSame($captured[3]['user'], elgg_get_logged_in_user_entity());

		$this->assertFalse($item->delete());

		elgg_unregister_plugin_hook_handler('permissions_check:delete', 'river', $handler);

		$this->assertTrue($item->delete());
	}

	public function testDeleteRiverFunctionTriggersEventsPerms() {
		$params = [
			'action_type' => 'create',
			'subject_guid' => $this->user->guid,
			'object_guid' => $this->entity->guid,
		];
		$id = elgg_create_river_item($params);

		$owner = $this->entity->getOwnerEntity();
		$old_user = _elgg_services()->session->getLoggedInUser();
		_elgg_services()->session->setLoggedInUser($owner);

		$events_fired = 0;
		$handler = function () use (&$events_fired) {
			$events_fired++;
		};

		elgg_register_plugin_hook_handler('permissions_check:delete', 'river', $handler);
		elgg_register_event_handler('delete:before', 'river', $handler);
		elgg_register_event_handler('delete:after', 'river', $handler);

		elgg_delete_river(['id' => $id]);

		elgg_unregister_plugin_hook_handler('permissions_check:delete', 'river', $handler);
		elgg_unregister_event_handler('delete:before', 'river', $handler);
		elgg_unregister_event_handler('delete:after', 'river', $handler);

		$this->assertEquals($events_fired, 3);

		_elgg_services()->session->setLoggedInUser($old_user);
	}

	public function testElggCreateRiverItemMissingRequiredParam() {
		$params = [
			'action_type' => 'create',
			'subject_guid' => $this->user->guid,
			'object_guid' => $this->entity->guid,
		];

		$no_action = $params;
		unset($no_action['action_type']);
		$this->assertFalse(elgg_create_river_item($no_action));

		$no_object = $params;
		unset($no_object['object_guid']);
		$this->assertFalse(elgg_create_river_item($no_object));
	}
	
	public function testElggCreateRiverItemSubjectGuid() {
		$params = [
			'action_type' => 'create',
			'subject_guid' => $this->user->guid,
			'object_guid' => $this->entity->guid,
		];
		
		$no_subject = $params;
		unset($no_subject['subject_guid']);
		
		// subject_guid is filled by logged in user
		$this->assertNotFalse(elgg_create_river_item($no_subject));
		
		// missing subject_guid
		_elgg_services()->session->removeLoggedInUser();
		$this->assertFalse(elgg_create_river_item($no_subject));
		
		// still logged out, but now supplied subject_guid
		$this->assertNotFalse(elgg_create_river_item($params));
	}

	public function testElggCreateRiverItemViewNotExist() {
		$params = [
			'view' => 'some/view/that/non/exists',
			'action_type' => 'create',
			'subject_guid' => $this->user->guid,
			'object_guid' => $this->entity->guid,
		];

		$this->assertFalse(elgg_create_river_item($params));
	}

	public function testElggCreateRiverItemBadEntity() {
		$params = [
			'action_type' => 'create',
			'subject_guid' => $this->user->guid,
			'object_guid' => $this->entity->guid,
			'target_guid' => $this->entity->guid,
		];

		$bad_subject = $params;
		$bad_subject['subject_guid'] = -1;
		$this->assertFalse(elgg_create_river_item($bad_subject));

		$bad_object = $params;
		$bad_object['object_guid'] = -1;
		$this->assertFalse(elgg_create_river_item($bad_object));

		$bad_target = $params;
		$bad_target['target_guid'] = -1;
		$this->assertFalse(elgg_create_river_item($bad_target));
	}

	public function testElggRiverDisableEnable() {

		$this->assertTrue(_elgg_services()->events->hasHandler('disable:after', 'all', '_elgg_river_disable'));

		$user = $this->createOne('user');
		$this->entity = $this->createOne('object');

		$params = [
			'action_type' => 'create',
			'subject_guid' => $user->guid,
			'object_guid' => $this->entity->guid,
		];

		$id = elgg_create_river_item($params);

		$river = elgg_get_river(['ids' => [$id]]);

		$this->assertSame($river[0]->enabled, 'yes');

		$ia = elgg_set_ignore_access(true);
		$this->assertTrue($user->disable());
		elgg_set_ignore_access($ia);

		// should no longer be able to get the river
		$river = elgg_get_river(['ids' => [$id]]);

		$this->assertSame($river, []);

		// renabling the user should re-enable the river
		$ia = elgg_set_ignore_access(true);
		$ha = access_get_show_hidden_status();
		access_show_hidden_entities(true);
		$user->enable();
		access_show_hidden_entities($ha);
		elgg_set_ignore_access($ia);

		$river = elgg_get_river(['ids' => [$id]]);

		$this->assertSame($river[0]->enabled, 'yes');

		$user->delete();
		$this->entity->delete();
	}

	/**
	 * @group RiverEvents
	 */
	public function testCreatesRiverItemOnEntityEvent() {

		$user = $this->createUser();

		_elgg_services()->session->setLoggedInUser($user);

		elgg_register_river_event('create', 'object', 'foo');

		$object = $this->createObject([
			'subtype' => 'foo',
		]);

		$river = elgg_get_river([
			'object_guids' => $object->guid,
		]);

		$this->assertCount(1, $river);

		$item = array_shift($river);
		/* @var $item ElggRiverItem */

		$this->assertInstanceOf(ElggRiverItem::class, $item);

		$this->assertEquals('create', $item->action);
		$this->assertEquals($object->owner_guid, $item->getSubjectEntity()->guid);
		$this->assertEquals($object->guid, $item->getObjectEntity()->guid);
		$this->assertEquals($object->container_guid, $item->getTargetEntity()->guid);
		$this->assertEquals($object->guid, $item->result_id);
		$this->assertEquals($object->type, $item->result_type);
		$this->assertEquals($object->subtype, $item->result_subtype);
		$this->assertEquals($object, $item->getResult());

		elgg_unregister_river_event('create:after', 'object', 'foo');

		_elgg_services()->session->removeLoggedInUser();
	}

	/**
	 * @group RiverEvents
	 */
	public function testCreatesRiverItemOnUserEvent() {

		$user = $this->createUser([
			'subtype' => 'foo',
		]);

		_elgg_services()->session->setLoggedInUser($user);

		elgg_register_river_event('custom:after', 'user');

		elgg_trigger_after_event('custom', 'user', $user);

		$river = elgg_get_river([
			'object_guids' => $user->guid,
		]);

		$this->assertCount(1, $river);

		$item = array_shift($river);
		/* @var $item ElggRiverItem */

		$this->assertInstanceOf(ElggRiverItem::class, $item);

		$this->assertEquals('custom', $item->action);
		$this->assertEquals($user->guid, $item->getSubjectEntity()->guid);
		$this->assertEquals($user->guid, $item->getObjectEntity()->guid);
		$this->assertFalse($item->getTargetEntity());
		$this->assertEquals($user->guid, $item->result_id);
		$this->assertEquals($user->type, $item->result_type);
		$this->assertEquals($user->subtype, $item->result_subtype);

		elgg_unregister_river_event('custom:after', 'user');

		_elgg_services()->session->removeLoggedInUser();
	}

	/**
	 * @group RiverEvents
	 */
	public function testCreatesRiverItemOnCommentEvent() {

		$user = $this->createUser();

		_elgg_services()->session->setLoggedInUser($user);

		elgg_set_entity_class('object', 'foo_comment', \ElggComment::class);

		elgg_register_river_event('create', 'object', 'foo_comment');

		$object = $this->createObject();

		$comment = $this->createObject([
			'subtype' => 'foo_comment',
			'container_guid' => $object->guid,
		]);

		$river = elgg_get_river([
			'result_ids' => $comment->guid,
			'result_types' => 'object',
			'result_subtypes' => 'foo_comment',
		]);

		$this->assertCount(1, $river);

		$item = array_shift($river);
		/* @var $item ElggRiverItem */

		$this->assertInstanceOf(ElggRiverItem::class, $item);

		$this->assertEquals('foo_comment', $item->action);
		$this->assertEquals($user->guid, $item->getSubjectEntity()->guid);
		$this->assertEquals($object->guid, $item->getObjectEntity()->guid);
		$this->assertEquals($object->container_guid, $item->getTargetEntity()->guid);
		$this->assertEquals($comment->guid, $item->result_id);
		$this->assertEquals($comment->type, $item->result_type);
		$this->assertEquals($comment->subtype, $item->result_subtype);
		$this->assertEquals($comment, $item->getResult());

		elgg_unregister_river_event('create', 'object', 'foo_comment');

		_elgg_services()->session->removeLoggedInUser();
	}

	/**
	 * @group RiverEvents
	 */
	public function testCreatesRiverItemOnAnnotationEvent() {

		$user = $this->createUser();

		_elgg_services()->session->setLoggedInUser($user);

		elgg_register_river_event('create:after', 'annotation', 'poke');

		$object = $this->createObject();

		$id = $object->annotate('poke', 1);

		$annotation = elgg_get_annotation_from_id($id);

		$river = elgg_get_river([
			'result_ids' => $annotation->id,
			'result_types' => 'annotation',
		]);

		$this->assertCount(1, $river);

		$item = array_shift($river);
		/* @var $item ElggRiverItem */

		$this->assertInstanceOf(ElggRiverItem::class, $item);

		$this->assertEquals('poke', $item->action);
		$this->assertEquals($user->guid, $item->getSubjectEntity()->guid);
		$this->assertEquals($object->guid, $item->getObjectEntity()->guid);
		$this->assertEquals($object->container_guid, $item->getTargetEntity()->guid);
		$this->assertEquals($annotation->id, $item->result_id);
		$this->assertEquals('annotation', $item->result_type);
		$this->assertEquals('poke', $item->result_subtype);
		$this->assertEquals($annotation, $item->getResult());

		elgg_unregister_river_event('create:after', 'annotation', 'poke');

		_elgg_services()->session->removeLoggedInUser();
	}

	/**
	 * @group RiverEvents
	 */
	public function testCreatesRiverItemOnRelationshipEvent() {

		$user = $this->createUser();

		_elgg_services()->session->setLoggedInUser($user);

		elgg_register_river_event('create:after', 'relationship', 'connect');

		$object = $this->createObject([
			'subtype' => 'foo',
		]);

		$id = add_entity_relationship($user->guid, 'connect', $object->guid);
		$relationship = get_relationship($id);

		$river = elgg_get_river([
			'result_id' => $relationship->id,
			'result_type' => 'relationship',
		]);

		$this->assertCount(1, $river);

		$item = array_shift($river);
		/* @var $item ElggRiverItem */

		$this->assertInstanceOf(ElggRiverItem::class, $item);

		$this->assertEquals('connect', $item->action);
		$this->assertEquals($user->guid, $item->getSubjectEntity()->guid);
		$this->assertEquals($object->guid, $item->getObjectEntity()->guid);
		$this->assertEquals($object->container_guid, $item->getTargetEntity()->guid);
		$this->assertEquals($relationship->id, $item->result_id);
		$this->assertEquals('relationship', $item->result_type);
		$this->assertEquals('connect', $item->result_subtype);
		$this->assertEquals($relationship, $item->getResult());

		elgg_unregister_river_event('create:after', 'relationship', 'connection');

		_elgg_services()->session->removeLoggedInUser();
	}
}
