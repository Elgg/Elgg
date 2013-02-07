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
	
	/**
	 * Test elgg_get_friendly_time()
	 */
	public function testElggGetFriendlyTime() {

		$current_time = time();
		$offsets = array(
			'0' => elgg_echo('friendlytime:justnow'),
			'-120' => elgg_echo('friendlytime:minutes', array('2')),
			'-60' => elgg_echo('friendlytime:minutes:singular'),
			'-10800' => elgg_echo('friendlytime:hours', array('3')),
			'-86400' => elgg_echo('friendlytime:days:singular'),
			'120' => elgg_echo('friendlytime:future:minutes', array('2')),
			'86400' => elgg_echo('friendlytime:future:days:singular'),
		);
		
		foreach ($offsets as $num_seconds => $friendlytime) {
			$this->assertIdentical(elgg_get_friendly_time($current_time + $num_seconds, $current_time), $friendlytime);
		}
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