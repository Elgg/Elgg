<?php
/**
 * Elgg Test helper functions
 *
 *
 * @package Elgg
 * @subpackage Test
 */
class ElggCoreHelpersTest extends ElggCoreUnitTest {

	/**
	 * Called before each test object.
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Called before each test method.
	 */
	public function setUp() {

	}

	/**
	 * Called after each test method.
	 */
	public function tearDown() {
		// do not allow SimpleTest to interpret Elgg notices as exceptions
		$this->swallowErrors();

		global $CONFIG;
		unset($CONFIG->externals);
		unset($CONFIG->externals_map);
	}

	/**
	 * Called after each test object.
	 */
	public function __destruct() {
		// all __destruct() code should go above here
		parent::__destruct();
	}

	/**
	 * Test elgg_instanceof()
	 */
	public function testElggInstanceOf() {
		$entity = new ElggObject();
		$entity->subtype = 'test_subtype';
		$entity->save();

		$this->assertTrue(elgg_instanceof($entity));
		$this->assertTrue(elgg_instanceof($entity, 'object'));
		$this->assertTrue(elgg_instanceof($entity, 'object', 'test_subtype'));

		$this->assertFalse(elgg_instanceof($entity, 'object', 'invalid_subtype'));
		$this->assertFalse(elgg_instanceof($entity, 'user', 'test_subtype'));

		$entity->delete();

		$bad_entity = FALSE;
		$this->assertFalse(elgg_instanceof($bad_entity));
		$this->assertFalse(elgg_instanceof($bad_entity, 'object'));
		$this->assertFalse(elgg_instanceof($bad_entity, 'object', 'test_subtype'));

		remove_subtype('object', 'test_subtype');
	}

	/**
	 * Test elgg_normalize_url()
	 */
	public function testElggNormalizeURL() {
		$conversions = array(
			'http://example.com' => 'http://example.com',
			'https://example.com' => 'https://example.com',
			'http://example-time.com' => 'http://example-time.com',

			'//example.com' => '//example.com',
			'ftp://example.com/file' => 'ftp://example.com/file',
			'mailto:brett@elgg.org' => 'mailto:brett@elgg.org',
			'javascript:alert("test")' => 'javascript:alert("test")',
			'app://endpoint' => 'app://endpoint',

			'example.com' => 'http://example.com',
			'example.com/subpage' => 'http://example.com/subpage',

			'page/handler' =>                	elgg_get_site_url() . 'page/handler',
			'page/handler?p=v&p2=v2' =>      	elgg_get_site_url() . 'page/handler?p=v&p2=v2',
			'mod/plugin/file.php' =>            elgg_get_site_url() . 'mod/plugin/file.php',
			'mod/plugin/file.php?p=v&p2=v2' =>  elgg_get_site_url() . 'mod/plugin/file.php?p=v&p2=v2',
			'rootfile.php' =>                   elgg_get_site_url() . 'rootfile.php',
			'rootfile.php?p=v&p2=v2' =>         elgg_get_site_url() . 'rootfile.php?p=v&p2=v2',

			'/page/handler' =>               	elgg_get_site_url() . 'page/handler',
			'/page/handler?p=v&p2=v2' =>     	elgg_get_site_url() . 'page/handler?p=v&p2=v2',
			'/mod/plugin/file.php' =>           elgg_get_site_url() . 'mod/plugin/file.php',
			'/mod/plugin/file.php?p=v&p2=v2' => elgg_get_site_url() . 'mod/plugin/file.php?p=v&p2=v2',
			'/rootfile.php' =>                  elgg_get_site_url() . 'rootfile.php',
			'/rootfile.php?p=v&p2=v2' =>        elgg_get_site_url() . 'rootfile.php?p=v&p2=v2',
		);

		foreach ($conversions as $input => $output) {
			$this->assertIdentical($output, elgg_normalize_url($input));
		}
	}


	/**
	 * Test elgg_register_js()
	 */
	public function testElggRegisterJS() {
		global $CONFIG;

		// specify name
		$result = elgg_register_js('key', 'http://test1.com', 'footer');
		$this->assertTrue($result);
		$this->assertTrue(isset($CONFIG->externals_map['js']['key']));

		$item = $CONFIG->externals_map['js']['key'];
		$this->assertTrue($CONFIG->externals['js']->contains($item));

		$priority = $CONFIG->externals['js']->getPriority($item);
		$this->assertTrue($priority !== false);

		$item = $CONFIG->externals['js']->getElement($priority);
		$this->assertIdentical('http://test1.com', $item->url);

		// send a bad url
		$result = elgg_register_js('bad', null);
		$this->assertFalse($result);
	}

	/**
	 * Test elgg_register_css()
	 */
	public function testElggRegisterCSS() {
		global $CONFIG;
		
		// specify name
		$result = elgg_register_css('key', 'http://test1.com');
		$this->assertTrue($result);
		$this->assertTrue(isset($CONFIG->externals_map['css']['key']));

		$item = $CONFIG->externals_map['css']['key'];
		$this->assertTrue($CONFIG->externals['css']->contains($item));

		$priority = $CONFIG->externals['css']->getPriority($item);
		$this->assertTrue($priority !== false);

		$item = $CONFIG->externals['css']->getElement($priority);
		$this->assertIdentical('http://test1.com', $item->url);
	}

	/**
	 * Test elgg_unregister_js()
	 */
	public function testElggUnregisterJS() {
		global $CONFIG;

		$base = trim(elgg_get_site_url(), "/");

		$urls = array('id1' => "$base/urla", 'id2' => "$base/urlb", 'id3' => "$base/urlc");
		
		foreach ($urls as $id => $url) {
			elgg_register_js($id, $url);
		}

		$result = elgg_unregister_js('id1');
		$this->assertTrue($result);

		$js = $CONFIG->externals['js'];
		$elements = $js->getElements();
		$this->assertFalse(isset($CONFIG->externals_map['js']['id1']));
		
		foreach ($elements as $element) {
			if (isset($element->name)) {
				$this->assertFalse($element->name == 'id1');
			}
		}

		$result = elgg_unregister_js('id1');
		$this->assertFalse($result);

		$result = elgg_unregister_js('', 'does_not_exist');
		$this->assertFalse($result);

		$result = elgg_unregister_js('id2');
		$elements = $js->getElements();

		$this->assertFalse(isset($CONFIG->externals_map['js']['id2']));
		foreach ($elements as $element) {
			if (isset($element->name)) {
				$this->assertFalse($element->name == 'id2');
			}
		}

		$this->assertTrue(isset($CONFIG->externals_map['js']['id3']));

		$priority = $CONFIG->externals['js']->getPriority($CONFIG->externals_map['js']['id3']);
		$this->assertTrue($priority !== false);

		$item = $CONFIG->externals['js']->getElement($priority);
		$this->assertIdentical($urls['id3'], $item->url);
	}

	/**
	 * Test elgg_load_js()
	 */
	public function testElggLoadJS() {
		global $CONFIG;

		// load before register
		elgg_load_js('key');
		$result = elgg_register_js('key', 'http://test1.com', 'footer');
		$this->assertTrue($result);
		
		$js_urls = elgg_get_loaded_js('footer');
		$this->assertIdentical(array(500 => 'http://test1.com'), $js_urls);
	}

	/**
	 * Test elgg_get_loaded_js()
	 */
	public function testElggGetJS() {
		global $CONFIG;

		$base = trim(elgg_get_site_url(), "/");

		$urls = array(
			'id1' => "$base/urla",
			'id2' => "$base/urlb",
			'id3' => "$base/urlc"
		);
		
		foreach ($urls as $id => $url) {
			elgg_register_js($id, $url);
			elgg_load_js($id);
		}

		$js_urls = elgg_get_loaded_js('head');

		$this->assertIdentical($js_urls[500], $urls['id1']);
		$this->assertIdentical($js_urls[501], $urls['id2']);
		$this->assertIdentical($js_urls[502], $urls['id3']);

		$js_urls = elgg_get_loaded_js('footer');
		$this->assertIdentical(array(), $js_urls);
	}

	// test ElggPriorityList
	public function testElggPriorityListAdd() {
		$pl = new ElggPriorityList();
		$elements = array(
			'Test value',
			'Test value 2',
			'Test value 3'
		);

		shuffle($elements);

		foreach ($elements as $element) {
			$this->assertTrue($pl->add($element) !== false);
		}

		$test_elements = $pl->getElements();

		$this->assertTrue(is_array($test_elements));

		foreach ($test_elements as $i => $element) {
			// should be in the array
			$this->assertTrue(in_array($element, $elements));

			// should be the only element, so priority 0
			$this->assertEqual($i, array_search($element, $elements));
		}
	}

	public function testElggPriorityListAddWithPriority() {
		$pl = new ElggPriorityList();

		$elements = array(
			10 => 'Test Element 10',
			5 => 'Test Element 5',
			0 => 'Test Element 0',
			100 => 'Test Element 100',
			-1 => 'Test Element -1',
			-5 => 'Test Element -5'
		);

		foreach ($elements as $priority => $element) {
			$pl->add($element, $priority);
		}

		$test_elements = $pl->getElements();

		// should be sorted by priority
		$elements_sorted = array(
			-5 => 'Test Element -5',
			-1 => 'Test Element -1',
			0 => 'Test Element 0',
			5 => 'Test Element 5',
			10 => 'Test Element 10',
			100 => 'Test Element 100',
		);

		$this->assertIdentical($elements_sorted, $test_elements);

		foreach ($test_elements as $priority => $element) {
			$this->assertIdentical($elements[$priority], $element);
		}
	}

	public function testElggPriorityListGetNextPriority() {
		$pl = new ElggPriorityList();

		$elements = array(
			2 => 'Test Element',
			0 => 'Test Element 2',
			-2 => 'Test Element 3',
		);

		foreach ($elements as $priority => $element) {
			$pl->add($element, $priority);
		}

		// we're not specifying a priority so it should be the next consecutive to 0.
		$this->assertEqual(1, $pl->getNextPriority());

		// add another one at priority 1
		$pl->add('Test Element 1');

		// next consecutive to 0 is now 3.
		$this->assertEqual(3, $pl->getNextPriority());
	}

	public function testElggPriorityListRemove() {
		$pl = new ElggPriorityList();

		$elements = array();
		for ($i=0; $i<3; $i++) {
			$element = new stdClass();
			$element->name = "Test Element $i";
			$element->someAttribute = rand(0, 9999);
			$elements[] = $element;
			$pl->add($element);
		}

		$pl->remove($elements[1]);

		$test_elements = $pl->getElements();

		// make sure it's gone.
		$this->assertEqual(2, count($test_elements));
		$this->assertIdentical($elements[0], $test_elements[0]);
		$this->assertIdentical($elements[2], $test_elements[2]);
	}

	public function testElggPriorityListMove() {
		$pl = new ElggPriorityList();

		$elements = array(
			-5 => 'Test Element -5',
			0 => 'Test Element 0',
			5 => 'Test Element 5',
		);

		foreach ($elements as $priority => $element) {
			$pl->add($element, $priority);
		}

		$this->assertEqual($pl->move($elements[-5], 10), 10);
		
		// check it's at the new place
		$this->assertIdentical($elements[-5], $pl->getElement(10));

		// check it's not at the old
		$this->assertFalse($pl->getElement(-5));
	}

	public function testElggPriorityListConstructor() {
		$elements = array(
			10 => 'Test Element 10',
			5 => 'Test Element 5',
			0 => 'Test Element 0',
			100 => 'Test Element 100',
			-1 => 'Test Element -1',
			-5 => 'Test Element -5'
		);

		$pl = new ElggPriorityList($elements);
		$test_elements = $pl->getElements();

		$elements_sorted = array(
			-5 => 'Test Element -5',
			-1 => 'Test Element -1',
			0 => 'Test Element 0',
			5 => 'Test Element 5',
			10 => 'Test Element 10',
			100 => 'Test Element 100',
		);

		$this->assertIdentical($elements_sorted, $test_elements);
	}

	public function testElggPriorityListGetPriority() {
		$pl = new ElggPriorityList();

		$elements = array(
			'Test element 0',
			'Test element 1',
			'Test element 2',
		);

		foreach ($elements as $element) {
			$pl->add($element);
		}

		$this->assertIdentical(0, $pl->getPriority($elements[0]));
		$this->assertIdentical(1, $pl->getPriority($elements[1]));
		$this->assertIdentical(2, $pl->getPriority($elements[2]));
	}

	public function testElggPriorityListGetElement() {
		$pl = new ElggPriorityList();
		$priorities = array();

		$elements = array(
			'Test element 0',
			'Test element 1',
			'Test element 2',
		);

		foreach ($elements as $element) {
			$priorities[] = $pl->add($element);
		}

		$this->assertIdentical($elements[0], $pl->getElement($priorities[0]));
		$this->assertIdentical($elements[1], $pl->getElement($priorities[1]));
		$this->assertIdentical($elements[2], $pl->getElement($priorities[2]));
	}

	public function testElggPriorityListPriorityCollision() {
		$pl = new ElggPriorityList();
		
		$elements = array(
			5 => 'Test element 5',
			6 => 'Test element 6',
			0 => 'Test element 0',
		);

		foreach ($elements as $priority => $element) {
			$pl->add($element, $priority);
		}

		// add at a colliding priority
		$pl->add('Colliding element', 5);

		// should float to the top closest to 5, so 7
		$this->assertEqual(7, $pl->getPriority('Colliding element'));
	}

	public function testElggPriorityListIterator() {
		$elements = array(
			-5 => 'Test element -5',
			0 => 'Test element 0',
			5 => 'Test element 5'
		);
		
		$pl = new ElggPriorityList($elements);

		foreach ($pl as $priority => $element) {
			$this->assertIdentical($elements[$priority], $element);
		}
	}

	public function testElggPriorityListCountable() {
		$pl = new ElggPriorityList();

		$this->assertEqual(0, count($pl));

		$pl->add('Test element 0');
		$this->assertEqual(1, count($pl));

		$pl->add('Test element 1');
		$this->assertEqual(2, count($pl));

		$pl->add('Test element 2');
		$this->assertEqual(3, count($pl));
	}

	public function testElggPriorityListUserSort() {
		$elements = array(
			'A',
			'B',
			'C',
			'D',
			'E',
		);

		$elements_sorted_string = $elements;

		shuffle($elements);
		$pl = new ElggPriorityList($elements);

		// will sort by priority
		$test_elements = $pl->getElements();
		$this->assertIdentical($elements, $test_elements);

		function test_sort($elements) {
			sort($elements, SORT_LOCALE_STRING);
			return $elements;
		}

		// force a new sort using our function
		$pl->sort('test_sort');
		$test_elements = $pl->getElements();

		$this->assertIdentical($elements_sorted_string, $test_elements);
	}

	// see http://trac.elgg.org/ticket/4288
	public function testElggBatchIncOffset() {
		// normal increment
		$options = array(
			'offset' => 0,
			'limit' => 11
		);
		$batch = new ElggBatch(array('ElggCoreHelpersTest', 'elgg_batch_callback_test'), $options,
				null, 5);
		$j = 0;
		foreach ($batch as $e) {
			$offset = floor($j / 5) * 5;
			$this->assertEqual($offset, $e['offset']);
			$this->assertEqual($j + 1, $e['index']);
			$j++;
		}

		$this->assertEqual(11, $j);

		// no increment, 0 start
		ElggCoreHelpersTest::elgg_batch_callback_test(array(), true);
		$options = array(
			'offset' => 0,
			'limit' => 11
		);
		$batch = new ElggBatch(array('ElggCoreHelpersTest', 'elgg_batch_callback_test'), $options,
				null, 5);
		$batch->setIncrementOffset(false);

		$j = 0;
		foreach ($batch as $e) {
			$this->assertEqual(0, $e['offset']);
			// should always be the same 5
			$this->assertEqual($e['index'], $j + 1 - (floor($j / 5) * 5));
			$j++;
		}
		$this->assertEqual(11, $j);

		// no increment, 3 start
		ElggCoreHelpersTest::elgg_batch_callback_test(array(), true);
		$options = array(
			'offset' => 3,
			'limit' => 11
		);
		$batch = new ElggBatch(array('ElggCoreHelpersTest', 'elgg_batch_callback_test'), $options,
				null, 5);
		$batch->setIncrementOffset(false);

		$j = 0;
		foreach ($batch as $e) {
			$this->assertEqual(3, $e['offset']);
			// same 5 results
			$this->assertEqual($e['index'], $j + 4 - (floor($j / 5) * 5));
			$j++;
		}

		$this->assertEqual(11, $j);
	}

	public function testElggBatchReadHandlesBrokenEntities() {
		$num_test_entities = 8;
		$guids = array();
		for ($i = $num_test_entities; $i > 0; $i--) {
			$entity = new ElggObject();
			$entity->type = 'object';
			$entity->subtype = 'test_5357_subtype';
			$entity->access_id = ACCESS_PUBLIC;
			$entity->save();
			$guids[] = $entity->guid;
			_elgg_invalidate_cache_for_entity($entity->guid);
		}

		// break entities such that the first fetch has one incomplete
		// and the second and third fetches have only incompletes!
		$db_prefix = elgg_get_config('dbprefix');
		delete_data("
			DELETE FROM {$db_prefix}objects_entity
			WHERE guid IN ({$guids[1]}, {$guids[2]}, {$guids[3]}, {$guids[4]}, {$guids[5]})
		");

		$options = array(
			'type' => 'object',
			'subtype' => 'test_5357_subtype',
			'order_by' => 'e.guid',
		);

		$entities_visited = array();
		$batch = new ElggBatch('elgg_get_entities', $options, null, 2);
		/* @var ElggEntity[] $batch */
		foreach ($batch as $entity) {
			$entities_visited[] = $entity->guid;
		}

		// The broken entities should not have been visited
		$this->assertEqual($entities_visited, array($guids[0], $guids[6], $guids[7]));

		// cleanup (including leftovers from previous tests)
		$entity_rows = elgg_get_entities(array_merge($options, array(
			'callback' => '',
			'limit' => false,
		)));
		$guids = array();
		foreach ($entity_rows as $row) {
			$guids[] = $row->guid;
		}
		delete_data("DELETE FROM {$db_prefix}entities WHERE guid IN (" . implode(',', $guids) . ")");
		delete_data("DELETE FROM {$db_prefix}objects_entity WHERE guid IN (" . implode(',', $guids) . ")");
	}

	public function testElggBatchDeleteHandlesBrokenEntities() {
		$num_test_entities = 8;
		$guids = array();
		for ($i = $num_test_entities; $i > 0; $i--) {
			$entity = new ElggObject();
			$entity->type = 'object';
			$entity->subtype = 'test_5357_subtype';
			$entity->access_id = ACCESS_PUBLIC;
			$entity->save();
			$guids[] = $entity->guid;
			_elgg_invalidate_cache_for_entity($entity->guid);
		}

		// break entities such that the first fetch has one incomplete
		// and the second and third fetches have only incompletes!
		$db_prefix = elgg_get_config('dbprefix');
		delete_data("
			DELETE FROM {$db_prefix}objects_entity
			WHERE guid IN ({$guids[1]}, {$guids[2]}, {$guids[3]}, {$guids[4]}, {$guids[5]})
		");

		$options = array(
			'type' => 'object',
			'subtype' => 'test_5357_subtype',
			'order_by' => 'e.guid',
		);

		$entities_visited = array();
		$batch = new ElggBatch('elgg_get_entities', $options, null, 2, false);
		/* @var ElggEntity[] $batch */
		foreach ($batch as $entity) {
			$entities_visited[] = $entity->guid;
			$entity->delete();
		}

		// The broken entities should not have been visited
		$this->assertEqual($entities_visited, array($guids[0], $guids[6], $guids[7]));

		// cleanup (including leftovers from previous tests)
		$entity_rows = elgg_get_entities(array_merge($options, array(
			'callback' => '',
			'limit' => false,
		)));
		$guids = array();
		foreach ($entity_rows as $row) {
			$guids[] = $row->guid;
		}
		delete_data("DELETE FROM {$db_prefix}entities WHERE guid IN (" . implode(',', $guids) . ")");
		delete_data("DELETE FROM {$db_prefix}objects_entity WHERE guid IN (" . implode(',', $guids) . ")");
	}

	static function elgg_batch_callback_test($options, $reset = false) {
		static $count = 1;

		if ($reset) {
			$count = 1;
			return true;
		}

		if ($count > 20) {
			return false;
		}

		for ($j = 0; ($options['limit'] < 5) ? $j < $options['limit'] : $j < 5; $j++) {
			$return[] = array(
				'offset' => $options['offset'],
				'limit' => $options['limit'],
				'count' => $count++,
				'index' => 1 + $options['offset'] + $j
			);
		}

		return $return;
	}
}