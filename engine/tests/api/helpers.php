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
	}

	/**
	 * Test elgg_normalize_url()
	 */
	public function testElggNormalizeURL() {
		$conversions = array(
			'http://example.com' => 'http://example.com',
			'https://example.com' => 'https://example.com',
			'//example.com' => '//example.com',

			'example.com' => 'http://example.com',
			'example.com/subpage' => 'http://example.com/subpage',

			'pg/page/handler' =>                elgg_get_site_url() . 'pg/page/handler',
			'pg/page/handler?p=v&p2=v2' =>      elgg_get_site_url() . 'pg/page/handler?p=v&p2=v2',
			'mod/plugin/file.php' =>            elgg_get_site_url() . 'mod/plugin/file.php',
			'mod/plugin/file.php?p=v&p2=v2' =>  elgg_get_site_url() . 'mod/plugin/file.php?p=v&p2=v2',
			'rootfile.php' =>                   elgg_get_site_url() . 'rootfile.php',
			'rootfile.php?p=v&p2=v2' =>         elgg_get_site_url() . 'rootfile.php??p=v&p2=v2',

			'/pg/page/handler' =>               elgg_get_site_url() . 'pg/page/handler',
			'/pg/page/handler?p=v&p2=v2' =>     elgg_get_site_url() . 'pg/page/handler?p=v&p2=v2',
			'/mod/plugin/file.php' =>           elgg_get_site_url() . 'mod/plugin/file.php',
			'/mod/plugin/file.php?p=v&p2=v2' => elgg_get_site_url() . 'mod/plugin/file.php?p=v&p2=v2',
			'/rootfile.php' =>                  elgg_get_site_url() . 'rootfile.php',
			'/rootfile.php?p=v&p2=v2' =>        elgg_get_site_url() . 'rootfile.php??p=v&p2=v2',
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

		// specify id
		$result = elgg_register_js('//test1.com', 'key', 'footer');
		$this->assertTrue($result);
		$this->assertIdentical('//test1.com', $CONFIG->externals['javascript']['footer']['key']);

		// let Elgg pick id
		$result = elgg_register_js('//test2.com');
		$this->assertTrue($result);
		$this->assertIdentical('//test2.com', $CONFIG->externals['javascript']['head'][0]);

		// send a bad url
		$result = elgg_register_js();
		$this->assertFalse($result);
	}

	/**
	 * Test elgg_register_css()
	 */
	public function testElggRegisterCSS() {
		global $CONFIG;

		// specify id
		$result = elgg_register_css('//test1.com', 'key');
		$this->assertTrue($result);
		$this->assertIdentical('//test1.com', $CONFIG->externals['css']['head']['key']);

		// let Elgg pick id
		$result = elgg_register_css('//test2.com');
		$this->assertTrue($result);
		$this->assertIdentical('//test2.com', $CONFIG->externals['css']['head'][1]);

		// send a bad url
		$result = elgg_register_js();
		$this->assertFalse($result);
	}

	/**
	 * Test elgg_unregister_js()
	 */
	public function testElggUnregisterJS() {
		global $CONFIG;

		$urls = array('id1' => '//url1.com', 'id2' => '//url2.com', 'id3' => '//url3.com');
		foreach ($urls as $id => $url) {
			elgg_register_js($url, $id);
		}

		$result = elgg_unregister_js('id1');
		$this->assertTrue($result);
		$this->assertNULL($CONFIG->externals['javascript']['head']['id1']);

		$result = elgg_unregister_js('', '//url2.com');
		$this->assertTrue($result);
		$this->assertNULL($CONFIG->externals['javascript']['head']['id2']);

		$result = elgg_unregister_js('id1');
		$this->assertFalse($result);
		$result = elgg_unregister_js('', '//url2.com');
		$this->assertFalse($result);

		$this->assertIdentical($urls['id3'], $CONFIG->externals['javascript']['head']['id3']);
	}

	/**
	 * Test elgg_get_js()
	 */
	public function testElggGetJS() {
		global $CONFIG;

		$urls = array('id1' => '//url1.com', 'id2' => '//url2.com', 'id3' => '//url3.com');
		foreach ($urls as $id => $url) {
			elgg_register_js($url, $id);
		}

		$js_urls = elgg_get_js('head');
		$this->assertIdentical($js_urls[0], $urls['id1']);
		$this->assertIdentical($js_urls[1], $urls['id2']);
		$this->assertIdentical($js_urls[2], $urls['id3']);

		$js_urls = elgg_get_js('footer');
		$this->assertIdentical(array(), $js_urls);
	}
}
