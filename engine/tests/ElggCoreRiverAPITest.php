<?php
/**
 * Elgg Test river api
 *
 * @package Elgg
 * @subpackage Test
 */
class ElggCoreRiverAPITest extends \ElggCoreUnitTest {

	public function testCanCreateRiverItem() {
		$entity = $this->getSomeEntity();
		$params = array(
			'view' => 'river/relationship/friend/create',
			'action_type' => 'create',
			'subject_guid' => $entity->guid,
			'object_guid' => $entity->guid,
		);

		$id = elgg_create_river_item($params);
		$this->assertTrue(is_int($id));
		$this->assertTrue(_elgg_delete_river(array('id' => $id)));

		$params['return_item'] = true;
		$item = elgg_create_river_item($params);

		$this->assertIsA($item, ElggRiveritem::class);
		$this->assertTrue(_elgg_delete_river(array('id' => $item->id)));
	}

	public function testRiverCreationEmitsHookAndEvent() {
		$entity = $this->getSomeEntity();
		$params = array(
			'view' => 'river/relationship/friend/create',
			'action_type' => 'create',
			'subject_guid' => $entity->guid,
			'object_guid' => $entity->guid,
			'posted' => time(),
			'return_item' => true,
		);

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

		$expected_values = array(
			'type' => $entity->getType(),
			'subtype' => $entity->getSubtype(),
			'action_type' => $params['action_type'],
			'access_id' => $entity->access_id,
			'view' => $params['view'],
			'subject_guid' => $params['subject_guid'],
			'object_guid' => $params['object_guid'],
			'target_guid' => 0,
			'annotation_id' => 0,
			'posted' => $params['posted'],
		);
		foreach ($expected_values as $key => $value) {
			$this->assertEqual($captured['hook_value'][$key], $value);
		}
		$this->assertSame($item, $captured['event_object']);

		$this->assertTrue($item->delete());
	}

	public function testCanCancelRiverItemViaHook() {
		$entity = $this->getSomeEntity();
		$params = array(
			'view' => 'river/relationship/friend/create',
			'action_type' => 'create',
			'subject_guid' => $entity->guid,
			'object_guid' => $entity->guid,
		);

		elgg_register_plugin_hook_handler('creating', 'river', [Elgg\Values::class, 'getFalse']);
		$id = elgg_create_river_item($params);
		elgg_unregister_plugin_hook_handler('creating', 'river', [Elgg\Values::class, 'getFalse']);

		$this->assertTrue($id === true); // prevented
	}

	public function testCanCancelRiverDeleteByEvent() {
		$entity = $this->getSomeEntity();
		$params = array(
			'view' => 'river/relationship/friend/create',
			'action_type' => 'create',
			'subject_guid' => $entity->guid,
			'object_guid' => $entity->guid,
			'return_item' => true,
		);
		$item = elgg_create_river_item($params);

		elgg_register_event_handler('delete:before', 'river', [Elgg\Values::class, 'getFalse']);
		$this->assertFalse($item->delete());
		elgg_unregister_event_handler('delete:before', 'river', [Elgg\Values::class, 'getFalse']);

		$items = elgg_get_river(['id' => $item->id]);
		$this->assertEqual(count($items), 1);

		$this->assertTrue($item->delete());
	}

	public function testRiverDeleteFiresAfterEvent() {
		// [delete:after, river]

		$entity = $this->getSomeEntity();
		$params = array(
			'view' => 'river/relationship/friend/create',
			'action_type' => 'create',
			'subject_guid' => $entity->guid,
			'object_guid' => $entity->guid,
			'return_item' => true,
		);
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
		$entity = $this->getSomeEntity();
		$params = array(
			'view' => 'river/relationship/friend/create',
			'action_type' => 'create',
			'subject_guid' => $entity->guid,
			'object_guid' => $entity->guid,
			'return_item' => true,
		);
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

	public function testDeprecatedDeleteRiverFunctionBypassesEventsPerms() {
		$entity = $this->getSomeEntity();
		$params = array(
			'view' => 'river/relationship/friend/create',
			'action_type' => 'create',
			'subject_guid' => $entity->guid,
			'object_guid' => $entity->guid,
		);
		$id = elgg_create_river_item($params);

		$fired = false;
		$handler = function () use (&$fired) {
			$fired = true;
		};

		elgg_register_plugin_hook_handler('permissions_check:delete', 'river', $handler);
		elgg_register_event_handler('delete:before', 'river', $handler);
		elgg_register_event_handler('delete:after', 'river', $handler);

		_elgg_delete_river(['id' => $id]);

		elgg_unregister_plugin_hook_handler('permissions_check:delete', 'river', $handler);
		elgg_unregister_event_handler('delete:before', 'river', $handler);
		elgg_unregister_event_handler('delete:after', 'river', $handler);

		$this->assertFalse($fired);
	}

	public function testElggCreateRiverItemMissingRequiredParam() {
		$entity = $this->getSomeEntity();
		$params = array(
			'view' => 'river/relationship/friend/create',
			'action_type' => 'create',
			'subject_guid' => $entity->guid,
			'object_guid' => $entity->guid,
		);

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
		$entity = $this->getSomeEntity();
		$params = array(
			'view' => 'river/relationship/foo/create',
			'action_type' => 'create',
			'subject_guid' => $entity->guid,
			'object_guid' => $entity->guid,
		);

		$this->assertFalse(elgg_create_river_item($params));
	}

	public function testElggCreateRiverItemBadEntity() {
		$entity = $this->getSomeEntity();
		$params = array(
			'view' => 'river/relationship/friend/create',
			'action_type' => 'create',
			'subject_guid' => $entity->guid,
			'object_guid' => $entity->guid,
			'target_guid' => $entity->guid,
		);

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
		$types = array('object');
		$subtypes = array('blog');
		$result = _elgg_get_river_type_subtype_where_sql('rv', $types, $subtypes, null);
		$this->assertIdentical($result, "((rv.type = 'object') AND ((rv.subtype = 'blog')))");

		$types = array('object');
		$subtypes = array('blog', 'file');
		$result = _elgg_get_river_type_subtype_where_sql('rv', $types, $subtypes, null);
		$this->assertIdentical($result, "((rv.type = 'object') AND ((rv.subtype = 'blog') OR (rv.subtype = 'file')))");
	}
	
	public function testElggRiverDisableEnable() {
		$user = new \ElggUser();
		$user->save();
		
		$entity = new \ElggObject();
		$entity->save();
		
		$params = array(
			'view' => 'river/relationship/friend/create',
			'action_type' => 'create',
			'subject_guid' => $user->guid,
			'object_guid' => $entity->guid,
		);

		$id = elgg_create_river_item($params);

		$river = elgg_get_river(array('ids' => array($id)));

		$this->assertIdentical($river[0]->enabled, 'yes');
		
		$user->disable();
		
		// should no longer be able to get the river
		$river = elgg_get_river(array('ids' => array($id)));
		
		$this->assertIdentical($river, array());
		
		// renabling the user should re-enable the river
		access_show_hidden_entities(true);
		$user->enable();
		access_show_hidden_entities(false);
		
		$river = elgg_get_river(array('ids' => array($id)));
		
		$this->assertIdentical($river[0]->enabled, 'yes');
		
		$user->delete();
		$entity->delete();
	}

	/**
	 * @return ElggEntity
	 */
	protected function getSomeEntity() {
		$entity = elgg_get_entities(array('limit' => 1));
		return $entity[0];
	}
}
