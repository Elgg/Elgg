<?php
/**
 * Elgg Test river api
 *
 * @package Elgg
 * @subpackage Test
 */
class ElggCoreRiverAPITest extends ElggCoreUnitTest {

	public function testElggCreateRiverItemWorks() {
		$entity = elgg_get_entities(array('limit' => 1));
		$entity = $entity[0];
		$params = array(
			'view' => 'river/relationship/friend/create',
			'action_type' => 'create',
			'subject_guid' => $entity->guid,
			'object_guid' => $entity->guid,
		);

		$id = elgg_create_river_item($params);
		$this->assertTrue(is_int($id));

		$this->assertTrue(elgg_delete_river(array('id' => $id)));
	}

	public function testElggCreateRiverItemMissingRequiredParam() {
		$entity = elgg_get_entities(array('limit' => 1));
		$entity = $entity[0];
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
		$entity = elgg_get_entities(array('limit' => 1));
		$entity = $entity[0];
		$params = array(
			'view' => 'river/relationship/foo/create',
			'action_type' => 'create',
			'subject_guid' => $entity->guid,
			'object_guid' => $entity->guid,
		);

		$this->assertFalse(elgg_create_river_item($params));
	}

	public function testElggCreateRiverItemBadEntity() {
		$entity = elgg_get_entities(array('limit' => 1));
		$entity = $entity[0];
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
		$user = new ElggUser();
		$user->save();

		$entity = new ElggObject();
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
}
