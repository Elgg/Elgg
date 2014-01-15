<?php
/**
 * Test ElggEntity
 *
 */
class ElggCoreEntityTest extends ElggCoreUnitTest {

	/**
	 * @var ElggEntity
	 */
	protected $entity;

	/**
	 * Called before each test method.
	 */
	public function setUp() {
		// use ElggObject since ElggEntity is an abstract class
		$this->entity = new ElggObject();
		$this->entity->subtype = 'elgg_entity_test_subtype';
		$this->entity->save();
	}

	/**
	 * Called after each test method.
	 */
	public function tearDown() {
		if ($this->entity) {
			$this->entity->delete();
		}
	}

	public function __destruct() {
		parent::__destruct();
		remove_subtype('object', 'elgg_entity_test_subtype');
	}

	public function testSubtypePropertyReads() {
		$this->assertTrue($this->entity->save());
		$guid = $this->entity->guid;

		$subtype_prop = $this->entity->subtype;
		$this->assertIsA($subtype_prop, 'int');
		$this->assertEqual($subtype_prop, get_subtype_id('object', 'elgg_entity_test_subtype'));

		_elgg_invalidate_cache_for_entity($guid);
		$this->entity = null;
		$this->entity = get_entity($guid);

		$subtype_prop = $this->entity->subtype;
		$this->assertIsA($subtype_prop, 'int');
		$this->assertEqual($subtype_prop, get_subtype_id('object', 'elgg_entity_test_subtype'));
	}

	public function testGetSubtype() {
		$guid = $this->entity->guid;

		$this->assertEqual($this->entity->getSubtype(), 'elgg_entity_test_subtype');

		_elgg_invalidate_cache_for_entity($guid);
		$this->entity = null;
		$this->entity = get_entity($guid);

		$this->assertEqual($this->entity->getSubtype(), 'elgg_entity_test_subtype');
	}

	public function testSubtypeAddRemove() {
		$test_subtype = 'test_1389988642';
		$object = new ElggObject();
		$object->subtype = $test_subtype;
		$object->save();

		$this->assertTrue(is_numeric(get_subtype_id('object', $test_subtype)));

		$object->delete();
		remove_subtype('object', $test_subtype);

		$this->assertFalse(get_subtype_id('object', $test_subtype));
	}

	public function testElggEnityGetAndSetAnnotations() {
		$this->assertIdentical($this->entity->getAnnotations(array('annotation_name' => 'non_existent')), array());

		// save entity and check for annotation
		$this->entity->annotate('non_existent', 'foo');
		$annotations = $this->entity->getAnnotations(array('annotation_name' => 'non_existent'));
		$this->assertIsA($annotations[0], 'ElggAnnotation');
		$this->assertIdentical($annotations[0]->name, 'non_existent');
		$this->assertEqual($this->entity->countAnnotations('non_existent'), 1);

		// @todo belongs in Annotations API test class
		$this->assertIdentical($annotations, elgg_get_annotations(array('guid' => $this->entity->getGUID())));
		$this->assertIdentical($annotations, elgg_get_annotations(array('guid' => $this->entity->getGUID(), 'type' => 'object')));
		$this->assertIdentical(false, elgg_get_annotations(array('guid' => $this->entity->getGUID(), 'type' => 'object', 'subtype' => 'fail')));

		//  clear annotation
		$this->assertTrue($this->entity->deleteAnnotations());
		$this->assertEqual($this->entity->countAnnotations('non_existent'), 0);

		// @todo belongs in Annotations API test class
		$this->assertIdentical(array(), elgg_get_annotations(array('guid' => $this->entity->getGUID())));
		$this->assertIdentical(array(), elgg_get_annotations(array('guid' => $this->entity->getGUID(), 'type' => 'object')));
	}


	public function testElggEntitySaveAndDelete() {
		// check attributes populated during create()
		$time_minimum = time() - 5;
		$this->assertTrue($this->entity->time_created > $time_minimum);
		$this->assertTrue($this->entity->time_updated > $time_minimum);
		$this->assertEqual($this->entity->site_guid, elgg_get_site_entity()->guid);
		$this->assertEqual($this->entity->container_guid, elgg_get_logged_in_user_guid());
	}

	public function testElggEntityDisableAndEnable() {
		global $CONFIG;

		// add annotations and metadata to check if they're disabled.
		$annotation_id = create_annotation($this->entity->guid, 'test_annotation_' . rand(), 'test_value_' . rand());
		$metadata_id = create_metadata($this->entity->guid, 'test_metadata_' . rand(), 'test_value_' . rand());

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
		global $CONFIG;

		$obj1 = new ElggObject();
		$obj1->container_guid = $this->entity->getGUID();
		$obj1->save();
		$obj2 = new ElggObject();
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

	public function testElggEntityMetadata() {
		// let's delete a non-existent metadata
		$this->assertFalse($this->entity->deleteMetadata('important'));

		// let's add the metadata
		$this->entity->important = 'indeed!';
		$this->assertIdentical('indeed!', $this->entity->important);
		$this->entity->less_important = 'true, too!';
		$this->assertIdentical('true, too!', $this->entity->less_important);

		// test deleting incorrectly
		// @link https://github.com/elgg/elgg/issues/2273
		$this->assertNull($this->entity->deleteMetadata('impotent'));
		$this->assertEqual($this->entity->important, 'indeed!');

		// get rid of one metadata
		$this->assertEqual($this->entity->important, 'indeed!');
		$this->assertTrue($this->entity->deleteMetadata('important'));
		$this->assertNull($this->entity->important);

		// get rid of all metadata
		$this->assertTrue($this->entity->deleteMetadata());
		$this->assertNull($this->entity->less_important);
	}

	public function testElggEntityMultipleMetadata() {
		foreach (array($this->entity, new ElggObject()) as $obj) {
			$md = array('brett', 'bryan', 'brad');
			$name = 'test_md_' . rand();

			$obj->$name = $md;

			$this->assertEqual($md, $obj->$name);
		}
	}

	public function testElggEntitySingleElementArrayMetadata() {
		foreach (array($this->entity, new ElggObject()) as $obj) {
			$md = array('test');
			$name = 'test_md_' . rand();

			$obj->$name = $md;

			$this->assertEqual($md[0], $obj->$name);
		}
	}

	public function testElggEntityAppendMetadata() {
		foreach (array($this->entity, new ElggObject()) as $obj) {
			$md = 'test';
			$name = 'test_md_' . rand();

			$obj->$name = $md;
			$obj->setMetadata($name, 'test2', '', true);

			$this->assertEqual(array('test', 'test2'), $obj->$name);
		}
	}

	public function testElggEntitySingleElementArrayAppendMetadata() {
		foreach (array($this->entity, new ElggObject()) as $obj) {
			$md = 'test';
			$name = 'test_md_' . rand();

			$obj->$name = $md;
			$obj->setMetadata($name, array('test2'), '', true);

			$this->assertEqual(array('test', 'test2'), $obj->$name);
		}
	}

	public function testElggEntityArrayAppendMetadata() {
		foreach (array($this->entity, new ElggObject()) as $obj) {
			$md = array('brett', 'bryan', 'brad');
			$md2 = array('test1', 'test2', 'test3');
			$name = 'test_md_' . rand();

			$obj->$name = $md;
			$obj->setMetadata($name, $md2, '', true);

			$this->assertEqual(array_merge($md, $md2), $obj->$name);
		}
	}
}
