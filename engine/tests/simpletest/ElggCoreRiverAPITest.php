<?php

use Elgg\Values;

/**
 * Elgg Test river api
 *
 * @package    Elgg
 * @subpackage Test
 */
class ElggCoreRiverAPITest extends \ElggCoreUnitTest {

	/**
	 * @var ElggEntity
	 */
	protected $entity;

	/**
	 * @var ElggUser
	 */
	protected $user;

	public function up() {
		$user = $this->createUser();

		$entity = $this->createObject([
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

		$this->user->delete();

		elgg_unregister_plugin_hook_handler('permissions_check:delete', 'river', [
			$this,
			'allowDelete'
		]);
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
			'view' => 'river/relationship/friend/create',
			'action_type' => 'create',
			'subject_guid' => $this->entity->guid,
			'object_guid' => $this->entity->guid,
		];

		$id = elgg_create_river_item($params);
		$this->assertTrue(is_int($id));
		$this->assertTrue(elgg_delete_river(['id' => $id]));

		$params['return_item'] = true;
		$item = elgg_create_river_item($params);

		$this->assertIsA($item, ElggRiveritem::class);
		$this->assertTrue(elgg_delete_river(['id' => $item->id]));
	}

	public function testRiverCreationEmitsHookAndEvent() {
		$params = [
			'view' => 'river/relationship/friend/create',
			'action_type' => 'create',
			'subject_guid' => $this->entity->guid,
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
		elgg_unregister_plugin_hook_handler('creating', 'river', $hook_handler);
		elgg_unregister_event_handler('created', 'river', $event_handler);

		$expected_values = [
			'type' => $this->entity->getType(),
			'subtype' => $this->entity->getSubtype(),
			'action_type' => $params['action_type'],
			'access_id' => $this->entity->access_id,
			'view' => $params['view'],
			'subject_guid' => $params['subject_guid'],
			'object_guid' => $params['object_guid'],
			'target_guid' => 0,
			'annotation_id' => 0,
			'posted' => $params['posted'],
		];
		foreach ($expected_values as $key => $value) {
			$this->assertEqual($captured['hook_value'][$key], $value);
		}
		$this->assertSame($item, $captured['event_object']);

		$this->assertTrue($item->delete());
	}

	public function testCanCancelRiverItemViaHook() {
		$params = [
			'view' => 'river/relationship/friend/create',
			'action_type' => 'create',
			'subject_guid' => $this->entity->guid,
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

		$this->assertTrue($id === true); // prevented
	}

	public function testCanCancelRiverDeleteByEvent() {
		$params = [
			'view' => 'river/relationship/friend/create',
			'action_type' => 'create',
			'subject_guid' => $this->entity->guid,
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
		$this->assertEqual(count($items), 1);

		$this->assertTrue($item->delete());
	}

	public function testRiverDeleteFiresAfterEvent() {
		// [delete:after, river]

		$params = [
			'view' => 'river/relationship/friend/create',
			'action_type' => 'create',
			'subject_guid' => $this->entity->guid,
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
			'view' => 'river/relationship/friend/create',
			'action_type' => 'create',
			'subject_guid' => $this->entity->guid,
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
			'view' => 'river/relationship/friend/create',
			'action_type' => 'create',
			'subject_guid' => $this->entity->guid,
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

		$this->assertEqual($events_fired, 3);

		_elgg_services()->session->setLoggedInUser($old_user);
	}

	public function testElggCreateRiverItemMissingRequiredParam() {
		$params = [
			'view' => 'river/relationship/friend/create',
			'action_type' => 'create',
			'subject_guid' => $this->entity->guid,
			'object_guid' => $this->entity->guid,
		];

		$no_view = $params;
		unset($no_view['view']);
		$this->assertFalse(elgg_create_river_item($no_view));

		$no_action = $params;
		unset($no_action['action_type']);
		$this->assertFalse(elgg_create_river_item($no_action));

		$no_subject = $params;
		unset($no_subject['subject_guid']);
		$this->assertFalse(elgg_create_river_item($no_subject));

		$no_object = $params;
		unset($no_object['object_guid']);
		$this->assertFalse(elgg_create_river_item($no_object));
	}

	public function testElggCreateRiverItemViewNotExist() {
		$params = [
			'view' => 'river/relationship/foo/create',
			'action_type' => 'create',
			'subject_guid' => $this->entity->guid,
			'object_guid' => $this->entity->guid,
		];

		$this->assertFalse(elgg_create_river_item($params));
	}

	public function testElggCreateRiverItemBadEntity() {
		$params = [
			'view' => 'river/relationship/friend/create',
			'action_type' => 'create',
			'subject_guid' => $this->entity->guid,
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

	public function testElggTypeSubtypeWhereSQL() {
		$types = ['object'];
		$subtypes = ['blog'];
		$result = _elgg_get_river_type_subtype_where_sql('rv', $types, $subtypes, null);
		$this->assertIdentical($result, "((rv.type = 'object') AND ((rv.subtype = 'blog')))");

		$types = ['object'];
		$subtypes = [
			'blog',
			'file'
		];
		$result = _elgg_get_river_type_subtype_where_sql('rv', $types, $subtypes, null);
		$this->assertIdentical($result, "((rv.type = 'object') AND ((rv.subtype = 'blog') OR (rv.subtype = 'file')))");
	}

	public function testElggRiverDisableEnable() {

		$this->assertTrue(_elgg_services()->hooks->getEvents()->hasHandler('disable:after', 'all', '_elgg_river_disable'));

		$user = $this->createUser();
		$this->entity = $this->createObject();

		$params = [
			'view' => 'river/relationship/friend/create',
			'action_type' => 'create',
			'subject_guid' => $user->guid,
			'object_guid' => $this->entity->guid,
		];

		$id = elgg_create_river_item($params);

		$river = elgg_get_river(['ids' => [$id]]);

		$this->assertIdentical($river[0]->enabled, 'yes');

		$ia = elgg_set_ignore_access(true);
		$this->assertTrue($user->disable());
		elgg_set_ignore_access($ia);

		// should no longer be able to get the river
		$river = elgg_get_river(['ids' => [$id]]);

		$this->assertIdentical($river, []);

		// renabling the user should re-enable the river
		$ia = elgg_set_ignore_access(true);
		$ha = access_get_show_hidden_status();
		access_show_hidden_entities(true);
		$user->enable();
		access_show_hidden_entities($ha);
		elgg_set_ignore_access($ia);

		$river = elgg_get_river(['ids' => [$id]]);

		$this->assertIdentical($river[0]->enabled, 'yes');

		$user->delete();
		$this->entity->delete();
	}
}
