<?php

/**
 * Elgg Test helper functions
 *
 *
 * @package    Elgg
 * @subpackage Test
 */
class ElggCoreHelpersTest extends \ElggCoreUnitTest {

	public function up() {

	}

	public function down() {
		_elgg_services()->externalFiles->reset();
	}

	/**
	 * Test elgg_instanceof()
	 */
	public function testElggInstanceOf() {
		$entity = new \ElggObject();
		$entity->subtype = 'test_subtype';
		$entity->save();

		$this->assertTrue(elgg_instanceof($entity));
		$this->assertTrue(elgg_instanceof($entity, 'object'));
		$this->assertTrue(elgg_instanceof($entity, 'object', 'test_subtype'));

		$this->assertFalse(elgg_instanceof($entity, 'object', 'invalid_subtype'));
		$this->assertFalse(elgg_instanceof($entity, 'user', 'test_subtype'));

		$entity->delete();

		$bad_entity = false;
		$this->assertFalse(elgg_instanceof($bad_entity));
		$this->assertFalse(elgg_instanceof($bad_entity, 'object'));
		$this->assertFalse(elgg_instanceof($bad_entity, 'object', 'test_subtype'));
	}

	/**
	 * Test elgg_normalize_url()
	 */
	public function testElggNormalizeURL() {
		$conversions = [
			'http://example.com' => 'http://example.com',
			'https://example.com' => 'https://example.com',
			'http://example-time.com' => 'http://example-time.com',

			'http://in:valid~url' => elgg_get_site_url() . 'http://in:valid~url',
			'https://in:valid~url' => elgg_get_site_url() . 'https://in:valid~url',

			'//example.com' => '//example.com',
			'ftp://example.com/file' => 'ftp://example.com/file',
			'mailto:brett@elgg.org' => 'mailto:brett@elgg.org',
			'javascript:alert("test")' => 'javascript:alert("test")',
			'app://endpoint' => 'app://endpoint',
			'tel:+1111111111' => 'tel:+1111111111',

			'example.com' => 'http://example.com',
			'example.com/subpage' => 'http://example.com/subpage',

			'http://example.com/ИмяПользователя' => 'http://example.com/ИмяПользователя',

			'http://example.com/a b' => 'http://example.com/a%20b',
			'http://example.com/?a=1 2' => 'http://example.com/?a=1%202',

			'page/handler' => elgg_get_site_url() . 'page/handler',
			'page/handler?p=v&p2=v2' => elgg_get_site_url() . 'page/handler?p=v&p2=v2',
			'mod/plugin/file.php' => elgg_get_site_url() . 'mod/plugin/file.php',
			'mod/plugin/file.php?p=v&p2=v2' => elgg_get_site_url() . 'mod/plugin/file.php?p=v&p2=v2',
			'rootfile.php' => elgg_get_site_url() . 'rootfile.php',
			'rootfile.php?p=v&p2=v2' => elgg_get_site_url() . 'rootfile.php?p=v&p2=v2',

			'/page/handler' => elgg_get_site_url() . 'page/handler',
			'/page/handler?p=v&p2=v2' => elgg_get_site_url() . 'page/handler?p=v&p2=v2',
			'/mod/plugin/file.php' => elgg_get_site_url() . 'mod/plugin/file.php',
			'/mod/plugin/file.php?p=v&p2=v2' => elgg_get_site_url() . 'mod/plugin/file.php?p=v&p2=v2',
			'/rootfile.php' => elgg_get_site_url() . 'rootfile.php',
			'/rootfile.php?p=v&p2=v2' => elgg_get_site_url() . 'rootfile.php?p=v&p2=v2',
		];

		foreach ($conversions as $input => $output) {
			$this->assertIdentical($output, elgg_normalize_url($input));
		}
	}

	/**
	 * Test elgg_register_js()
	 */
	public function testElggRegisterJS() {
		// specify name
		$result = elgg_register_js('key', 'http://test1.com', 'footer');
		$this->assertTrue($result);

		$item = _elgg_services()->externalFiles->getFile('js', 'key');
		$this->assertNotNull($item);
		$this->assertTrue($item->priority !== false);
		$this->assertIdentical('http://test1.com', $item->url);

		// send a bad url
		$result = elgg_register_js('bad', null);
		$this->assertFalse($result);
	}

	/**
	 * Test elgg_register_css()
	 */
	public function testElggRegisterCSS() {
		// specify name
		$result = elgg_register_css('key', 'http://test1.com');
		$this->assertTrue($result);

		$item = _elgg_services()->externalFiles->getFile('css', 'key');
		$this->assertNotNull($item);
		$this->assertTrue($item->priority !== false);
		$this->assertIdentical('http://test1.com', $item->url);
	}

	/**
	 * Test elgg_unregister_js()
	 */
	public function testElggUnregisterJS() {
		$api = _elgg_services()->externalFiles;

		$base = trim(elgg_get_site_url(), "/");
		$urls = [
			'id1' => "$base/urla",
			'id2' => "$base/urlb",
			'id3' => "$base/urlc",
		];

		foreach ($urls as $id => $url) {
			elgg_register_js($id, $url);
		}

		$result = elgg_unregister_js('id1');
		$this->assertTrue($result);
		$this->assertNull($api->getFile('js', 'id1'));

		$elements = $api->getRegisteredFiles('js', 'head');

		foreach ($elements as $element) {
			if (isset($element->name)) {
				$this->assertFalse($element->name == 'id1');
			}
		}

		$this->assertFalse(elgg_unregister_js('id1'));

		$this->assertFalse(elgg_unregister_js('does_not_exist'));

		elgg_unregister_js('id2');

		$elements = $api->getRegisteredFiles('js', 'head');
		foreach ($elements as $element) {
			if (isset($element->name)) {
				$this->assertFalse($element->name == 'id2');
			}
		}

		$priority = $api->getFile('js', 'id3')->priority;
		$this->assertTrue($priority !== false);
	}

	/**
	 * Test elgg_load_js()
	 */
	public function testElggLoadJS() {
		// load before register
		elgg_load_js('key');
		$result = elgg_register_js('key', 'http://test1.com', 'footer');
		$this->assertTrue($result);

		$js_urls = elgg_get_loaded_js('footer');
		$this->assertIdentical([500 => 'http://test1.com'], $js_urls);
	}

	/**
	 * Test elgg_get_loaded_js()
	 */
	public function testElggGetJS() {
		$base = trim(elgg_get_site_url(), "/");

		$urls = [
			'id1' => "$base/urla",
			'id2' => "$base/urlb",
			'id3' => "$base/urlc"
		];

		foreach ($urls as $id => $url) {
			elgg_register_js($id, $url);
			elgg_load_js($id);
		}

		$js_urls = elgg_get_loaded_js('head');

		$this->assertIdentical($js_urls[500], $urls['id1']);
		$this->assertIdentical($js_urls[501], $urls['id2']);
		$this->assertIdentical($js_urls[502], $urls['id3']);

		$js_urls = elgg_get_loaded_js('footer');
		$this->assertIdentical([], $js_urls);
	}
}