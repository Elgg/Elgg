<?php

namespace Elgg\Integration;

use Elgg\Exceptions\InvalidArgumentException;
use Elgg\Values;

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
		$user = $this->createUser();
		
		$entity = $this->createObject([
			'owner_guid' => $user->guid,
		]);
		
		$this->user = $user;
		$this->entity = $entity;

		_elgg_services()->session_manager->setLoggedInUser($user);
		
		// By default, only admins are allowed to delete river items
		// For the sake of this test case, we will allow the user to delete items
		elgg_register_event_handler('permissions_check:delete', 'river', [
			$this,
			'allowDelete'
		]);
	}

	public function down() {
		elgg_unregister_event_handler('permissions_check:delete', 'river', [
			$this,
			'allowDelete'
		]);
	}

	public function allowDelete(\Elgg\Event $event) {
		
		$event_user = $event->getUserParam();
		if (!$event_user) {
			return;
		}
		
		if ($this->user->guid === $event_user->guid) {
			return true;
		}
	}

	public function testCanCreateRiverItem() {
		$item = elgg_create_river_item([
			'action_type' => 'create',
			'object_guid' => $this->entity->guid,
		]);

		$this->assertInstanceOf(\ElggRiverItem::class, $item);
		$this->assertGreaterThan(0, $item->posted);
		$this->assertTrue(elgg_delete_river(['id' => $item->id]));
	}

	public function testRiverCreationEmitsEvents() {
		$params = [
			'view' => 'river/relationship/friend/create',
			'action_type' => 'create',
			'subject_guid' => $this->user->guid,
			'object_guid' => $this->entity->guid,
			'posted' => time(),
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
		$this->assertInstanceOf(\ElggRiverItem::class, $item);
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
		$item = elgg_create_river_item($params);
		elgg_unregister_event_handler('create:before', 'river', [
			Values::class,
			'getFalse'
		]);

		$this->assertNull($item); // prevented
	}

	public function testCanCancelRiverDeleteByEvent() {
		$params = [
			'view' => 'river/relationship/friend/create',
			'action_type' => 'create',
			'subject_guid' => $this->user->guid,
			'object_guid' => $this->entity->guid,
		];
		$item = elgg_create_river_item($params);
		$this->assertInstanceOf(\ElggRiverItem::class, $item);

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
		$this->assertInstanceOf(\ElggRiverItem::class, $item);

		$captured = null;
		$event_handler = function (\Elgg\Event $event) use (&$captured) {
			$captured = $event->getObject();
		};

		elgg_register_event_handler('delete:after', 'river', $event_handler);
		$item->delete();
		elgg_unregister_event_handler('delete:after', 'river', $event_handler);

		$this->assertSame($item, $captured);
	}

	public function testRiverDeleteUsesPermissionEvent() {
		$params = [
			'view' => 'river/relationship/friend/create',
			'action_type' => 'create',
			'subject_guid' => $this->user->guid,
			'object_guid' => $this->entity->guid,
		];
		$item = elgg_create_river_item($params);
		$this->assertInstanceOf(\ElggRiverItem::class, $item);

		//permissions_check:delete, river
		$this->assertTrue($item->canDelete());

		$captured = null;
		$handler = function (\Elgg\Event $event) use (&$captured) {
			$captured = clone $event; // need to clone to keep original starting values

			return false;
		};

		elgg_register_event_handler('permissions_check:delete', 'river', $handler);

		$this->assertFalse($item->canDelete());
		
		$this->assertInstanceOf(\Elgg\Event::class, $captured);
		$this->assertTrue($captured->getValue());
		$this->assertSame($captured->getParam('item'), $item);
		$this->assertSame($captured->getUserParam(), elgg_get_logged_in_user_entity());

		$this->assertFalse($item->delete());

		elgg_unregister_event_handler('permissions_check:delete', 'river', $handler);

		$this->assertTrue($item->delete());
	}

	public function testDeleteRiverFunctionTriggersEventsPerms() {
		$params = [
			'view' => 'river/relationship/friend/create',
			'action_type' => 'create',
			'subject_guid' => $this->user->guid,
			'object_guid' => $this->entity->guid,
		];
		$item = elgg_create_river_item($params);
		$this->assertNotNull($item);

		$owner = $this->entity->getOwnerEntity();
		$old_user = _elgg_services()->session_manager->getLoggedInUser();
		_elgg_services()->session_manager->setLoggedInUser($owner);

		$events_fired = 0;
		$handler = function () use (&$events_fired) {
			$events_fired++;
		};

		elgg_register_event_handler('permissions_check:delete', 'river', $handler);
		elgg_register_event_handler('delete:before', 'river', $handler);
		elgg_register_event_handler('delete:after', 'river', $handler);

		elgg_delete_river(['id' => $item->id]);

		elgg_unregister_event_handler('permissions_check:delete', 'river', $handler);
		elgg_unregister_event_handler('delete:before', 'river', $handler);
		elgg_unregister_event_handler('delete:after', 'river', $handler);

		$this->assertEquals($events_fired, 2);

		_elgg_services()->session_manager->setLoggedInUser($old_user);
	}

	public function testDeleteRiverThrowsException() {
		$this->expectException(InvalidArgumentException::class);
		elgg_delete_river(['invalid' => false]);
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
		$this->assertNull(elgg_create_river_item($no_action));

		$no_object = $params;
		unset($no_object['object_guid']);
		$this->assertNull(elgg_create_river_item($no_object));
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
		$this->assertNotNull(elgg_create_river_item($no_subject));
		
		// missing subject_guid
		_elgg_services()->session_manager->removeLoggedInUser();
		$this->assertNull(elgg_create_river_item($no_subject));
		
		// still logged out, but now supplied subject_guid
		$this->assertNotNull(elgg_create_river_item($params));
	}

	public function testElggCreateRiverItemViewNotExist() {
		$params = [
			'view' => 'river/relationship/foo/create',
			'action_type' => 'create',
			'subject_guid' => $this->user->guid,
			'object_guid' => $this->entity->guid,
		];

		$this->assertNull(elgg_create_river_item($params));
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
		$this->assertNull(elgg_create_river_item($bad_subject));

		$bad_object = $params;
		$bad_object['object_guid'] = -1;
		$this->assertNull(elgg_create_river_item($bad_object));

		$bad_target = $params;
		$bad_target['target_guid'] = -1;
		$this->assertNull(elgg_create_river_item($bad_target));
	}
}
