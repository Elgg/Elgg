<?php

namespace Elgg\Integration;

use Elgg\Values;
use ElggRiveritem;

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

	public function allowDelete(\Elgg\Hook $hook) {
		
		$hook_user = $hook->getUserParam();
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

		$this->assertInstanceOf(ElggRiverItem::class, $item);
		$this->assertTrue(elgg_delete_river(['id' => $item->id]));
	}

	public function testRiverCreationEmitsEvents() {
		$params = [
			'view' => 'river/relationship/friend/create',
			'action_type' => 'create',
			'subject_guid' => $this->user->guid,
			'object_guid' => $this->entity->guid,
			'posted' => time(),
			'return_item' => true,
		];

		$captured = [];
		$before_handler = function (\Elgg\Event $event) use (&$captured) {
			$captured['before_object'] = $event->getObject();
		};
		$after_handler = function (\Elgg\Event $event) use (&$captured) {
			$captured['after_object'] = $event->getObject();
		};

		elgg_register_event_handler('create:before', 'river', $before_handler);
		elgg_register_event_handler('create:after', 'river', $after_handler);
		$item = elgg_create_river_item($params);
		$this->assertInstanceOf(ElggRiverItem::class, $item);
		elgg_unregister_event_handler('create:before', 'river', $before_handler);
		elgg_unregister_event_handler('create:after', 'river', $after_handler);

		$this->assertSame($item, $captured['before_object']);
		$this->assertSame($item, $captured['after_object']);

		$this->assertTrue($item->delete());
	}

	public function testCanCancelRiverItemViaEvent() {
		$params = [
			'view' => 'river/relationship/friend/create',
			'action_type' => 'create',
			'subject_guid' => $this->user->guid,
			'object_guid' => $this->entity->guid,
		];

		elgg_register_event_handler('create:before', 'river', [
			Values::class,
			'getFalse'
		]);
		$id = elgg_create_river_item($params);
		elgg_unregister_event_handler('create:before', 'river', [
			Values::class,
			'getFalse'
		]);

		$this->assertFalse($id); // prevented
	}

	public function testCanCancelRiverDeleteByEvent() {
		$params = [
			'view' => 'river/relationship/friend/create',
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
			'view' => 'river/relationship/friend/create',
			'action_type' => 'create',
			'subject_guid' => $this->user->guid,
			'object_guid' => $this->entity->guid,
			'return_item' => true,
		];
		$item = elgg_create_river_item($params);

		$captured = null;
		$event_handler = function (\Elgg\Event $event) use (&$captured) {
			$captured = $event->getObject();
		};

		elgg_register_event_handler('delete:after', 'river', $event_handler);
		$item->delete();
		elgg_unregister_event_handler('delete:after', 'river', $event_handler);

		$this->assertSame($item, $captured);
	}

	public function testRiverDeleteUsesPermissionHook() {
		$params = [
			'view' => 'river/relationship/friend/create',
			'action_type' => 'create',
			'subject_guid' => $this->user->guid,
			'object_guid' => $this->entity->guid,
			'return_item' => true,
		];
		$item = elgg_create_river_item($params);

		//permissions_check:delete, river
		$this->assertTrue($item->canDelete());

		$captured = null;
		$handler = function (\Elgg\Hook $hook) use (&$captured) {
			$captured = clone $hook; // need to clone to keep original starting values

			return false;
		};

		elgg_register_plugin_hook_handler('permissions_check:delete', 'river', $handler);

		$this->assertFalse($item->canDelete());
		
		$this->assertInstanceOf(\Elgg\Hook::class, $captured);
		$this->assertTrue($captured->getValue());
		$this->assertSame($captured->getParam('item'), $item);
		$this->assertSame($captured->getUserParam(), elgg_get_logged_in_user_entity());

		$this->assertFalse($item->delete());

		elgg_unregister_plugin_hook_handler('permissions_check:delete', 'river', $handler);

		$this->assertTrue($item->delete());
	}

	public function testDeleteRiverFunctionTriggersEventsPerms() {
		$params = [
			'view' => 'river/relationship/friend/create',
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

		$this->assertEquals($events_fired, 2);

		_elgg_services()->session->setLoggedInUser($old_user);
	}

	public function testElggCreateRiverItemMissingRequiredParam() {
		$params = [
			'view' => 'river/relationship/friend/create',
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
			'view' => 'river/relationship/friend/create',
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
			'view' => 'river/relationship/foo/create',
			'action_type' => 'create',
			'subject_guid' => $this->user->guid,
			'object_guid' => $this->entity->guid,
		];

		$this->assertFalse(elgg_create_river_item($params));
	}

	public function testElggCreateRiverItemBadEntity() {
		$params = [
			'view' => 'river/relationship/friend/create',
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
}
